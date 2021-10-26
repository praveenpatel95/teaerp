<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_routes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('line_id');
            $table->unsignedBigInteger('route_id');
            $table->foreign('line_id')
                ->on('lines')->references('id')->onDelete('cascade');
            $table->foreign('route_id')
                ->on('routes')->references('id')->onDelete('cascade');
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
        Schema::dropIfExists('line_routes');
    }
}
