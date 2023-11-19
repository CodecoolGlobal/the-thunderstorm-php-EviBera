<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeatherAttribute extends Model
{
    protected $table = 'weather_attributes';

    protected $primaryKey = 'attribute_id';

    protected $fillable = [
        'attribute_name',
        'unit',
    ];

    // Define the reverse relationship with WeatherMeasurements
    public function measurements()
    {
        return $this->hasMany(WeatherMeasurement::class, 'attribute_id', 'attribute_id');
    }
}
