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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_type_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->time('pickup_time');
            $table->boolean('with_driver')->default(false);
            $table->string('delivery_address')->nullable();
            $table->decimal('daily_price', 10, 2)->default(0);
            $table->decimal('driver_fee_per_day', 10, 2)->default(0);
            $table->integer('total_days')->default(1);
            $table->decimal('total_price', 10, 2);
            $table->foreignId('assigned_vehicle_id')->nullable()->constrained('vehicles')->nullOnDelete();
            $table->text('admin_notes')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'rejected', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
