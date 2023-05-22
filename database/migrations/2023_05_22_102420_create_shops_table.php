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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users');
            $table->foreignId('category_id')->nullable()->constrained('categories');

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('phone_number', 20)->nullable();

            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code', 10)->nullable();
            $table->string('country')->nullable();

            $table->string('logo_photo')->nullable();
            $table->string('cover_photo')->nullable();

            $table->boolean('is_enabled')->default(true);
            $table->boolean('is_verified')->default(false);



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
