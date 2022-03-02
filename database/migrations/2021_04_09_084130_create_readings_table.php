<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('readings', function (Blueprint $table) {
            $table->id();
            $table->integer('meter_id');
            $table->integer('billable');
            $table->integer('reading');
            $table->boolean('accepted1');
            $table->boolean('accepted2');
            $table->string('image');
            $table->string('reading_status');
            $table->string('obstacle_code');
            $table->integer('daily_average_consumption');
            $table->integer('replacement_consumption');
            $table->integer('disconnection_reading');
            $table->integer('disconnection_date');
            $table->integer('meter_reading_sequence');
            $table->integer('temperature');
            $table->integer('pressure');
            $table->integer('adjustment_consumption');
            $table->integer('meter_reader_id');
            $table->integer('supervisor_branch_id');
            $table->integer('supervisor_id');
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
        Schema::dropIfExists('readings');
    }
}
