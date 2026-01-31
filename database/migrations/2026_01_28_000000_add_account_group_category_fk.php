<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('account_group', function (Blueprint $table) {
            $table->foreign('category_id', 'account_group_category_id_fk')
                ->references('id')
                ->on('account_category')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('account_group', function (Blueprint $table) {
            $table->dropForeign('account_group_category_id_fk');
        });
    }
};
