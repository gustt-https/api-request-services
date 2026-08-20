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
        Schema::create('client_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('profile_status', [
                'pending',
                'active',
                'blocked',
            ])->default('pending');

            $table->timestamp('phone_confirmed_at')->nullable();

            $table->string('default_cep', 9)->nullable();
            $table->string('default_address')->nullable();
            $table->string('default_address_number')->nullable();
            $table->string('default_complement')->nullable();
            $table->string('default_neighborhood')->nullable();
            $table->string('default_city')->nullable();
            $table->string('default_state', 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_profiles');
    }
};
