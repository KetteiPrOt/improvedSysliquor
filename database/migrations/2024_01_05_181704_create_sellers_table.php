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
        Schema::create('sellers', function (Blueprint $table) {
            $table->smallIncrements('id');

            $table->string('identification_card', 10)->unique()->nullable();

            // Adding foreign keys
            $table->unsignedBigInteger('user_id')->unique();
            $table->foreign('user_id', 'seller_user')
                  ->references('id')
                  ->on('users')
                  ->restrictOnDelete()->restrictOnUpdate();

            $table->unsignedMediumInteger('person_id')->unique();
            $table->foreign('person_id', 'person_seller')
                  ->references('id')
                  ->on('persons')
                  ->restrictOnDelete()->restrictOnUpdate();
            
            $table->unsignedTinyInteger('warehouse_id');
            $table->foreign('warehouse_id', 'seller_warehouse')
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
        Schema::dropIfExists('sellers');
    }
};
