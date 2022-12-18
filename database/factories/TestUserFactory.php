<?php

namespace Bunthoeuntok\SimplePermission\Database\Factories;

use Bunthoeuntok\SimplePermission\Tests\TestUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestUserFactory extends Factory
{
    protected $model = TestUser::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->email(),
            'password' => bcrypt('password')
        ];
    }
}
