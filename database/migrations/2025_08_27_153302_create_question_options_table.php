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

        Schema::create('question_options', function (Blueprint $table) {
            $table->id(); // PK

            $table->foreignId('question_id')
                ->constrained('questions')
                ->cascadeOnDelete()
                ->comment('FK to questions.id'); // parent question

            $table->string('kind', 24)->default('option')
                ->comment('Semantic kind: option | row | field | column'); // flexible usage

            $table->string('key', 64)->nullable()
                ->comment('Stable machine key for this option/row/field'); // used for mapping/visibility
            $table->string('value', 128)->nullable()
                ->comment('Value to be saved for options; nullable for rows/fields'); // e.g., "basic","both"

            // Translatable UI texts
            $table->json('label')->nullable()
                ->comment('Translatable label (JSON per locale)'); // UI label
            $table->json('description')->nullable()
                ->comment('Translatable description/help (JSON per locale)'); // optional

            // Extra schema (units, placeholders, constraints for fields, etc.)
            $table->json('extra')->nullable()
                ->comment('Arbitrary schema/flags (units, min/max, placeholder, mask, etc.)');

            $table->unsignedSmallInteger('sort')->default(0)
                ->comment('Sort order within the question'); // display order

            $table->boolean('is_active')->default(true)
                ->comment('Whether this option/row/field is enabled/visible');

            $table->timestamps();
            $table->softDeletes()->comment('Soft delete timestamp');

            // Indexes (performance)
            $table->index(['question_id', 'sort'], 'ix_qopt_question_sort');     // ordered fetch per question
            $table->index(['question_id', 'kind'], 'ix_qopt_question_kind');     // filter by kind
            $table->index('is_active', 'ix_qopt_active');                        // common filter
            $table->unique(['question_id', 'key'], 'uq_qopt_question_key');      // stable key per question (allows multiple NULLs)
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_options');
    }
};
