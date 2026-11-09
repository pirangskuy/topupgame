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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique(); // kode invoice

            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->foreignId('denomination_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_method_id')->constrained()->cascadeOnDelete();

            $table->string('user_id_value');              // ID game (User ID)
            $table->string('server_value')->nullable();   // Server/Zone (opsional)
            $table->string('whatsapp');                   // nomor WA pembeli
            $table->unsignedInteger('price');             // snapshot harga saat order dibuat

            $table->string('status')->default('pending'); // pending|paid|processing|success|failed
            $table->text('note')->nullable();             // catatan admin / user (optional)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
