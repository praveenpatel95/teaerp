<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('salesman_id');
            $table->decimal('work_hours',10,2)->nullable();
            $table->tinyInteger('type')->default(0)->comment('0 for present, 1 for absent ,2 for holiday ');
            $table->date('attendance_date');
            $table->integer('commission')->nullable();
            $table->double('salary')->nullable();
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
        Schema::dropIfExists('attendances');
    }
}
