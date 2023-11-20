<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class WeatherStatController extends Controller
{
    public function exportToExcel()
    {
        // Your logic to retrieve data from the database and process it

        // Create a new Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set the title of the worksheet
        $sheet->setTitle('Weather data');

        // Add headers
        $sheet->setCellValue('A1', 'day');
        $sheet->setCellValue('B1', 'minimum temperature (˚C)');
        $sheet->setCellValue('C1', 'average wind speed (km/h)');
        $sheet->setCellValue('D1', 'top precipitation (mm)');
        $sheet->setCellValue('E1', 'average grams of H2O per kg of air');

        $sheet->setCellValue('A2', 'Monday');
        $sheet->setCellValue('A3', 'Tuesday');
        $sheet->setCellValue('A4', 'Wednesday');
        $sheet->setCellValue('A5', 'Thursday');
        $sheet->setCellValue('A6', 'Friday');
        $sheet->setCellValue('A7', 'Saturday');
        $sheet->setCellValue('A8', 'Sunday');

        // Your logic to populate the data in the spreadsheet

        // Set the response headers for Excel file

        $response = response(null, 200);
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="weather_data.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        // Save the spreadsheet to a temporary file and return the response
        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'weather_data');
        $writer->save($tempFile);

        try {
            $response->setContent(file_get_contents($tempFile));
        } catch (\Exception $e) {
            // Handle the exception, log it, or return an error response
            return response()->json(['error' => 'Failed to read the file.'], 500);
        }

        // Clean up the temporary file
        unlink($tempFile);

        return $response;
    }
}
