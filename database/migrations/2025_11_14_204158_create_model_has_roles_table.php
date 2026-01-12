<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('model_has_roles', function (Blueprint $table) {

            // MUST MATCH your existing tables = INT(10) UNSIGNED
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('model_id');
            $table->string('model_type');

            // index for Spatie performance
            $table->index(['model_id', 'model_type'], 'model_has_roles_model_id_model_type_index');

            // foreign keys must match existing tables exactly
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->primary(['role_id', 'model_id', 'model_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('model_has_roles');
    }
};
