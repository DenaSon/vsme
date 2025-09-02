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

        Schema::create('questions', function (Blueprint $table) {
            $table->id(); // PK

            $table->foreignId('disclosure_id')
                ->constrained('disclosures')
                ->cascadeOnDelete()
                ->comment('FK to disclosures.id'); // parent disclosure (e.g., B1)

            $table->string('key', 64)
                ->comment('Stable question key, e.g. "b1.q1"'); // used in code/answers
            $table->unsignedSmallInteger('number')->default(0)
                ->comment('Sequential display number within the disclosure'); // e.g., 1,2,3

            $table->string('type', 48)
                ->comment('Renderer type, e.g. radio-cards, textarea, file, repeatable, yesno-grid');

            // Translatable fields
            $table->json('title')->nullable()
                ->comment('Translatable question title (JSON per locale)');
            $table->json('help_official')->nullable()
                ->comment('Translatable official help text (JSON per locale)');
            $table->json('help_friendly')->nullable()
                ->comment('Translatable friendly help text (JSON per locale)');

            // Schema-driven behavior
            $table->json('rules')->nullable()
                ->comment('Validation rules schema (JSON), e.g. {"required":true,"in":["basic","both"]}');
            $table->json('visibility')->nullable()
                ->comment('Visibility/branching rules (JSON), e.g. {"visible_if":[...]}');

            $table->unsignedSmallInteger('order')->default(0)
                ->comment('Display order inside the disclosure'); // sorting within disclosure
            $table->boolean('is_active')->default(true)
                ->comment('Whether this question is enabled/visible');

            $table->json('meta')->nullable()
                ->comment('Extensible attributes (units, placeholders, UI flags, etc.)');

            $table->timestamps();
            $table->softDeletes()->comment('Soft delete timestamp');

            // Indexes
            $table->unique(['disclosure_id', 'key'], 'uq_questions_disclosure_key'); // fast exact lookup
            $table->index(['disclosure_id', 'number'], 'ix_questions_disclosure_number'); // for display
            $table->index(['disclosure_id', 'order'], 'ix_questions_disclosure_order'); // ordered fetch
            $table->index('type', 'ix_questions_type'); // renderer-specific queries
            $table->index('is_active', 'ix_questions_active'); // common filter
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
