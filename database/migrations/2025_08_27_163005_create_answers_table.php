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

        Schema::create('answers', function (Blueprint $table) {
            $table->id(); // PK

            $table->foreignId('report_id')
                ->constrained('reports')
                ->cascadeOnDelete()
                ->comment('FK to reports.id (the active run of a questionnaire for a company)');

            $table->string('question_key', 64)
                ->comment('Stable question key, e.g. "b1.q1" (matches questions.key)'); // lookup by key

            $table->json('value')
                ->nullable()
                ->comment('Answer payload as JSON (works for text, options, multi, numbers, etc.)');

            $table->string('skipped_reason', 32)
                ->nullable()
                ->comment('If skipped, the reason code (e.g., "na", "classified")');

            $table->boolean('not_applicable')
                ->default(false)
                ->comment('True if question is not applicable for this report context');

            $table->boolean('classified_omitted')
                ->default(false)
                ->comment('True if the answer is omitted due to classified/sensitive info');

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('User who last updated this answer (nullable for system)');

            $table->timestamps(); // created_at, updated_at

            // Indexes
            $table->unique(['report_id', 'question_key'], 'uq_answers_report_question'); // current answer per question
            $table->index(['report_id'], 'ix_answers_report');                            // common filter
            $table->index(['question_key'], 'ix_answers_question_key');                   // cross-report lookups / analytics
            $table->index(['not_applicable', 'classified_omitted'], 'ix_answers_flags');  // fast filtering on flags
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
