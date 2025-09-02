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
        Schema::create('modules', function (Blueprint $table) {
            $table->id(); // PK

            $table->foreignId('questionnaire_id')
                ->constrained('questionnaires')
                ->cascadeOnDelete()
                ->comment('FK to questionnaires.id'); // parent questionnaire

            // You can switch to string+check if you prefer; enum is straightforward here.
            $table->enum('code', ['basic', 'comprehensive'])
                ->comment('Module code: "basic" or "comprehensive"'); // stable identifier

//            $table->string('title_key', 191)->nullable()
//                ->comment('i18n key for module title'); // for UI
//            $table->string('description_key', 191)->nullable()
//                ->comment('i18n key for module description'); // optional

            $table->unsignedSmallInteger('order')->default(0)
                ->comment('Display order inside the questionnaire'); // sorting

            $table->boolean('is_active')->default(true)
                ->comment('Whether the module is enabled/visible'); // toggle

            $table->json('meta')->nullable()
                ->comment('Extensible properties for future needs'); // e.g., flags

            $table->timestamps();
            $table->softDeletes()->comment('Soft delete timestamp');

            // Indexes
            $table->unique(['questionnaire_id', 'code'], 'uq_modules_questionnaire_code'); // unique per questionnaire
            $table->index(['questionnaire_id', 'order'], 'ix_modules_questionnaire_order'); // ordered fetch
            $table->index('is_active', 'ix_modules_active'); // common filter
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
