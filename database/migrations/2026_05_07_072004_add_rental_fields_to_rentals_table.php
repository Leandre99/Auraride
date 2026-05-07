<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rentals', function (Blueprint $table) {
            // Vérifier si la colonne n'existe pas avant de l'ajouter
            if (!Schema::hasColumn('rentals', 'delivery_address')) {
                $table->text('delivery_address')->nullable()->after('pickup_time');
            }

            if (!Schema::hasColumn('rentals', 'certifies_license')) {
                $table->boolean('certifies_license')->default(false)->after('delivery_address');
            }

            if (!Schema::hasColumn('rentals', 'daily_price')) {
                $table->decimal('daily_price', 10, 2)->nullable()->after('certifies_license');
            }

            if (!Schema::hasColumn('rentals', 'driver_fee_per_day')) {
                $table->decimal('driver_fee_per_day', 10, 2)->default(0)->after('daily_price');
            }

            if (!Schema::hasColumn('rentals', 'total_days')) {
                $table->integer('total_days')->nullable()->after('driver_fee_per_day');
            }
        });
    }

    public function down()
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_address',
                'certifies_license',
                'daily_price',
                'driver_fee_per_day',
                'total_days'
            ]);
        });
    }
};
