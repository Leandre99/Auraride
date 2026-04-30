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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_approved')->default(true); // Default true for now, can be false for new drivers
            $table->boolean('is_active')->default(true);
        });

        Schema::table('trips', function (Blueprint $table) {
            $table->string('payment_status')->default('pending');
            $table->string('payment_method')->nullable();
            $table->integer('rating')->nullable();
            $table->text('comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_approved', 'is_active']);
        });

        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'payment_method', 'rating', 'comment']);
        });
    }
};
