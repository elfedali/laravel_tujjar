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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('email')->unique();
            $table->string('role')->default(\App\Models\User::ROLE_USER);

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone_number', 20)->unique()->nullable();

            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code', 10)->nullable();
            $table->string('country')->nullable();

            $table->string('photo')->nullable();

            $table->boolean('is_enabled')->default(true);

            $table->timestamp('email_verified_at')->nullable();
            $table->string('email_verification_token')->nullable();

            $table->timestamp('phone_number_verified_at')->nullable();
            $table->string('phone_number_verification_token')->nullable();

            // google
            $table->string('google_id')->nullable();
            $table->text('google_token')->nullable();
            $table->string('google_refresh_token')->nullable();



            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
