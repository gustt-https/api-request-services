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
        Schema::create('request_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('requests');
            $table->foreignId('worker_id')->constrained('users', 'user_id');
            $table->string('status');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->longText('cancellation_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_applications');
    }
};
