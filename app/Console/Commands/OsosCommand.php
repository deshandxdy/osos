<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;

class OsosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'osos:start {--fresh}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate and seed default data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $migrate_fresh = $this->option('fresh');
        if ($migrate_fresh)
        {
            if ($this->confirm('Do you wish to continue?')) {
                Artisan::call('migrate:fresh');
                $this->defaultSetUp();

            } else {
                $this->line('Command aborted by user');
            }
        }
         else {
            $this->defaultSetUp();
         }
        return Command::SUCCESS;
    }

    protected function defaultSetUp()
    {
        $this->createAdmin();
        $this->insertDefaultRolesAndPermissions();
        $this->info('Default data setted successfully');
    }

    protected function createAdmin()
    {
        $user = User::firstOrCreate([
            'email' => env("ADMIN_EMAIL", 'admin@admin.tech'),
        ], [
            'email' => env("ADMIN_EMAIL", "admin@admin.tech"),
            'first_name' => "OSOS",
            'last_name' => "Admin",
            'email_verified_at' => now(),
            'password' => bcrypt(env("ADMIN_PASSWORD", "admin1234")), // password
        ]);

    }

    protected function insertDefaultRolesAndPermissions()
    {
        $roles = config('constants.roles');
        $permissions = config('constants.permissions');

        foreach ($roles as  $role) {
           Role::create($role);
        }

        foreach ($permissions as  $permission) {
            Permission::create($permission);
         }
    }
}
