<?php

namespace Bunthoeuntok\SimplePermission\Tests;

use Bunthoeuntok\SimplePermission\Models\Role;
use Bunthoeuntok\SimplePermission\SimplePermissionServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected $role;
    protected $adminRole;
    protected $user;
    protected $adminUser;

    public function setUp(): void
    {
        parent::setUp();
        $this->setupDatabase();
        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Bunthoeuntok\\SimplePermission\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        $this->prepareTestingData();
    }

    protected function getPackageProviders($app)
    {
        return [
            SimplePermissionServiceProvider::class,
        ];
    }

    public function setupDatabase()
    {
        $this->loadLaravelMigrations();
        $user = include __DIR__.'/../database/migrations/add_role_id_to_users_table.php.stub';
        $menu = include __DIR__.'/../database/migrations/create_menus_table.php.stub';
        $action = include __DIR__.'/../database/migrations/create_actions_table.php.stub';
        $role = include __DIR__.'/../database/migrations/create_roles_table.php.stub';
        $rolePermssion = include __DIR__.'/../database/migrations/create_role_has_permission_table.php.stub';
        $user->up();
        $menu->up();
        $action->up();
        $role->up();
        $rolePermssion->up();
    }

    protected function prepareTestingData()
    {
        $this->role = Role::factory()->create(['is_admin' => false]);
        $this->adminRole = Role::factory()->create(['is_admin' => true]);
        $this->user = TestUser::factory()->create();
        $this->adminUser = TestUser::factory()->create();

        $this->user->assignRole($this->role);
        $this->adminUser->assignRole($this->adminRole);
    }
}
