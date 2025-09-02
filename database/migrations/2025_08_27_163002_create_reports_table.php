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

        Schema::create('reports', function (Blueprint $table) {
            $table->id(); // PK

            $table->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnDelete()
                ->comment('FK to companies.id (owner of the report)'); // شرکت صاحب گزارش

            $table->foreignId('questionnaire_id')
                ->constrained('questionnaires')
                ->cascadeOnDelete()
                ->comment('FK to questionnaires.id (e.g., VSME v1)'); // نسخه پرسشنامه

            $table->unsignedSmallInteger('year')->nullable()
                ->comment('Reporting year (nullable if using custom period)'); // سال گزارش

            $table->date('period_start')->nullable()
                ->comment('Custom period start date'); // شروع بازه دلخواه
            $table->date('period_end')->nullable()
                ->comment('Custom period end date'); // پایان بازه دلخواه

            $table->enum('mode', ['individual','consolidated'])->default('individual')
                ->comment('Report mode: individual (single entity) or consolidated (group)'); // حالت گزارش

            $table->enum('module_choice', ['A','B'])->default('A')
                ->comment('Module selection: A=Basic only, B=Basic+Comprehensive'); // انتخاب ماژول

            $table->enum('status', ['draft','submitted','locked'])->default('draft')
                ->comment('Workflow status of this report'); // وضعیت گردش‌کار

            $table->string('current_key', 64)->nullable()
                ->comment('Last visited question key (eg. "b1.q35") for resuming'); // محل ادامه

            $table->unsignedTinyInteger('progress')->default(0)
                ->comment('Cached progress percentage [0..100] for quick UI'); // درصد پیشرفت کش‌شده

            $table->timestamp('submitted_at')->nullable()
                ->comment('Timestamp when the report was submitted'); // زمان ارسال

//            $table->foreignId('snapshot_id')->nullable()
//                ->constrained('snapshots')
//                ->nullOnDelete()
//                ->comment('FK to final immutable snapshot (if created)'); // اسنپ‌شات نهایی

            $table->foreignId('created_by')->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('User who created the report'); // سازنده
            $table->foreignId('updated_by')->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('User who last updated the report'); // آخرین ویرایشگر

            $table->json('meta')->nullable()
                ->comment('Extensible metadata (flags, notes, links)'); // توسعه‌پذیر

            $table->timestamps();
            $table->softDeletes()->comment('Soft delete timestamp');

            // Indexes & Constraints
            $table->unique(['company_id','questionnaire_id','year'], 'uq_reports_company_questionnaire_year'); // یکتایی سالانه
            $table->index(['company_id','status'], 'ix_reports_company_status'); // فیلتر رایج در پروفایل شرکت
            $table->index(['questionnaire_id','module_choice'], 'ix_reports_questionnaire_module'); // تحلیل/فیلتر
            $table->index('current_key', 'ix_reports_current_key'); // ازسرگیری سریع
            $table->index('progress', 'ix_reports_progress'); // داشبورد/مرتب‌سازی
            $table->index('submitted_at', 'ix_reports_submitted_at'); // گزارش‌های ارسال‌شده
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
