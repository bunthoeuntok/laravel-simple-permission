<?php

namespace Bunthoeuntok\SimplePermission;

use Bunthoeuntok\SimplePermission\Commands\SimplePermissionCommand;
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
}
