<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeatherMeasurementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weather_measurements', function (Blueprint $table) {
            $table->id('measurement_id');
            $table->date('measurement_date');
            $table->unsignedBigInteger('attribute_id');
            $table->integer('value');
            $table->timestamps();

            // Define foreign key
            $table->foreign('attribute_id')->references('attribute_id')->on('weather_attributes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weather_measurements');
    }
}
