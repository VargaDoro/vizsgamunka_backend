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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('doctor_id')
                ->references('user_id')
                ->on('doctors')
                ->onDelete('cascade');

            $table->foreignId('patient_id')
                ->references('user_id')
                ->on('patients')
                ->onDelete('cascade');

            $table->string('medicine_name');
            $table->string('dosage');
            $table->date('issued_at');
            $table->date('valid_until');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
