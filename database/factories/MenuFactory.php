<?php

namespace Bunthoeuntok\SimplePermission\Database\Factories;

use Bunthoeuntok\SimplePermission\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuFactory extends Factory
{
    protected $model = Menu::class;

    public function definition()
    {
        $menuLevels = config('simple-permission.menu_levels');

        return [
            'menu_name' => $this->faker->unique()->name(),
            'order' => $this->faker->randomDigit(),
            'level' => $menuLevels[random_int(0, count($menuLevels) - 1)],
        ];
    }
}
