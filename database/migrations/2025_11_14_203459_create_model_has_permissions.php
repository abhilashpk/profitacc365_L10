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
Schema::create('model_has_permissions', function (Blueprint $table) {
    $table->unsignedInteger('permission_id');  // MUST MATCH old permissions table
    $table->unsignedInteger('model_id');       // because users.id is INT too
    $table->string('model_type');

    $table->index(['model_id', 'model_type'], 'model_has_permissions_model_id_model_type_index');

    $table->foreign('permission_id')
        ->references('id')
        ->on('permissions')
        ->onDelete('cascade');
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_has_permissions');
    }
};
