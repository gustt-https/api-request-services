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
        if (! Schema::hasTable('payment')) {
            return;
        }

        Schema::table('payment', function (Blueprint $table) {
            if (! Schema::hasColumn('payment', 'pix_payload')) {
                $table->text('pix_payload')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('payment') || ! Schema::hasColumn('payment', 'pix_payload')) {
            return;
        }

        Schema::table('payment', function (Blueprint $table) {
            $table->dropColumn('pix_payload');
        });
    }
};
