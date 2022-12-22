<?php

namespace Bunthoeuntok\SimplePermission\Exceptions;

use Exception;

class RoleAlreadyExists extends Exception
{
    public function __construct($role)
    {
        $this->message = "Role `{$role}` is already exists.";
    }
}
