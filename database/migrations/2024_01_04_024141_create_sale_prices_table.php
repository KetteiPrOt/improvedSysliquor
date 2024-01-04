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
        Schema::create('sale_prices', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->decimal('price', 5, 2, true);

            // foreign keys
            $table->unsignedTinyInteger('units_number_id');
            $table->foreign('units_number_id', 'sale_price_units_number')
                  ->references('id')
                  ->on('units_numbers');

            $table->unsignedMediumInteger('product_id');
            $table->foreign('product_id', 'product_sale_price')
                  ->references('id')
                  ->on('products')
                  ->onUpdate('cascade')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_prices');
    }
};
