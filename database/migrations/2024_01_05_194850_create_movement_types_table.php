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
        Schema::create('movement_types', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 50);

            // Adding foreign keys
            $table->unsignedTinyInteger('movement_category_id');
            $table->foreign('movement_category_id', 'movement_category_movement_type')
                  ->references('id')
                  ->on('movement_categories')
                  ->restrictOnDelete()->restrictOnUpdate();;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movement_types');
    }
};
