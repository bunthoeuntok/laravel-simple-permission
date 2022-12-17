<?php

namespace Bunthoeuntok\SimplePermission\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Bunthoeuntok\SimplePermission\SimplePermission
 */
class SimplePermission extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Bunthoeuntok\SimplePermission\SimplePermission::class;
    }
}
