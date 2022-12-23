<?php

namespace Bunthoeuntok\SimplePermission\Commands;

use Bunthoeuntok\SimplePermission\Models\Action;
use Bunthoeuntok\SimplePermission\Models\Menu;
use Illuminate\Console\Command;

class PermissionImportCommand extends Command
{
    public $signature = 'permission:import';

    public $description = 'Import permission data';

    public function handle()
    {
        $this->info('Starting import permission...');
        $data = config('simple-permission.data');
        try {
            $this->save($data);
            $this->info('Done...');
        } catch (\Throwable $th) {
            if ($this->shouldOverwrite($th->getMessage())) {
                $this->save($data, true);
                $this->info('Import overwrite!');
            }
        }
    }

    private function shouldOverwrite($message)
    {
        return $this->confirm(
            $message.
            ', Do you want to overwrite it?',
            false
        );
    }

    private function save($data, $force = false)
    {
        function recursiveSave($menus, $parerntId = null, $force = false)
        {
            if ($force) {
                Menu::truncate();
                Action::truncate();
            }
            foreach ($menus as $menu) {
                $created = Menu::create([
                    'level' => $menu['level'],
                    'menu_name' => $menu['menu_name'],
                    'parent_id' => $parerntId,
                    'order' => $menu['order'] ?? null,
                ]);

                if (isset($menu['actions']) && count($menu['actions'])) {
                    $created->actions()->createMany($menu['actions']);
                } elseif (isset($menu['children'])) {
                    recursiveSave($menu['children'], $created->id);
                }
            }
        }

        recursiveSave($data, null, $force);
    }

    // private function saveForce($data)
    // {
    //     Menu::truncate();
    //     Action::truncate();
    //     function recursiveSaveForce($menus, $parerntId = null)
    //     {
    //         foreach ($menus as $menu) {
    //             $created = Menu::create([
    //                 'level' => $menu['level'],
    //                 'menu_name' => $menu['menu_name'],
    //                 'parent_id' => $parerntId,
    //                 'order' => $menu['order'] ?? null,
    //             ]);

    //             if (isset($menu['actions']) && count($menu['actions'])) {
    //                 foreach ($menu['actions'] as $action) {
    //                     Action::updateOrCreate(['route_name' => $action['route_name']], [
    //                         'action_name' => $action['action_name'],
    //                         'default' => $action['default'] ?? false,
    //                         'menu_id' => $created->id,
    //                         'order' => $action['order'] ?? null,
    //                     ]);
    //                 }
    //             } elseif (isset($menu['children'])) {
    //                 recursiveSaveForce($menu['children'], $created->id);
    //             }
    //         }
    //     }

    //     recursiveSaveForce($data, null);
    // }
}
