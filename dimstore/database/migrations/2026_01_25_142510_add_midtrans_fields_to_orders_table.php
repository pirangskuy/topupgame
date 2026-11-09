<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'order_code')) {
                $table->string('order_code', 64)->nullable()->index();
            }
            if (!Schema::hasColumn('orders', 'total_amount')) {
                $table->unsignedInteger('total_amount')->nullable();
            }
            if (!Schema::hasColumn('orders', 'user_id')) {
                $table->string('user_id', 64)->nullable();
            }
            if (!Schema::hasColumn('orders', 'server_id')) {
                $table->string('server_id', 64)->nullable();
            }

            // payment gateway fields
            if (!Schema::hasColumn('orders', 'payment_provider')) {
                $table->string('payment_provider', 30)->nullable()->index(); // midtrans/manual
            }
            if (!Schema::hasColumn('orders', 'payment_channel')) {
                $table->string('payment_channel', 30)->nullable()->index(); // gopay, qris, bca_va, dll
            }

            // midtrans fields
            if (!Schema::hasColumn('orders', 'snap_token')) {
                $table->string('snap_token', 64)->nullable();
            }
            if (!Schema::hasColumn('orders', 'midtrans_transaction_id')) {
                $table->string('midtrans_transaction_id', 64)->nullable()->index();
            }
            if (!Schema::hasColumn('orders', 'midtrans_status')) {
                $table->string('midtrans_status', 30)->nullable()->index(); // pending/settlement/expire/deny/cancel
            }
            if (!Schema::hasColumn('orders', 'midtrans_raw')) {
                $table->json('midtrans_raw')->nullable();
            }

            if (!Schema::hasColumn('orders', 'paid_at')) {
                $table->timestamp('paid_at')->nullable();
            }
            if (!Schema::hasColumn('orders', 'delivered_at')) {
                $table->timestamp('delivered_at')->nullable();
            }
        });

        // Backfill dari kolom lama jika ada (supaya data lama tetap kebaca)
        if (Schema::hasColumn('orders', 'code') && Schema::hasColumn('orders', 'order_code')) {
            DB::table('orders')->whereNull('order_code')->update(['order_code' => DB::raw('`code`')]);
        }
        if (Schema::hasColumn('orders', 'price') && Schema::hasColumn('orders', 'total_amount')) {
            DB::table('orders')->whereNull('total_amount')->update(['total_amount' => DB::raw('`price`')]);
        }
        if (Schema::hasColumn('orders', 'user_id_value') && Schema::hasColumn('orders', 'user_id')) {
            DB::table('orders')->whereNull('user_id')->update(['user_id' => DB::raw('`user_id_value`')]);
        }
        if (Schema::hasColumn('orders', 'server_value') && Schema::hasColumn('orders', 'server_id')) {
            DB::table('orders')->whereNull('server_id')->update(['server_id' => DB::raw('`server_value`')]);
        }

        // Unique index order_code (kalau belum ada)
        try {
            Schema::table('orders', function (Blueprint $table) {
                $table->unique('order_code', 'orders_order_code_unique');
            });
        } catch (\Throwable $e) {
            // ignore kalau sudah ada / DB berbeda
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            foreach ([
                'orders_order_code_unique',
                'orders_order_code_index',
                'orders_payment_provider_index',
                'orders_payment_channel_index',
                'orders_midtrans_transaction_id_index',
                'orders_midtrans_status_index',
            ] as $idx) {
                try { $table->dropIndex($idx); } catch (\Throwable $e) {}
                try { $table->dropUnique($idx); } catch (\Throwable $e) {}
            }

            foreach ([
                'order_code','total_amount','user_id','server_id',
                'payment_provider','payment_channel',
                'snap_token','midtrans_transaction_id','midtrans_status','midtrans_raw',
                'paid_at','delivered_at',
            ] as $col) {
                if (Schema::hasColumn('orders', $col)) $table->dropColumn($col);
            }
        });
    }
};
