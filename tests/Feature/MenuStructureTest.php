<?php

namespace Bunthoeuntok\SimplePermission\Tests\Feature;

use Bunthoeuntok\SimplePermission\Exceptions\ActionAlreadyExists;
use Bunthoeuntok\SimplePermission\Exceptions\MenuLevelNotMatch;
use Bunthoeuntok\SimplePermission\Models\Action;
use Bunthoeuntok\SimplePermission\Models\Menu;
use Bunthoeuntok\SimplePermission\Tests\TestCase;
use Illuminate\Support\Collection;

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
        $menu = Menu::factory()->create();

        $this->expectException(ActionAlreadyExists::class);
        $menu->actions()->saveMany([
            new Action(['action_name' => 'action 1', 'route_name' => 'action-1.index']),
            new Action(['action_name' => 'action 2', 'route_name' => 'action-1.index']),
        ]);
    }

    /** @test */
    public function menus_can_generate_to_be_menu_tree()
    {
        Menu::factory()->create(['parent_id' => null]);
        Menu::factory()->create(['parent_id' => random_int(1, 5)]);
        Menu::factory()->create(['parent_id' => random_int(1, 5)]);
        Menu::factory()->create(['parent_id' => random_int(1, 5)]);
        Menu::factory()->create(['parent_id' => random_int(1, 5)]);
        Menu::factory()->create(['parent_id' => random_int(1, 5)]);
        Menu::factory()->create(['parent_id' => random_int(1, 5)]);
        Menu::factory()->create(['parent_id' => random_int(1, 5)]);

        $tree = Menu::tree()->get()->toTree();
        $this->assertInstanceOf(Collection::class, $tree);
    }

    /** @test */
    public function menu_level_can_be_one_which_is_setted_in_the_config()
    {
        $this->expectException(MenuLevelNotMatch::class);
        Menu::factory()->create(['level' => 'testing']);
    }
}
