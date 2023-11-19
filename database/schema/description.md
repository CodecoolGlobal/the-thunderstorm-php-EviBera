## Table: WeatherMeasurements
#### Columns:
* measurement_id (Primary Key, Auto-increment)
* measurement_date (Date)
* attribute_id (Foreign Key referencing WeatherAttributes.attribute_id)
* value (Int)

## Table: WeatherAttributes
#### Columns:
* attribute_id (Primary Key, Auto-increment)
* attribute_name (VarChar) 
  * "Temperature" 
  * "Precipitation" 
  * "Humidity" 
  * "Wind Speed"
* unit (VarChar) 
  * "°C" 
  * "mm" 
  * "%" 
  * "km/h"
