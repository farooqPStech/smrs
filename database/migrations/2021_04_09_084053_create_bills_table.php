<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->double('price');
            $table->string('comment');
            $table->integer('consumer_id');
            $table->integer('bill_number');
            $table->string('area_code');
            $table->string('bill_type');
            $table->double('arrears');
            $table->string('deposit_type');
            $table->integer('deposit');
            $table->datetime('last_normal_reading_date');
            $table->datetime('bill_date');
            $table->double('bill_amount');
            $table->integer('payment_number');
            $table->date('payment_date');
            $table->double('payment_amount');
            $table->integer('bill_code');
            $table->string('evc');
            $table->integer('interest_charges');
            $table->double('rebate');
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
        Schema::dropIfExists('bills');
    }
}
