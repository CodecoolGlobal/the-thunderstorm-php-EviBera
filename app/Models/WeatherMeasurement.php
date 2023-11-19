<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherMeasurement extends Model
{
    protected $table = 'weather_measurements';

    protected $primaryKey = 'measurement_id';
    protected $fillable = [
        'measurement_date',
        'attribute_id',
        'value',
    ];

    // Define the relationship with WeatherAttributes
    public function attribute()
    {
        return $this->belongsTo(WeatherAttribute::class, 'attribute_id', 'attribute_id');
    }
}
