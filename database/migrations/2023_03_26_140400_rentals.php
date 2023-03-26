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
        if (!Schema::hasTable('rentals')) {
            Schema::create('rentals', function (Blueprint $table) {
                $table->id('rental_id');
                $table->unsignedBigInteger('customer_id');
                $table->unsignedBigInteger('vehicle_id');
                $table->date('start_date');
                $table->date('end_date');
                $table->integer('total_days');
                $table->unsignedBigInteger('invoice_id')->nullable();
                $table->timestamps();

                $table->foreign('customer_id')
                    ->references('customer_id')->on('customers')
                    ->onDelete('cascade');

                $table->foreign('vehicle_id')
                    ->references('vehicle_id')->on('vehicles')
                    ->onDelete('cascade');

                $table->foreign('invoice_id')
                    ->references('invoice_id')->on('invoices')
                    ->onDelete('set null');
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
        Schema::dropIfExists('rentals');
    }
};
