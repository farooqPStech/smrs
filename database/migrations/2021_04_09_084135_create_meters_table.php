<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meters', function (Blueprint $table) {
            $table->id();
            $table->integer('prefix');
            $table->string('meter_number');
            $table->integer('unit_measurement');
            $table->integer('dial_length');
            $table->integer('meter_location_id');
            $table->string('meter_type');
            $table->integer('consumer_id');
            $table->integer('last_reading');
            $table->date('last_reading_date');
            $table->string('last_reading_status');
            $table->integer('daily_average_consumption');
            $table->date('meter_installation_date');
            $table->integer('replacement_consumption');
            $table->integer('disconnection_reading');
            $table->date('disconnection_date');
            $table->integer('meter_reading_sequence');
            $table->integer('temperature');
            $table->integer('pressure');
            $table->integer('cf_factor');
            $table->integer('z_factor');
            $table->integer('adjustment_consumption');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meters');
    }
}
