<?php

namespace Bunthoeuntok\SimplePermission\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SimplePermissionCommand extends Command
{
    public $signature = 'permission:install';

    public $description = 'Install requirement for laravel-simple-permission.';

    public function handle()
    {
        $this->info('Installing laravel-simple-permission...');

        // Publish configuration
        $this->info('Publishing configuration...');
        if (! $this->configExists('simple-permission.php')) {
            $this->publishConfiguration();
            $this->info('Published configuration');
        } else {
            $this->info('Skip, it published already.');

            return;
        }

        // Publishing migrations
        $this->info('Publishing migrations...');
        $this->publishMigrations();
        $this->info('Publishing middleware...');
        $this->publishMiddleware();
        $this->info('Installed laravel-simple-permission');
    }

    private function configExists($fileName)
    {
        return File::exists(config_path($fileName));
    }

    private function publishConfiguration()
    {
        $params = [
            '--provider' => "Bunthoeuntok\SimplePermission\SimplePermissionServiceProvider",
            '--tag' => 'config',
        ];

        $this->call('vendor:publish', $params);
    }

    private function publishMigrations()
    {
        $params = [
            '--provider' => "Bunthoeuntok\SimplePermission\SimplePermissionServiceProvider",
            '--tag' => 'migration',
        ];

        $this->call('vendor:publish', $params);
    }

    private function publishMiddleware()
    {
        $params = [
            '--provider' => "Bunthoeuntok\SimplePermission\SimplePermissionServiceProvider",
            '--tag' => 'middleware',
        ];

        $this->call('vendor:publish', $params);
    }
}
