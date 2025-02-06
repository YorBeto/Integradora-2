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
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // ID del pedido
            $table->foreignId('store_id')->constrained('stores'); // ID de la tienda que hace el pedido
            $table->dateTime('order_date')->default(DB::raw('CURRENT_TIMESTAMP')); // Fecha del pedido
            $table->enum('status', ['Pending', 'Accepted', 'Rejected', 'In transit', 'Delivered'])->default('Pending'); // Estado del pedido
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
        Schema::dropIfExists('orders');
    }
};
