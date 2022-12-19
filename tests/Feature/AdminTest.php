<?php

namespace Bunthoeuntok\SimplePermission\Tests\Feature;

use Bunthoeuntok\SimplePermission\Facades\SimplePermission;
use Bunthoeuntok\SimplePermission\Models\Action;
use Bunthoeuntok\SimplePermission\Models\Menu;
use Bunthoeuntok\SimplePermission\PermissionMiddleware;
use Bunthoeuntok\SimplePermission\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class AdminTest extends TestCase
{
    use WithFaker;
    protected $middleware;

    public function setUp(): void
    {
        parent::setUp();
        $this->middleware = new PermissionMiddleware();

        $this->actingAs($this->adminUser);
        $module = Menu::factory()->create(['level' => 'module']);
        $menu1 = Menu::factory()->create(['parent_id' => $module->id, 'level' => 'page']);
        $menu2 = Menu::factory()->create(['parent_id' => $module->id, 'level' => 'page']);
        $curentAction = $this->faker->url();
        $menu1->actions()->saveMany([
            new Action(['action_name' => $this->faker->name(), 'route_name' => $curentAction, 'default' => true]),
            new Action(['action_name' => $this->faker->name(), 'route_name' => $this->faker->url()]),
            new Action(['action_name' => $this->faker->name(), 'route_name' => $this->faker->url()]),
            new Action(['action_name' => $this->faker->name(), 'route_name' => $this->faker->url()]),
        ]);
        $menu2->actions()->saveMany([
            new Action(['action_name' => $this->faker->name(), 'route_name' => $this->faker->url(), 'default' => true]),
            new Action(['action_name' => $this->faker->name(), 'route_name' => $this->faker->url()]),
            new Action(['action_name' => $this->faker->name(), 'route_name' => $this->faker->url()]),
            new Action(['action_name' => $this->faker->name(), 'route_name' => $this->faker->url()]),
        ]);
    }

    /** @test */
    public function admin_user_can_access_all_permissions()
    {
        $this->assertCount(8, SimplePermission::getPermissions());
    }
}
