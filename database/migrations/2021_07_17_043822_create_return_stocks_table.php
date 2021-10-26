<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('salesman_id');
            $table->unsignedBigInteger('product_id');
            $table->bigInteger('qty');
            $table->date('return_date');
            $table->foreign('salesman_id')->on('salesmen')->references('id')->onDelete('cascade');
            $table->foreign('product_id')->on('products')->references('id')->onDelete('cascade');
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
        Schema::dropIfExists('return_stocks');
    }
}
