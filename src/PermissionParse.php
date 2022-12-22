<?php

namespace Bunthoeuntok\SimplePermission;

class PermissionParse
{
    protected $data;

    public function __construct($data)
    {
        $this->data = json_decode($data);
    }

    public function validate()
    {
        return false;
    }
}
