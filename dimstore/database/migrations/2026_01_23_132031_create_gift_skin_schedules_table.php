<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gift_skin_schedules', function (Blueprint $table) {
            $table->id();

            // data penerima
            $table->string('nickname', 100);
            $table->string('user_id', 50);

            // tanggal
            $table->dateTime('order_date');       // kapan diinput / order dibuat (jadwal dicatat)
            $table->date('gift_date')->nullable(); // kapan gift dilakukan

            // status
            $table->string('status', 20)->default('progress'); // progress|done

            // opsional: catatan/admin input
            $table->string('admin_name', 100)->nullable(); // nama admin yg input
            $table->string('note', 255)->nullable();

            $table->timestamps();

            $table->index(['status', 'gift_date']);
            $table->index(['user_id', 'nickname']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gift_skin_schedules');
    }
};
