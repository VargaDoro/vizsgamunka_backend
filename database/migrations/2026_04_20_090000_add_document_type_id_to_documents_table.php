<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            if (!Schema::hasColumn('documents', 'document_type_id')) {
                $table->foreignId('document_type_id')
                    ->nullable()
                    ->after('patient_id')
                    ->constrained('document_types')
                    ->nullOnDelete();
            }
        });

        if (Schema::hasColumn('documents', 'type')) {
            // Backfill the new FK from existing string-based type values.
            DB::table('documents')
                ->join('document_types', 'documents.type', '=', 'document_types.type')
                ->whereNull('documents.document_type_id')
                ->update(['documents.document_type_id' => DB::raw('document_types.id')]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            if (Schema::hasColumn('documents', 'document_type_id')) {
                $table->dropConstrainedForeignId('document_type_id');
            }
        });
    }
};
