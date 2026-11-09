<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mlbb_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('order_code', 64)->unique();
            $table->string('name', 40)->default('Pembeli');
            $table->unsignedTinyInteger('rating'); // 1-5
            $table->text('comment');
            $table->boolean('is_approved')->default(true);
            $table->timestamps();

            $table->index(['is_approved']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mlbb_reviews');
    }
};
