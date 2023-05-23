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
        Schema::create('images', function (Blueprint $table) {
            $table->id();

            $table->string('path');
            $table->boolean('is_main')->default(false);
            $table->integer('position')->default(0);
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');

            $table->string('small')->nullable();
            $table->string('medium')->nullable();
            $table->string('large')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
