<?php

namespace Bunthoeuntok\SimplePermission\Exceptions;

use Exception;

class UserDoesNotHaveRole extends Exception
{
    public function __construct()
    {
        $this->message = "User haven't assigned to a role yet.";
    }
}
