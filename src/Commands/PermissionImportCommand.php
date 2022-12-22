<?php

namespace Bunthoeuntok\SimplePermission\Commands;

use Bunthoeuntok\SimplePermission\PermissionImport;
use Illuminate\Console\Command;

class PermissionImportCommand extends Command
{
    public $signature = 'permission:import';

    public $description = 'Import permission data';

    public function handle()
    {
        $this->info('Starting import permission...');
        $data = config('simple-permission.data');
        $import = new PermissionImport($data);
        try {
            $import->save();
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }
}
