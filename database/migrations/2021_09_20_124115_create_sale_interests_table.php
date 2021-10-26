<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleInterestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_interests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id');
            $table->integer('amount');
            $table->double('percentage');
            $table->double('interest_amount');
            $table->date('interest_date');
            $table->string('rec_no',20);
            $table->tinyInteger('status')->default(0)->comment('0 for due 1 for paid');
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
        Schema::dropIfExists('sale_interests');
    }
}
