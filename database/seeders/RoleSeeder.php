<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Roles Create Karein
        $superAdminRole = Role::updateOrCreate(['name' => 'super_admin']);
        $editorRole = Role::updateOrCreate(['name' => 'editor']);

        // 2. User Create Karein (Umair)
        $user = User::updateOrCreate(
            ['email' => 'uwebglobalsol@gmail.com'], // Email check karega agar pehle se hai toh update karega
            [
                'name' => 'Umair',
                'password' => Hash::make('admin123'), // Aap apna password yahan rakh sakte hain
            ]
        );

        // 3. User ko Super Admin Role assign karein
        $user->assignRole($superAdminRole);

        $this->command->info('User Umair has been created and assigned Super Admin role!');
    }
}
