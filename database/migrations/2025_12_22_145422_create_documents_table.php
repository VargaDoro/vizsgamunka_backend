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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('doctor_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreignId('patient_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->string('type', 50);
            $table->string('file_path', 255);

            // SQL: created_at DATETIME DEFAULT GETDATE()
            $table->timestamp('created_at')->useCurrent();

            // nincs updated_at az SQL-ben, ezért nem használunk timestamps()-et
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
