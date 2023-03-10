<?php

namespace Bunthoeuntok\SimplePermission\Tests\Feature;

use Bunthoeuntok\SimplePermission\Tests\TestCase;

class ImportCommandTest extends TestCase
{
    /** @test */
    public function application_can_run_command()
    {
        $command = $this->artisan('permission:install');
        $command->expectsOutputToContain('Starting import permission...');
    }
}
