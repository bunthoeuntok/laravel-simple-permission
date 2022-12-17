<?php

namespace Bunthoeuntok\SimplePermission\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Bunthoeuntok\SimplePermission\SimplePermissionServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Bunthoeuntok\\SimplePermission\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            SimplePermissionServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $menu = include __DIR__.'/../database/migrations/create_menus_table.php.stub';
        $action = include __DIR__.'/../database/migrations/create_actions_table.php.stub';
        $menu->up();
        $action->up();
    }
}
