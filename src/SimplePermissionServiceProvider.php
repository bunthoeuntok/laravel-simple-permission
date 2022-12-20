<?php

namespace Bunthoeuntok\SimplePermission;

use Bunthoeuntok\SimplePermission\Commands\SimplePermissionCommand;
use Illuminate\Support\Carbon;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SimplePermissionServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-simple-permission')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-simple-permission_table')
            ->hasCommand(SimplePermissionCommand::class);
    }

    public function boot()
    {
        $this->publishing();
    }

    protected function publishing()
    {
        $this->publishes([
            __DIR__.'/../config/simple-permission.php' => config_path('simple-permission.php'),
        ], 'permission-config');

        $this->publishes([
            __DIR__.'/../database/migrations/add_role_id_to_users_table.php.stub' => $this->generateMigrationName('add_role_id_to_users_table.php', Carbon::now(2)),
            __DIR__.'/../database/migrations/create_roles_table.php.stub' => $this->generateMigrationName('create_roles_table.php', Carbon::now()->subSecond(2)),
            __DIR__.'/../database/migrations/create_menus_table.php.stub' => $this->generateMigrationName('create_menus_table.php', Carbon::now()->subSecond(2)),
            __DIR__.'/../database/migrations/create_actions_table.php.stub' => $this->generateMigrationName('create_actions_table.php', Carbon::now()->subSecond(1)),
            __DIR__.'/../database/migrations/create_role_has_permission_table.php.stub' => $this->generateMigrationName('create_role_has_permission_table.php', Carbon::now()),
        ], 'permission-migration');
    }
}
