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
        Schema::create('report_snapshots', function (Blueprint $table) {
            $table->id()->comment('Primary key of the snapshot');

            $table->foreignId('report_id')
                ->constrained()
                ->cascadeOnDelete()
                ->comment('Reference to the parent report');

            $table->enum('scope', ['basic', 'comprehensive', 'both'])
                ->default('basic')
                ->comment('Snapshot scope: Basic only, Comprehensive only, or both');

            $table->string('questionnaire_code', 64)
                ->comment('Code of the questionnaire for this snapshot (e.g., vsme)');

            $table->string('questionnaire_version', 16)
                ->comment('Version of the questionnaire for this snapshot (e.g., v1)');

            $table->string('locale', 8)
                ->default('en')
                ->comment('Locale used when resolving labels/titles');

            $table->unsignedSmallInteger('format_version')
                ->default(1)
                ->comment('Payload format version for schema evolution');

            $table->unsignedInteger('sequence')
                ->default(1)
                ->comment('Sequence order for history within (report_id + scope)');

            $table->boolean('is_latest')
                ->default(true)
                ->comment('Indicates if this is the latest snapshot for the given (report_id + scope)');

            $table->json('payload_json')
                ->comment('Denormalized content (company data, blocks, items, translated texts, units, flags, etc.)');

            $table->char('checksum', 64)
                ->comment('SHA-256 checksum for data integrity verification');

            $table->foreignId('generated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('User who generated this snapshot');

            $table->timestamp('generated_at')
                ->nullable()
                ->comment('Exact timestamp when the snapshot was generated');

            $table->timestamps(); // Laravel's created_at and updated_at

            // Indexes
            $table->index(['report_id', 'scope', 'is_latest'], 'ix_snap_report_scope_latest');
            $table->index(['questionnaire_code', 'questionnaire_version'], 'ix_snap_qn_ver');
            $table->index('generated_at', 'ix_snap_generated_at');

            // Unique constraint for historical sequence
            $table->unique(['report_id', 'scope', 'sequence'], 'uq_snap_report_scope_seq');
        });
    }

    /**
     * Reverse the migrations.
     */




    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_snapshots');
    }
};
