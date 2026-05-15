<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->foreignId('vehicle_type_id')->nullable()->after('client_id')->constrained('vehicle_types')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            //
        });
    }
};
