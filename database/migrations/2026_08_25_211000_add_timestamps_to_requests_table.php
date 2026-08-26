<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            // Ajustado: accept/cancel e o RequestResource usam esses campos, mas eles não existiam na tabela.
            $table->timestamp('accepted_at')->nullable()->after('price');
            $table->timestamp('started_at')->nullable()->after('accepted_at');
            $table->timestamp('completed_at')->nullable()->after('started_at');
            $table->timestamp('cancelled_at')->nullable()->after('completed_at');
        });
    }

    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropColumn(['accepted_at', 'started_at', 'completed_at', 'cancelled_at']);
        });
    }
};
