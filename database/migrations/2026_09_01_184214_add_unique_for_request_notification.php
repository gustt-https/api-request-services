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
        Schema::table('request_notifications', function (Blueprint $table) {
            $table->unique(['request_id', 'worker_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_notifications', function (Blueprint $table) {
            $table->dropUnique(['request_id', 'worker_id']);
        });
    }
};
