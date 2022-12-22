<?php

namespace Bunthoeuntok\SimplePermission\Exceptions;

use Exception;

class MenuLevelNotMatch extends Exception
{
    public function __construct($level)
    {
        $this->message = "Menu level `{$level}` doesn't set in the configuation file.";
    }
}
