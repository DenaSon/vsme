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
        Schema::create('disclosures', function (Blueprint $table) {
            $table->id(); // PK

            $table->foreignId('questionnaire_id')
                ->constrained('questionnaires')
                ->cascadeOnDelete()
                ->comment('FK to questionnaires.id'); // parent standard/version

            $table->foreignId('module_id')
                ->constrained('modules')
                ->cascadeOnDelete()
                ->comment('FK to modules.id'); // basic or comprehensive

            $table->string('code', 16)
                ->comment('Disclosure code, e.g. "b1", "c3"'); // stable code like B1..C9

            // Translatable display fields (JSON)
            $table->json('title')->nullable()
                ->comment('Translatable title (JSON per locale)');
            $table->json('description')->nullable()
                ->comment('Translatable description (JSON per locale)');

            $table->unsignedSmallInteger('order')->default(0)
                ->comment('Display order inside the module'); // sorting

            $table->boolean('is_active')->default(true)
                ->comment('Whether this disclosure is enabled/visible');

            // Applicability default; actual per-company applicability is decided at runtime
            $table->boolean('is_applicable_by_default')->default(true)
                ->comment('If true, disclosure considered applicable unless ruled out');

            $table->json('meta')->nullable()
                ->comment('Extensible attributes / flags for future needs');

            $table->timestamps();
            $table->softDeletes()->comment('Soft delete timestamp');

            // Indexes
            $table->unique(['questionnaire_id', 'code'], 'uq_disclosures_questionnaire_code'); // fast by code per standard
            $table->index(['questionnaire_id', 'module_id'], 'ix_disclosures_questionnaire_module');
            $table->index(['module_id', 'order'], 'ix_disclosures_module_order');
            $table->index('is_active', 'ix_disclosures_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disclosures');
    }
};
