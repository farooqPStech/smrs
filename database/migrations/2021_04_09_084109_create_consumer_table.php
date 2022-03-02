<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsumerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumer', function (Blueprint $table) {
            $table->id();
            $table->integer('prefix');
            $table->integer('route_id');
            $table->integer('consumer_number');
            $table->string('old_account_number');
            $table->string('tariff_code');
            $table->string('consumer_name');
            $table->string('consumer_address_1');
            $table->string('consumer_address_2');
            $table->string('consumer_address_3');
            $table->string('contract_number');
            $table->string('area_code');
            $table->string('consumer_type');
            $table->string('consumer_status');
            $table->double('arrears');
            $table->string('deposit_type');
            $table->string('deposit');
            $table->date('last_normal_reading_date');
            $table->integer('last_bill_number');
            $table->integer('last_bill_date');
            $table->integer('last_bill_amount');
            $table->integer('last_payment_number');
            $table->date('last_payment_date');
            $table->integer('last_payment_amount');
            $table->string('last_bill_code');
            $table->string('evc');
            $table->integer('interest_charges');
            $table->integer('rebate');
            $table->integer('spi');
            $table->integer('consecutive_estimate_amount');
            $table->integer('capital_contribution');
            $table->integer('fix_charges');
            $table->integer('caloric_value');
            $table->integer('special_rate');
            $table->string('telephone_number');
            $table->double('market_price');
            $table->string('customer_full_name');
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
        Schema::dropIfExists('consumer');
    }
}
