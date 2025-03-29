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
            $table->id();
            $table->timestamp('invoice_date')->default(now());
            $table->text('details')->nullable();
            $table->string('URL');
            $table->enum('status', ['Pending', 'Assigned'])->default('Pending');
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
