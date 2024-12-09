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
            $table->string('id')->primary();
            $table->string('first_name', 25);
            $table->string('last_name', 25);
            $table->unsignedBigInteger('balance')->default(0);
            
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();

            $table->boolean('referral')->default(false);

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate();
        });

        Schema::create('invites', function (Blueprint $table) {
            $table->string('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->string('invited');
        });

        Schema::create('otps', function (Blueprint $table) {
            // TODO: delete after a time
            $table->string('id')->unique();
            $table->string('code', 6);
            $table->ipAddress('ip_address');
            $table->timestamp('created_at')->useCurrent();
            $table->string('expire_at');
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('otp_code');
        Schema::dropIfExists('sessions');
    }
};
