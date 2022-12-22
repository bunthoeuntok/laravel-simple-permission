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

        $this->info('Publishing configuration...');

        if (!$this->configExists('simple-permission.php')) {
            $this->publishConfiguration();
            $this->info('Published configuration');
        } else {
            if ($this->shouldOverwriteConfig()) {
                $this->info('Overwriting configuration file...');
                $this->publishConfiguration($force = true);
            } else {
                $this->info('Existing configuration was not overwritten');
            }
        }

        $this->info('Installed laravel-simple-permission');
    }

    private function configExists($fileName)
    {
        return File::exists(config_path($fileName));
    }

    private function shouldOverwriteConfig()
    {
        return $this->confirm(
            'Config file already exists. Do you want to overwrite it?',
            false
        );
    }

    private function publishConfiguration($forcePublish = false)
    {
        $params = [
            '--provider' => "Bunthoeuntok\SimplePermission\SimplePermissionServiceProvider",
            '--tag' => "config"
        ];

        if ($forcePublish === true) {
            $params['--force'] = true;
        }

        $this->call('vendor:publish', $params);
    }
}
