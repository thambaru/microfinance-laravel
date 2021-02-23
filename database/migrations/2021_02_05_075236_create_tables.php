<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('nic', 20);
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('phone_num')->nullable();
            $table->string('address_nic')->nullable();
            $table->string('address')->nullable();
            $table->string('address_bus')->nullable();
            $table->string('profession')->nullable();
            $table->timestamps();
        });

        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained();
            $table->double('loan_amount', 8, 2);
            $table->double('int_rate_mo', 4, 2);
            $table->date('start_date');
            $table->integer('installments', 8, 2);
            $table->double('rental', 8, 2);
            $table->timestamps();
        });

        Schema::create('guaranters', function (Blueprint $table) {
            $table->id();
            $table->string('nic', 20);
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('phone_num')->nullable();
            $table->string('address')->nullable();
            $table->string('profession')->nullable();
            $table->foreignId('loan_id')->constrained();
            $table->timestamps();
        });

        Schema::create('daily_records', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('loan_id')->constrained();
            $table->double('accrued_am', 8, 2);
            $table->double('paid_am', 8, 2);
            $table->double('accumulat_am', 8, 2);
            $table->double('arrears_tot', 8, 2);
            $table->double('excess_tot', 8, 2);
            $table->timestamps();
        });

        Schema::create('commiss_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rep_id')->constrained('users');
            $table->double('amount', 8, 2);
            $table->tinyInteger('type');
            $table->double('balance', 8, 2);
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
        Schema::dropIfExists('commiss_transactions');
        Schema::dropIfExists('daily_records');
        Schema::dropIfExists('guaranters');
        Schema::dropIfExists('loans');
        Schema::dropIfExists('customers');
    }
}
