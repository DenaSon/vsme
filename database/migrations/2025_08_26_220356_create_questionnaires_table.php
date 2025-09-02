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
        Schema::create('questionnaires', function (Blueprint $table) {
            $table->id(); // PK

            $table->string('code', 64)
                ->comment('Stable identifier of the standard, e.g. "vsme"'); // e.g., vsme
            $table->string('version', 16)
                ->comment('Version tag, e.g. "v1" or "1.0.0"'); // semantic or simple tag

//            $table->string('title_key', 191)->nullable()
//                ->comment('i18n key for display title'); // for UI localization
//            $table->string('description_key', 191)->nullable()
//                ->comment('i18n key for display description'); // optional longer text

            $table->string('locale_default', 10)->default('en')
                ->comment('Default locale code for this questionnaire'); // e.g., en, fi

            $table->boolean('is_active')->default(true)
                ->comment('Whether the questionnaire is selectable/visible in UI'); // soft toggle

            $table->timestamp('published_at')->nullable()
                ->comment('When the questionnaire becomes publicly available'); // scheduling
            $table->timestamp('archived_at')->nullable()
                ->comment('When the questionnaire is archived/retired'); // deprecation marker

            $table->json('meta')->nullable()
                ->comment('Extensible properties (flags, notes, links)'); // future-proof

            $table->timestamps(); // created_at, updated_at
            $table->softDeletes()->comment('Soft delete timestamp'); // safety net

            // Indexes
            $table->unique(['code', 'version'], 'uq_questionnaires_code_version'); // fast lookup by standard+version
            $table->index('code', 'ix_questionnaires_code'); // filter by family
            $table->index('is_active', 'ix_questionnaires_active'); // common filter
            $table->index('published_at', 'ix_questionnaires_published_at'); // scheduling queries
            $table->index('archived_at', 'ix_questionnaires_archived_at'); // archival filtering
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaires');
    }
};
