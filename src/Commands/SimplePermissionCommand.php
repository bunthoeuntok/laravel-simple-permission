<?php

namespace Bunthoeuntok\SimplePermission\Commands;

use Bunthoeuntok\SimplePermission\PermissionParse;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SimplePermissionCommand extends Command
{
    public $signature = 'permission:install';

    public $description = 'My command';

    public function handle()
    {
        $this->info('Starting import permission...');

        try {
            $sample = $this->loadFile();
        } catch (\Throwable $th) {
            return;
        }

        $parse = new PermissionParse($sample);
        if (! $parse->validate()) {
            $this->error('import file is incorrect format.');
            return;
        }
    }

    private function loadFile()
    {
        try {
            return File::get(__DIR__.'/../../tests/sample-import.json');
        } catch (\Throwable $th) {
            $this->error('File not found.');
            return;
        }
    }
}
