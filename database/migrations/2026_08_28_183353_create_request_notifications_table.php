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
        Schema::create('request_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')
                ->constrained('users', 'id')
                ->cascadeOnDelete();
            $table->foreignId('request_id')
                ->constrained('requests')
                ->cascadeOnDelete();
            $table->string('status');
            $table->timestamp('notified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_notifications');
    }
};
