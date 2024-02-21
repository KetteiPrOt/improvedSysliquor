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
        Schema::create('warehouses_existences', function (Blueprint $table) {
            $table->id();
            $table->mediumInteger('amount')->unsigned();

            $table->unsignedMediumInteger('product_id');
            $table->foreign('product_id', 'product_warehouse_existence')
                    ->references('id')
                    ->on('products')
                    ->cascadeOnDelete()->cascadeOnUpdate();
            
            $table->unsignedTinyInteger('warehouse_id');
            $table->foreign('warehouse_id', 'warehouse_existence_warehouse')
                    ->references('id')
                    ->on('warehouses')
                    ->restrictOnDelete()->restrictOnUpdate();
            
            $table->unsignedBigInteger('movement_id')->unique()->nullable();
            $table->foreign('movement_id', 'movement_warehouse_existence')
                    ->references('id')
                    ->on('movements')
                    ->nullOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses_existences');
    }
};
