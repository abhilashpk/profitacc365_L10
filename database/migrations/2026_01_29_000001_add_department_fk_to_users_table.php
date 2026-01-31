<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("UPDATE users SET department_id = NULL WHERE department_id = 0");
        DB::statement("UPDATE users SET department_id = NULL WHERE department_id IS NOT NULL AND department_id NOT IN (SELECT id FROM department)");
        DB::statement("ALTER TABLE users MODIFY department_id INT NULL");

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('department_id', 'users_department_id_foreign')
                ->references('id')
                ->on('department')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_department_id_foreign');
        });

        DB::statement("UPDATE users SET department_id = 0 WHERE department_id IS NULL");
        DB::statement("ALTER TABLE users MODIFY department_id INT NOT NULL");
    }
};
