<?php

namespace Bunthoeuntok\SimplePermission\Commands;

use Bunthoeuntok\SimplePermission\Models\Action;
use Bunthoeuntok\SimplePermission\Models\Menu;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
                $this->saveForce($data);
                $this->info('Import overwrite!');
            }
        }
    }

    private function shouldOverwrite($message)
    {
        return $this->confirm(
            $message.
            ', Do you want to start over again?',
            false
        );
    }

    private function save($data)
    {
        function recursiveSave($menus, $parerntId = null, $force = false)
        {
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

        recursiveSave($data, null);
    }

    private function saveForce($data)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('role_has_permission')->truncate();
        DB::table('actions')->truncate();
        DB::table('menus')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        function recursiveSaveForce($menus, $parerntId = null)
        {
            foreach ($menus as $menu) {
                $created = Menu::updateOrCreate(['slug' => str($menu['menu_name'])->slug()], [
                    'level' => $menu['level'],
                    'menu_name' => $menu['menu_name'],
                    'parent_id' => $parerntId,
                    'order' => $menu['order'] ?? null,
                ]);

                if (isset($menu['actions']) && count($menu['actions'])) {
                    foreach ($menu['actions'] as $action) {
                        Action::updateOrCreate(['route_name' => $action['route_name']], [
                            'action_name' => $action['action_name'],
                            'default' => $action['default'] ?? false,
                            'menu_id' => $created->id,
                            'order' => $action['order'] ?? null,
                        ]);
                    }
                } elseif (isset($menu['children'])) {
                    recursiveSaveForce($menu['children'], $created->id);
                }
            }
        }

        recursiveSaveForce($data, null);
    }
}
