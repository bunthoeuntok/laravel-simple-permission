<?php

namespace Bunthoeuntok\SimplePermission\Exceptions;

use Exception;

class MenuAlreadyExists extends Exception
{
    public function __construct($menu)
    {
        $this->message = "Menu `{$menu}` is already exists";
    }
}
