<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesmenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salesmen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('line_id');
            $table->string('name',100);
            $table->string('father_name',100);
            $table->string('mobile_no',10)->nullable();
            $table->text('address')->nullable();
            $table->string('adhar_no',12)->nullable();
            $table->string('salary_type',20);
            $table->double('salary_amount');
            $table->integer('commission')->nullable('in percentage');
            $table->date('joining_date');
            $table->string('password',100);
            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade');
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
        Schema::dropIfExists('salesmen');
    }
}
