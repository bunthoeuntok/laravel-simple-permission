<?php

namespace Bunthoeuntok\SimplePermission\Commands;

use Bunthoeuntok\SimplePermission\PermissionImport;
use Illuminate\Console\Command;

class SimplePermissionCommand extends Command
{
    public $signature = 'permission:install';

    public $description = 'My command';

    public function handle()
    {
        $this->info('Starting import permission...');
        $data = config('simple-permission.data');
        $import = new PermissionImport($data);
        $import->save();
    }
}
