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
            $table->string('phone')->primary();
            $table->string('first_name', 25);
            $table->string('last_name', 25);
            
            // reason: only one device allowed
            $table->string('mac_address');

            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate();
        });

        Schema::create('otp_code', function (Blueprint $table) {
            $table->string('phone')->unique();
            $table->string('code', 6);

            // delete after certain time
            $table->timestamp('created_at')->useCurrent();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('otp_code');
    }
};
