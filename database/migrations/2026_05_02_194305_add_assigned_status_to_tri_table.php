<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE trips MODIFY COLUMN status ENUM('pending', 'assigned', 'accepted', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending'");
    }

    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE trips MODIFY COLUMN status ENUM('pending', 'accepted', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};
