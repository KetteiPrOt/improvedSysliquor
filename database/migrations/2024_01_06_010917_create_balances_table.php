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
        Schema::create('balances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->mediumInteger('amount')->unsigned();
            $table->decimal('unitary_price', 5, 2, true)->unsigned();

            // Adding foreign keys
            $table->unsignedBigInteger('movement_id')->unique();
            $table->foreign('movement_id', 'balance_movement')
                  ->references('id')
                  ->on('movements')
                  ->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balances');
    }
};
