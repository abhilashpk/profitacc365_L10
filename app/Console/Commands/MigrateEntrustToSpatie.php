<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateEntrustToSpatie extends Command
{
    protected $signature = 'migrate:entrust';
    protected $description = 'Convert Entrust roles & permissions to Spatie format';

    public function handle()
    {
        $this->info("Migrating role_has_permissions...");
        
        // Check if old permission_role table exists
        if (DB::connection()->getSchemaBuilder()->hasTable('permission_role')) {
            foreach (DB::table('permission_role')->get() as $pr) {
                DB::table('role_has_permissions')->updateOrInsert([
                    'permission_id' => $pr->permission_id,
                    'role_id'       => $pr->role_id,
                ]);
            }
            $this->info("Permissions migrated successfully!");
        } else {
            $this->info("permission_role table not found - skipping permissions migration");
        }

        $this->info("Migrating model_has_roles...");
        
        // Check if old role_user table exists
        if (DB::connection()->getSchemaBuilder()->hasTable('role_user')) {
            foreach (DB::table('role_user')->get() as $ru) {
                DB::table('model_has_roles')->updateOrInsert([
                    'role_id'     => $ru->role_id,
                    'model_id'    => $ru->user_id,
                    'model_type'  => 'App\Models\User'
                ]);
            }
            $this->info("Roles migrated successfully!");
        } else {
            $this->info("role_user table not found - skipping roles migration");
        }

        $this->info("Success: Entrust -> Spatie migration completed!");
    }
}
