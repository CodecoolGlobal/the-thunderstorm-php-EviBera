<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class WeatherStatController extends Controller
{
    public function exportToExcel()
    {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Weather data');

        // Headers
        $headers = ['Day of Week', 'Minimum Temperature (°C)', 'Average Wind Speed (km/h)', 'Top Precipitation (mm)', 'Average H2O (g/kg)'];
        $sheet->fromArray($headers, null, 'A1');

        // Query and calculations
        $results = DB::select(DB::raw("
            SELECT
                DAYNAME(wm.measurement_date) as day_of_week,
                MIN(CASE WHEN wa.attribute_name = 'Temperature' THEN wm.value ELSE NULL END) as min_temperature,
                AVG(CASE WHEN wa.attribute_name = 'Wind Speed' THEN wm.value ELSE NULL END) as avg_wind_speed,
                MAX(CASE WHEN wa.attribute_name = 'Precipitation' THEN wm.value ELSE NULL END) as max_precipitation,
                AVG(CASE WHEN wa.attribute_name = 'Humidity' THEN wm.value ELSE NULL END) as avg_humidity
            FROM
                weather_measurements wm
            JOIN
                weather_attributes wa ON wm.attribute_id = wa.attribute_id
            GROUP BY
                DAYNAME(wm.measurement_date)
            ORDER BY
                FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
            "));

        $row = 2;
        foreach ($results as $result) {
            $sheet->setCellValue('A' . $row, $result->day_of_week);
            $sheet->setCellValue('B' . $row, $result->min_temperature);
            $sheet->setCellValue('C' . $row, $result->avg_wind_speed);
            $sheet->setCellValue('D' . $row, $result->max_precipitation);

            // Calculating average grams of H2O per kg of air
            $averageH2O = $result->avg_humidity * 0.42 * exp($result->min_temperature * 10 * 0.006235398) / 10;
            $sheet->setCellValue('E' . $row, $averageH2O);

            $row++;
        }

        // Ensure only 8 rows (including header)
        for (; $row <= 8; $row++) {
            $sheet->fromArray([null, null, null, null, null], null, 'A' . $row);
        }

        // Stream the file back to the user
        $response = new StreamedResponse(function() use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="weather_data.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;

    }

}
