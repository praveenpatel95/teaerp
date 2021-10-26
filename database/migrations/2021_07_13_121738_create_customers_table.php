<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
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
            $table->integer('route_id');
            $table->string('customer_name',100);
            $table->string('father_name',100);
            $table->string('mobile_no',10)->nullable();
            $table->text('address')->nullable();
            $table->string('adhar_no',12)->nullable();
            $table->double('due_balance')->nullable();
            $table->double('old_due_balance')->nullable();
            $table->bigInteger('order_no');
            $table->tinyInteger('status')->default(1);
            $table->string('customer_photo')->nullable();
            $table->date('return_date')->nullable();
            $table->tinyInteger('customer_type')->default(0)->comment('0 for customer 1 for shopkeeper');
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
        Schema::dropIfExists('customers');
    }
}
