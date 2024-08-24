<?php

namespace Tests;

use App\Models\Role;
use App\Models\User;

class PermissionTestCase extends TestCase
{
    protected Role $adminRole;

    protected Role $tutorRole;

    protected Role $studentRole;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        $this->adminRole = Role::create(['name' => Role::ADMIN]);
        $this->tutorRole = Role::create(['name' => Role::TUTOR]);
        $this->studentRole = Role::create(['name' => Role::STUDENT]);

        // Create users and assign roles
        $this->admin = $this->createUserWithRole($this->adminRole);
        $this->tutor = $this->createUserWithRole($this->tutorRole);
        $this->student = $this->createUserWithRole($this->studentRole);

    }

    protected function attachPermissions(Role $role, array $permissions)
    {
        $role->permissions()->attach(array_map(fn ($permission) => $permission->id, $permissions));
    }

    private function createUserWithRole(Role $role): User
    {
        $user = User::factory()->create();
        $user->roles()->attach($role);

        return $user;
    }
}
