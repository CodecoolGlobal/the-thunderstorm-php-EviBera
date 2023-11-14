# The ThunderstORM

## Story

You're planning a well-deserved vacation, and it seems like Cherrapunji is the perfect place for it so far.
The only problem is that this small town in India is considered one of the rainiest places on Earth.

Before you pick the exact time you'll leave, you want to create a database containing all weather data for statistical reasons, not to get caught in a thunderstorm...

## What are you going to learn?

- more advanced use of `Laravel`
- write custom `Artisan commands`
- what is `database normalization`
- what are `database transactions`
- how to generate Excel files using `PhpSpreadsheet`
- `return files` from an `API`
- control the output of a controller

## Tasks

1. Install the dependencies specified in `composer.json` by executing the `composer install` terminal command.
    - The `vendor` folder exists and contains at least a `laravel` directory and an `autoload.php` file.

2. Setup the database connection in the `.env` file.
    - The `.env` file exists and contains all parameters to connect to the database.
    - The `php artisan db:check:connection` command prints out `Connected successfully`.

3. Generate weather data by executing the `php artisan make:weather:data` command in the terminal.
    - The `weather_data.json` file exists in `/storage/data`.
    - The `weather_data.json` file is not empty.

4. Design the database schema that can store the weather data in a clean, normalized structure.
    - The `database schema` is designed and described in `/database/schema/description.md`.
    - There are at least 2 tables for storing weather data.
    - There is at least 1 connection between 2 tables.
    - Every table has a `primary key`.
    - There are no 2 columns with the same kind of data.

5. Create the models with migrations for the previously designed database schema.
    - The models exist in `app/Models/`.
    - The `database/migrations` folder contains the files for the model migrations.

6. Make a custom `Artisan` command for copying data from the `weather_data.json` file into the `database`.
    - The `store:weather:data` string appears in the output of the `php artisan list` command.
    - Running the `php artisan store:weather:data` command will read the data from `weather_data.json` and store it in the database.
    - All database operations use `transactions`.

7. Install the `phpoffice/phpspreadsheet` package using Composer.
    - The `phpspreadsheet` folder exists in `vendor/phpoffice`.

8. Create the `/stat/excel` endpoint that returns the `minimum temperature`, the `average wind speed`, the `top precipitation` and the calculated `average grams of H2O per kg of air` for every day of the week as a `XLSX` file.
    - The `/stat/excel` endpoint is defined and can be called.
    - The route returns a `XLSX` (Excel 2007) file.
    - The endpoint does not leave the file on the server after returning it.
    - The export file has only one sheet called `Weather data`.
    - The file contains exactly 8 rows (one for each day `from Monday to Sunday`, and the header).
    - The `Excel` file contains the `minimum temperature` for every day of the week.
    - The file contains the `average wind speed` as well.
    - The `top precipitation` is in the export file.
    - The calculated `average grams of H2O per kg of air` exists in the `XLSX` file.

9. [OPTIONAL] Setup every columns' width with automatic width calculation.
    - Every column of the export file has the same width as the longest data in it.

10. [OPTIONAL] Setup the page orientation to `landscape`.
    - After opening the file, the `print preview` shows a `landscape` orientation.

11. [OPTIONAL] Setup the page size to `A4`.
    - After opening the file, the `print preview` shows `A4` as the size of the page.

12. [OPTIONAL] Format the headers to be `bold`, have some shade of `red` as font color, and use `Arial` as the font family.
    - The headers are formatted as `bold`.
    - The headers have a `red` font color.
    - The headers have `Arial` as the font family.

13. [OPTIONAL] Create the frontend of the statistics with the tool of your choice (notable mentions are `React` and `Postman`).
    - There is a frontend that can call the `/stat/excel` route and return the statistics file.

## General requirements

- The `weather_data.json` file is not changed after it was generated.

## Hints

- The **equation** for calculating the **grams of H<sub>2</sub>O per kg of air** is the following:

  **RH &ast; 0.42 &ast; e<sup>(T &ast; 10 &ast; 0.006235398)</sup> &sol; 10**

  Where `RH` is the relative humidity (%), `e` is Euler's number, and `T` is the temperature in Celsius.
- The displayed measurement unit for it is usually **g/kg**
- You can check your calculations with [Lenntech's calculator](https://www.lenntech.com/calculators/humidity/relative-humidity.htm)
- The measurement units are quite repetitive, so extracting them on a database level can be beneficial.

## Background materials

- <i class="far fa-exclamation"></i> [Custom Artisan commands](https://www.cloudways.com/blog/custom-artisan-commands-laravel/)
- <i class="far fa-exclamation"></i> [Download files in Laravel](https://lavalite.org/blog/download-files-in-laravel)
- <i class="far fa-video"></i> [Database transactions](https://youtu.be/5Pia4UFuMKo)
- <i class="far fa-book-open"></i> [PDO transaction example](https://thisinterestsme.com/php-pdo-transaction-example/)
- <i class="far fa-video"></i> [Laravel DB transactions: When/How to use them](https://youtu.be/7daBdm2xgm8)
- <i class="far fa-video"></i> [Laravel DB transactions: Rollback on Error](https://youtu.be/GNZCIci0I64)
- <i class="far fa-book-open"></i> [Database normalization](https://www.guru99.com/database-normalization.html)
- <i class="far fa-exclamation"></i> [PHPSpreadsheet: Quickstart](https://phpspreadsheet.readthedocs.io/en/latest/)
- <i class="far fa-candy-cane"></i> [PHPSpreadsheet: Column width](https://spreadsheet-coding.com/phpspreadsheet/create-xlsx-files-with-auto-column-width-settings/)
- <i class="far fa-candy-cane"></i> [PHPSpreadsheet: Page setup](https://spreadsheet-coding.com/phpspreadsheet/create-xlsx-files-with-page-orientation-and-paper-size-settings/)
- <i class="far fa-candy-cane"></i> [PHPSpreadsheet: Formatting cells](https://www.htmlgoodies.com/beyond/exploring-phpspreadsheets-formatting-capabilities.html)
- <i class="far fa-book-open"></i> [Predefined math constants in PHP](https://www.php.net/manual/en/math.constants.php)
