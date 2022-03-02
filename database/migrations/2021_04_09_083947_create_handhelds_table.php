<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHandheldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('handhelds', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('meter_reader');
            $table->string('type');
            $table->string('brand');
            $table->integer('branch_id');
            $table->string('status');
            $table->string('location');
            $table->date('date_dispose');
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
        Schema::dropIfExists('handhelds');
    }
}
