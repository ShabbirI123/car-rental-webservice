<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('vehicles')) {
            Schema::create('vehicles', function (Blueprint $table) {
                $table->id('vehicle_id');
                $table->unsignedBigInteger('vehicle_type_id');
                $table->unsignedBigInteger('location_id');
                $table->boolean('available');
                $table->timestamps();

                $table->foreign('vehicle_type_id')
                    ->references('vehicle_type_id')->on('vehicle_types')
                    ->onDelete('cascade');

                $table->foreign('location_id')
                    ->references('location_id')->on('locations')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }


};
