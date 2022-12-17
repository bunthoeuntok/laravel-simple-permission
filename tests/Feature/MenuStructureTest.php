<?php

namespace Bunthoeuntok\SimplePermission\Tests\Feature;

use Bunthoeuntok\SimplePermission\Exceptions\ActionAlreadyExists;
use Bunthoeuntok\SimplePermission\Tests\TestCase;
use Bunthoeuntok\SimplePermission\Models\Menu;
use Bunthoeuntok\SimplePermission\Models\Action;

class MenuStructureTest extends TestCase
{
    /** @test */
    public function menu_has_actions()
    {
        $menu = Menu::factory()->create();
        $actions = $menu->actions()->saveMany([
            new Action(['action_name' => 'action 1', 'route_name' => 'action-1.index']),
            new Action(['action_name' => 'action 2', 'route_name' => 'action-2.index']),
        ]);

        $this->assertCount(2, $actions);
    }

    /** @test */
    public function action_cannot_be_duplicated()
    {
        $this->expectException(ActionAlreadyExists::class);

        $menu = Menu::factory()->create();
        $menu->actions()->saveMany([
            new Action(['action_name' => 'action 1', 'route_name' => 'action-1.index']),
            new Action(['action_name' => 'action 2', 'route_name' => 'action-1.index']),
        ]);
    }
}
