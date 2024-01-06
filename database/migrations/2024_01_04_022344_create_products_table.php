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
        Schema::create('products', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('name', 50);
            $table->smallInteger('minimun_stock');
            $table->tinyInteger('started_inventory')->unsigned()->default(0);

            // Adding foreign keys
            $table->unsignedSmallInteger('type_id')->nullable();
            $table->foreign('type_id', 'product_type')
                  ->references('id')
                  ->on('types')
                  ->nullOnDelete()->cascadeOnUpdate();
            
            $table->unsignedSmallInteger('presentation_id')->nullable();
            $table->foreign('presentation_id', 'presentation_product')
                ->references('id')
                ->on('presentations')
                ->nullOnDelete()->cascadeOnUpdate();

            // Add unique product tag index
            $table->unique(['type_id', 'name', 'presentation_id'], 'unique_product_tag');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
