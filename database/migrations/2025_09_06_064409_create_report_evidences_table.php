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

            Schema::create('report_evidences', function (Blueprint $t) {
                $t->id();
                $t->unsignedBigInteger('report_id')->index();
                $t->string('question_key', 30)->index();
                $t->string('path');               // storage path (disk: public)
                $t->string('original_name');
                $t->string('mime', 120)->nullable();
                $t->unsignedBigInteger('size')->default(0);
                $t->timestamps();



            });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_evidences');
    }
};
