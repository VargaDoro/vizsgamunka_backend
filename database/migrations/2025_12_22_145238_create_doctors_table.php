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
    Schema::create('doctors', function (Blueprint $table) {
        $table->unsignedBigInteger('user_id')->primary();
        $table->string('name', 100);
        $table->string('license_number', 50)->unique();
        $table->string('specialization', 100);
        $table->string('phone_number', 20)->nullable();
        $table->unsignedBigInteger('office_location_id')->nullable();
        $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->cascadeOnDelete();
        $table->foreign('office_location_id')
            ->references('id')
            ->on('office_locations');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
