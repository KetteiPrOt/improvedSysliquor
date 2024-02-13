<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('unitary_price', 5, 2, true)->unsigned();
            $table->smallInteger('amount')->unsigned();
            $table->decimal('total_price', 8, 2, true)->unsigned();

            // Adding foreign keys
            $table->unsignedTinyInteger('movement_type_id');
            $table->foreign('movement_type_id', 'movement_type')
                  ->references('id')
                  ->on('movement_types')
                  ->restrictOnDelete()->restrictOnUpdate();
                
            $table->unsignedMediumInteger('product_id');
            $table->foreign('product_id', 'movement_product')
                  ->references('id')
                  ->on('products')
                  ->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('invoice_id');
            $table->foreign('invoice_id', 'invoice_movement')
                  ->references('id')
                  ->on('invoices')
                  ->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedTinyInteger('warehouse_id');
            $table->foreign('warehouse_id', 'movement_warehouse')
                ->references('id')
                ->on('warehouses')
                ->restrictOnDelete()->restrictOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movements');
    }
};
