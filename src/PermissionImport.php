<?php

namespace Bunthoeuntok\SimplePermission;

use Bunthoeuntok\SimplePermission\Models\Menu;
use Illuminate\Support\Facades\DB;

class PermissionImport
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function save()
    {
        function recursiveSave($menus, $parerntId = null)
        {
            foreach ($menus as $menu) {
                $created = Menu::updateOrCreate(['slug' => str($menu['menu_name'])->slug()], [
                    'level' => $menu['level'],
                    'menu_name' => $menu['menu_name'],
                    'parent_id' => $parerntId,
                ]);

                if (isset($menu['actions']) && count($menu['actions'])) {
                    $created->actions()->createMany($menu['actions']);
                } elseif (isset($menu['children'])) {
                    recursiveSave($menu['children'], $created->id);
                }
            }
        }

        DB::transaction(function () {
            recursiveSave($this->data);
        });
    }
}
