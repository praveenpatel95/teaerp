<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesmanSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salesman_salaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('salesman_id');
            $table->double('salary_amount');
            $table->double('work_hours');
            $table->double('total_salary');
            $table->double('commission');
            $table->double('sale_commission');
            $table->double('total_amount');
            $table->double('advance_amount');
            $table->string('salary_month',10);
            $table->tinyinteger('pay_status')->default(0);
            $table->foreign('salesman_id')->on('salesmen')->references('id')->onDelete('cascade');
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
        Schema::dropIfExists('salesman_salaries');
    }
}
