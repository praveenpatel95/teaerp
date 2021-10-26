<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id');
            $table->bigInteger('salesman_id')->nullable();
            $table->double('pay_amount');
            $table->dateTime('pay_date');
            $table->text('remark')->nullable();
            $table->string('rec_no','50');
            $table->string('paymode',100)->comment('1 => Cash, 2 => Account, 3=>Paytm, 4 =>Phone Pay, 5=>Google Pay, 6=>Adhar Card');
            $table->foreign('sale_id')->on('sales')->references('id')->onDelete('cascade');
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
        Schema::dropIfExists('sale_payments');
    }
}
