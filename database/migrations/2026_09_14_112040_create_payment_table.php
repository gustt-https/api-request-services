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
        Schema::create('payment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')
                ->nullable()
                ->constrained('requests')
                ->nullOnDelete();
            $table->string('provider');
            /** Asaas ids look like `pay_…` — must be string, not integer. */
            $table->string('provider_payment_id');
            $table->string('external_reference');
            $table->decimal('amount', 10, 2);
            $table->string('status');
            /** PIX copia-e-cola from Asaas — QR image is built on the client. */
            $table->text('pix_payload')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('payment_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('payment')->cascadeOnDelete();
            $table->string('provider');
            $table->string('provider_event_id');
            $table->string('event');
            $table->json('payload');
            $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_events');
        Schema::dropIfExists('payment');
    }
};
