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
        Schema::table('payment', function (Blueprint $table) {
            $table->string('provider_payment_id')
                ->nullable()
                ->change();

            $table->foreignId('request_id')
                ->nullable(false)
                ->change();

            $table->dropForeign(['request_id']);

            $table->foreign('request_id')
                ->references('id')
                ->on('requests')
                ->cascadeOnDelete();

            $table->unique('external_reference');
        });
    }

    public function down(): void
    {
        Schema::table('payment', function (Blueprint $table) {
            $table->dropUnique(['external_reference']);

            $table->dropForeign(['request_id']);

            $table->foreign('request_id')
                ->references('id')
                ->on('requests')
                ->nullOnDelete();

            $table->foreignId('request_id')
                ->nullable()
                ->change();

            $table->string('provider_payment_id')
                ->nullable(false)
                ->change();
        });
    }
};
