<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->bigInteger('salesman_id');
            $table->dateTime('sale_date');
            $table->string('sale_no',500);
            $table->double('sale_commission')->nullable();
            $table->date('commission_date')->nullable();
            $table->string('remark',200)->nullable();
            $table->double('total_amount');
            $table->tinyInteger('is_interest')->default(0);
            $table->date('last_recursive_date')->nullable();
            $table->foreign('customer_id')->on('customers')->references('id')->onDelete('cascade');
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
        Schema::dropIfExists('sales');
    }
}
