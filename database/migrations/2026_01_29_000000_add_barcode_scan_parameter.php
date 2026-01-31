<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!DB::table('parameter2')->where('keyname', 'mod_barcode_scan')->exists()) {
            DB::table('parameter2')->insert([
                'name' => 'Barcode scanner toggle',
                'is_active' => 1,
                'status' => 1,
                'keyname' => 'mod_barcode_scan',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('parameter2')->where('keyname', 'mod_barcode_scan')->delete();
    }
};
