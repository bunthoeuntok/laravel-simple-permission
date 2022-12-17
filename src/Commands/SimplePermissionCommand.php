<?php

namespace Bunthoeuntok\SimplePermission\Commands;

use Illuminate\Console\Command;

class SimplePermissionCommand extends Command
{
    public $signature = 'laravel-simple-permission';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
