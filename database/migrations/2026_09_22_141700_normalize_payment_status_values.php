<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('payment')->where('status', 'PENDING')->update(['status' => 'pending']);
        DB::table('payment')->where('status', 'RECEIVED')->update(['status' => 'received']);
        DB::table('payment')->where('status', 'CANCELED')->update(['status' => 'canceled']);
        DB::table('payment')->where('status', 'CANCELLED')->update(['status' => 'canceled']);
        DB::table('payment')->where('status', 'CONFIRMED')->update(['status' => 'received']);
        DB::table('payment')->where('status', 'DELETED')->update(['status' => 'canceled']);
        DB::table('payment')->where('status', 'REFUNDED')->update(['status' => 'canceled']);
    }

    public function down(): void
    {
        DB::table('payment')->where('status', 'pending')->update(['status' => 'PENDING']);
        DB::table('payment')->where('status', 'received')->update(['status' => 'RECEIVED']);
        DB::table('payment')->where('status', 'canceled')->update(['status' => 'CANCELED']);
    }
};
