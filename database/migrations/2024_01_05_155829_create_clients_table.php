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
        Schema::create('clients', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('identification_card', 10)->unique()->nullable();
            $table->string('ruc', 13)->unique()->nullable();
            $table->string('social_reason', 75)->nullable();

            // Adding foreign keys
            $table->unsignedMediumInteger('person_id');
            $table->foreign('person_id', 'client_person')
                  ->references('id')
                  ->on('persons')
                  ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
