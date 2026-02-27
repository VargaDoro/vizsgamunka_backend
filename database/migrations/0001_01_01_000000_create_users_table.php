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
            //$table->unsignedBigInteger('user_id')->primary();     v ez legyen?
            $table->string('name', 100);
            $table->string('social_security_number', 20)->unique(); //orvosnak is kell legyen!!!(a verifynumberén kívül!!!)
            $table->date('birth_date');//orvosnak is kell legyen!!!
            $table->string('country', 100)->nullable();//orvosnak is kell legyen!!!
            $table->string('city', 100)->nullable();//orvosnak is kell legyen!!!
            $table->string('postal_code', 20)->nullable();//orvosnak is kell legyen!!!
            $table->string('street_address', 200)->nullable();//orvosnak is kell legyen!!!
            $table->string('phone_number', 20)->nullable();//orvosnak is kell legyen!!!
            $table->string('license_number', 50)->unique(); //CSAK ORVOSS
            $table->string('specialization', 100);  //CSAK ORVOSS
            $table->unsignedBigInteger('office_location_id')->nullable(); //CSAK ORVOSS
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'patient', 'doctor'])->default('patient');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
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
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
