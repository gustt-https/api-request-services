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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('worker_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('searching');
            $table->longText('description');
            $table->decimal('latitude');
            $table->decimal('longitude');
            $table->string('address');
            $table->integer('address_number');
            $table->string('complement');
            $table->decimal('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
