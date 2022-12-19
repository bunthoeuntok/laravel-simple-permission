<?php

namespace Bunthoeuntok\SimplePermission\Database\Factories;

use Bunthoeuntok\SimplePermission\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        return [
            'role_name' => $this->faker->name(),
        ];
    }
}
