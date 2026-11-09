<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom hanya kalau belum ada
        if (!Schema::hasColumn('denominations', 'group')) {
            Schema::table('denominations', function (Blueprint $table) {
                $table->string('group', 20)->default('paket_a')->after('game_id');
            });
        }

        if (!Schema::hasColumn('denominations', 'sort_order')) {
            Schema::table('denominations', function (Blueprint $table) {
                $table->unsignedInteger('sort_order')->default(0)->after('group');
            });
        }
    }

    public function down(): void
    {
        // Drop kolom hanya kalau ada
        if (Schema::hasColumn('denominations', 'sort_order')) {
            Schema::table('denominations', function (Blueprint $table) {
                $table->dropColumn('sort_order');
            });
        }

        if (Schema::hasColumn('denominations', 'group')) {
            Schema::table('denominations', function (Blueprint $table) {
                $table->dropColumn('group');
            });
        }
    }
};
