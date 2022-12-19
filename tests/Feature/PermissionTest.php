<?php

namespace Bunthoeuntok\SimplePermission\Tests\Feature;

use Bunthoeuntok\SimplePermission\Models\Action;
use Bunthoeuntok\SimplePermission\Models\Menu;
use Bunthoeuntok\SimplePermission\Models\Role;
use Bunthoeuntok\SimplePermission\Tests\TestCase;

class PermissionTest extends TestCase
{
    /** @test */
    public function role_can_be_assigned_permissions()
    {
        $role = Role::factory()->create();
        $menu = Menu::factory()->create();
        $actions = $menu->actions()->saveMany([
            new Action(['action_name' => 'action 1', 'route_name' => 'action-1.index']),
            new Action(['action_name' => 'action 2', 'route_name' => 'action-2.index']),
        ]);
        $role->givePermissions($actions);

        $this->assertCount(2, $role->permissions);
    }
}
