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
        if (!Schema::hasTable('invoices')) {
            Schema::create('invoices', function (Blueprint $table) {
                $table->id('invoice_id');
                $table->unsignedBigInteger('customer_id');
                $table->decimal('total_amount', 8, 2);
                $table->string('original_currency');
                $table->decimal('total_amount_selected_currency', 8, 2);
                $table->string('selected_currency');
                $table->date('invoice_date');
                $table->enum('payment_status', ['Paid', 'Pending']);
                $table->timestamps();

                $table->foreign('customer_id')
                    ->references('customer_id')->on('customers')
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
        Schema::dropIfExists('invoices');
    }
};
