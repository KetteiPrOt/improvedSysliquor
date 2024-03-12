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
        Schema::table('invoices', function (Blueprint $table) {
            $table->boolean('paid')->default(true);
            $table->string('comment', 750)->nullable()->default(null);
            $table->date('payment_due_date')->nullable()->default(null);
            $table->date('paid_date')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['paid', 'comment', 'payment_due_date', 'paid_date']);
        });
    }
};
