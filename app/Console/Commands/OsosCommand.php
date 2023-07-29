<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

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
        Artisan::call('passport:install');
        $this->info('Passport Installed');
        $this->insertDefaultRolesAndPermissions();
        $this->info('Roles and permissions created');
        $this->createAdmin();
        $this->info('Admin Created');
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

        $user->createToken('api-auth-token')->accessToken;
        $user->syncRoles(['Admin']);

    }

    protected function insertDefaultRolesAndPermissions()
    {
        $roles = config('constants.roles');
        $permissions = config('constants.permissions');

        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        foreach ($roles as  $role) {
            Role::firstOrCreate([
                'name'   => $role['name'],
            ], $role);
        }

        foreach ($permissions as  $permission) {
            Permission::firstOrCreate([
                'name'   => $permission['name'],
            ], $permission);
         }
    }
}
