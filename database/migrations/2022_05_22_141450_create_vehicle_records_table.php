<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_records', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("vehicle_id")->index()->unsigned();
            $table->dateTime("vehicle_entrance_at");
            $table->dateTime("vehicle_exit_at")->nullable();
            $table->integer("minutes")->nullable();
            $table->double("cost",20,2)->nullable();
            $table->timestamps();
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_records');
    }
}
