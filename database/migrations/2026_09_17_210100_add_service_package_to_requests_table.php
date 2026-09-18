<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Link a request to the chosen package. package_name + price on requests
     * are snapshots so past jobs stay readable if the catalog changes.
     */
    public function up(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->foreignId('service_package_id')
                ->nullable()
                ->after('worker_id')
                ->constrained('service_packages')
                ->nullOnDelete();

            $table->string('package_name')
                ->nullable()
                ->after('service_package_id');
        });
    }

    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('service_package_id');
            $table->dropColumn('package_name');
        });
    }
};
