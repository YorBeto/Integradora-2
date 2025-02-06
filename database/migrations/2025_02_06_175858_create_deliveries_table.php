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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id(); // ID de la entrega
            $table->foreignId('invoice_id')->constrained('invoices'); // ID de la factura relacionada
            $table->dateTime('delivery_date')->default(DB::raw('CURRENT_TIMESTAMP')); // Fecha de entrega
            $table->string('carrier', 100); // Transportista
            $table->enum('status', ['Pending', 'In transit', 'Delivered'])->default('Pending'); // Estado de la entrega
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
        Schema::dropIfExists('deliveries');
    }
};
