<?php

namespace Bunthoeuntok\SimplePermission\Tests;

use Bunthoeuntok\SimplePermission\Traits\HasRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User;

class TestUser extends User
{
    use HasFactory;
    use HasRole;


    protected $table = 'users';
}
