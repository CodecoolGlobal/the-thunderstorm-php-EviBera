<?php

namespace App\Console\Commands;

class MakeWeatherData extends OutputCommand
{
    protected $signature   = 'make:weather:data';
    protected $description = 'Generate weather data';
    protected $weatherDataFilePath;

    public function __construct()
    {
        parent::__construct();
        $this->weatherDataFilePath = (storage_path() . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR);
    }

    public function handle()
    {
        try {
            $this->weatherDataToFile($this->generateWeatherData());
            $this->output('Weather data generated successfully.', self::MSG_SUCCESS);
        }
        catch (\Exception $e) {
            $this->output('An error occurred!', self::MSG_ERROR);
            $this->output($e->getMessage());
        }

        return 0;
    }

    protected function generateWeatherData()
    {
        $startDate   = new \DateTime(date('Y-m-01 00:00:00'));
        $startDate->modify('-6 month');
        $weatherData = array();
        for ($measureDate = $startDate; $measureDate < new \DateTime('today'); $measureDate->modify('+1 day')) {
            $weatherData[] = array(
                'measurement_date'                 => $measureDate->format('Y-m-d'),
                'average_temperature_celsius'      => (random_int(22, 35) . ' °C'),
                'average_precipitation_millimeter' => (random_int(25, 33) . ' mm'),
                'average_humidity_percent'         => (random_int(65, 85) . '%'),
                'average_wind_speed'               => (random_int(0, 5) . ' km/h'),
            );
        }

        return $weatherData;
    }

    protected function weatherDataToFile(array $weatherData)
    {
        $file = fopen(($this->weatherDataFilePath . 'weather_data.json'), 'w+');
        fwrite($file, json_encode($weatherData, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR, 1024));
        fclose($file);
    }
}
