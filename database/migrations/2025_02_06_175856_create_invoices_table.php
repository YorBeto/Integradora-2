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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id(); // ID de la factura
            $table->foreignId('store_id')->constrained('stores'); // ID de la tienda
            $table->dateTime('invoice_date')->default(DB::raw('CURRENT_TIMESTAMP')); // Fecha de la factura
            $table->decimal('total', 10, 2); // Total de la factura
            $table->text('details')->nullable(); // Detalles de la factura
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
        Schema::dropIfExists('invoices');
    }
};
