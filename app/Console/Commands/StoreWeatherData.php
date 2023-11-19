<?php

namespace App\Console\Commands;

use App\Models\WeatherAttribute;
use App\Models\WeatherMeasurement;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StoreWeatherData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store:weather:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy data from the weather_data.json file into the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     *
     */
    public function handle()
    {
        try {
            // Start a database transaction
            DB::beginTransaction();

            $this->info('Copying data from weather_data.json to the database...');

            // Code to read data from weather_data.json and store it in the database
            $filePath = base_path('storage/data/weather_data.json');
            $data = json_decode(file_get_contents($filePath), true);

            foreach ($data as $entry) {
                // Extract measurement_date for each entry
                $measurementDate = $entry['measurement_date'];

                foreach ($entry as $key => $value) {
                    // Skip measurement_date as it's already extracted
                    if ($key === 'measurement_date') {
                        continue;
                    }

                    // Get or create the attribute based on the attribute name
                    $attribute = WeatherAttribute::firstOrCreate([
                        'attribute_name' => $this->getAttributeName($key),
                        'unit' => $this->getUnit($key),
                    ]);

                    $this->info("Attribute Name: {$this->getAttributeName($key)}, Unit: {$this->getUnit($key)}, Value: " . $this->getValue($value));

                    // Create a new measurement
                    WeatherMeasurement::create([
                        'measurement_date' => $measurementDate,
                        'attribute_id' => $attribute->attribute_id,
                        'value' => $this->getValue($value),
                    ]);
                }
            }
            // Commit the transaction if everything is successful
            DB::commit();

            $this->info('Data has been successfully copied to the database.');
        } catch (\Exception $e) {
            // An error occurred, rollback the transaction
            DB::rollBack();

            $this->error('An error occurred: ' . $e->getMessage());
        }
    }


    // Helper function to extract attribute name from the entry
    private function getAttributeName(string $key): string
    {
        // Map JSON keys to attribute names
        $attributeMap = [
            'average_temperature_celsius' => 'Temperature',
            'average_precipitation_millimeter' => 'Precipitation',
            'average_humidity_percent' => 'Humidity',
            'average_wind_speed' => 'Wind Speed',
        ];

        // Return the attribute name if found, or use the key
        return $attributeMap[$key] ?? ucfirst(str_replace('_', ' ', $key));
    }

    private function getUnit(string $key): string
    {
        // Map JSON keys to units
        $unitMap = [
            'average_temperature_celsius' => '°C',
            'average_precipitation_millimeter' => 'mm',
            'average_humidity_percent' => '%',
            'average_wind_speed' => 'km/h',
        ];

        // Check if the unit is an array, and implode it if necessary
        return isset($unitMap[$key]) ? (is_array($unitMap[$key]) ? implode(', ', $unitMap[$key]) : $unitMap[$key]) : '';
    }

    private function getValue($value)
    {
        return (int) filter_var($value, FILTER_SANITIZE_NUMBER_INT);
    }
}
