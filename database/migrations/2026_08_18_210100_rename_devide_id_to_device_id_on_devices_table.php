<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('devices')) {
            return;
        }

        if (Schema::hasColumn('devices', 'devide_id') && ! Schema::hasColumn('devices', 'device_id')) {
            Schema::table('devices', function (Blueprint $table) {
                $table->renameColumn('devide_id', 'device_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('devices', 'device_id') && ! Schema::hasColumn('devices', 'devide_id')) {
            Schema::table('devices', function (Blueprint $table) {
                $table->renameColumn('device_id', 'devide_id');
            });
        }
    }
};
