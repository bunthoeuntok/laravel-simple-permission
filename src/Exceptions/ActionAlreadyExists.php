<?php

namespace Bunthoeuntok\SimplePermission\Exceptions;

use Exception;

class ActionAlreadyExists extends Exception
{
    protected $routeName;

    public function __construct(string $routeName = 'route name')
    {
        $this->message = "`{$routeName}` is already exists!";
    }
}
