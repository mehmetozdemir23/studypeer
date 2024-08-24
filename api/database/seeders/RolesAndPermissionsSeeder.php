<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $admin = Role::create(['id' => Str::uuid(), 'name' => 'admin']);
        $tutor = Role::create(['id' => Str::uuid(), 'name' => 'tutor']);
        $student = Role::create(['id' => Str::uuid(), 'name' => 'student']);

        // Create permissions
        $permissions = [
            'create users',
            'read users',
            'update users',
            'delete users',
            'create groups',
            'read groups',
            'update groups',
            'delete groups',
            'create sessions',
            'read sessions',
            'update sessions',
            'delete sessions',
            'create shared files',
            'read shared files',
            'update shared files',
            'delete shared files',
            'send messages',
            'view notifications',
            'submit support tickets',
        ];

        $permissionRows = array_map(fn ($permission) => ['id' => Str::uuid(), 'name' => $permission], $permissions);
        Permission::insert($permissionRows);

        // Assign permissions to roles
        $admin->permissions()->attach(Permission::whereIn('name', [
            'create users',
            'read users',
            'update users',
            'delete users',
            'create groups',
            'read groups',
            'update groups',
            'delete groups',
            'create sessions',
            'read sessions',
            'update sessions',
            'delete sessions',
            'create shared files',
            'read shared files',
            'update shared files',
            'delete shared files',
            'send messages',
            'view notifications',
            'submit support tickets',
        ])->pluck('id'));

        $tutor->permissions()->attach(Permission::whereIn('name', [
            'create groups',
            'read groups',
            'update groups',
            'delete groups',
            'create sessions',
            'read sessions',
            'update sessions',
            'delete sessions',
            'create shared files',
            'read shared files',
            'update shared files',
            'delete shared files',
            'send messages',
            'view notifications',
            'submit support tickets',
        ])->pluck('id'));

        $student->permissions()->attach(Permission::whereIn('name', [
            'read groups',
            'read sessions',
            'read shared files',
            'send messages',
            'view notifications',
            'submit support tickets',
        ])->pluck('id'));
    }
}
