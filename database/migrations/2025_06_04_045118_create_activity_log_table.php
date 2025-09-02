<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{

    protected $connection = 'logs';

    protected function conn(): string
    {

        return config('activitylog.database_connection', $this->connection ?? config('database.default'));
    }

    protected function table(): string
    {

        return config('activitylog.table_name', 'vsme_logs');
    }

    public function up(): void
    {
        $connection = $this->conn();
        $table = $this->table();

        if (Schema::connection($connection)->hasTable($table)) {
            return;
        }

        Schema::connection($connection)->create($table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('log_name')->nullable()->index();
            $table->text('description');
            $table->nullableMorphs('subject'); // subject_type, subject_id (nullable + indexed)
            $table->nullableMorphs('causer');  // causer_type, causer_id (nullable + indexed)
            $table->json('properties')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        $connection = $this->conn();
        $table = $this->table();

        Schema::connection($connection)->dropIfExists($table);
    }
};
