<?php

namespace Bunthoeuntok\SimplePermission\Tests\Unit;

use Bunthoeuntok\SimplePermission\Models\Role;
use Bunthoeuntok\SimplePermission\Tests\TestCase;
use Bunthoeuntok\SimplePermission\Tests\TestUser;

class UserTest extends TestCase
{
    /** @test */
    public function user_has_role()
    {
        $user = TestUser::factory()->create();
        $role = Role::factory()->create();

        $user->assignRole($role);
        $this->assertEquals($role->role_name, $user->role->role_name);
    }
}
