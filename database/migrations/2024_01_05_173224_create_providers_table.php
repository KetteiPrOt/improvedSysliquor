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
        Schema::create('providers', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('ruc', 13)->unique();
            $table->string('social_reason', 75);

            // Adding foreign keys
            $table->unsignedMediumInteger('person_id');
            $table->foreign('person_id', 'person_provider')
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
        Schema::dropIfExists('providers');
    }
};
