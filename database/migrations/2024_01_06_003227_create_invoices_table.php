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
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('number', 15)->nullable();
            $table->date('date');

            // Add foreign keys
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id', 'invoice_user')
                  ->references('id')
                  ->on('users')
                  ->restrictOnDelete()->restrictOnUpdate();
                
            $table->unsignedMediumInteger('person_id')->nullable();
            $table->foreign('person_id', 'invoice_person')
                  ->references('id')
                  ->on('persons')
                  ->nullOnDelete()->cascadeOnUpdate();

            $table->unsignedTinyInteger('movement_category_id');
            $table->foreign('movement_category_id', 'invoice_movement_category')
                  ->references('id')
                  ->on('movement_categories')
                  ->restrictOnDelete()->restrictOnUpdate();

            $table->unsignedTinyInteger('warehouse_id');
            $table->foreign('warehouse_id', 'invoice_warehouse')
                ->references('id')
                ->on('warehouses')
                ->restrictOnDelete()->restrictOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
