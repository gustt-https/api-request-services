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
        Schema::create('worker_identity_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_profile_id')->constrained('worker_profiles')->cascadeOnDelete();
            $table->enum('status', ['unverified', 'pending', 'approved', 'rejected'])->default('unverified');
            $table->string('document_type', 32)->nullable();
            $table->string('document_number', 64)->nullable();
            $table->string('front_path')->nullable();
            $table->string('back_path')->nullable();
            $table->string('selfie_path')->nullable();
            $table->string('provider', 64)->nullable();
            $table->string('provider_ref')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->string('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worker_identity_verifications');
    }
};
