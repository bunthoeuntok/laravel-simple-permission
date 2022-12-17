<?php

namespace Bunthoeuntok\SimplePermission\Database\Factories;

use Bunthoeuntok\SimplePermission\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuFactory extends Factory
{
    protected $model = Menu::class;

    public function definition()
    {
        return [
            'menu_name' => $this->faker->name()
        ];
    }
}
