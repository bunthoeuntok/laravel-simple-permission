<?php

namespace Bunthoeuntok\SimplePermission;

use Bunthoeuntok\SimplePermission\Models\Action;
use Bunthoeuntok\SimplePermission\Models\Menu;
use Illuminate\Support\Facades\Cache;

class SimplePermission
{
    protected $roleId;
    protected $permissions;
    protected $menus;

    private function getMenus()
    {
        return Menu::tree()->addSelect([
            'route_name' => Action::select('route_name')
                ->when(!auth()->user()->isAdmin(), function ($query) {
                    return $query->join('role_has_permission', 'permission_id', 'actions.id')
                        ->where('role_id', auth()->user()->role_id)
                        ->where('actions.default', true);
                })
                ->whereColumn('menu_id', 'laravel_cte.id')
                ->where('actions.default', true)
                ->take(1)
            ])
        ->get()
        ->toTree();
    }

    public function treeMenu($routeName)
    {
        function recursive($menus, $currentPermission)
        {
            foreach ($menus as $key => $menu) {
                if ($menu->level == 'page') {
                    if (!$menu->route_name) {
                        $menus->forget($key);
                    } else {
                        if (!empty($currentPermission)) {
                            $menu->is_current = $menu->id === $currentPermission['menu_id'] ? true : false;
                        } else {
                            $menu->is_current = false;
                        }
                    }
                } else {
                    if (hasPages($menu->children)) {
                        recursive($menu->children, $currentPermission);
                    } else {
                        $menus->forget($key);
                    }
                }
            }
            return $menus;
        }

        function hasPages($menus)
        {
            return $menus->some(function ($menu) {
                return ($menu->level == 'page' && $menu->route_name) || hasPages($menu->children);
            });
        }
        return recursive($this->getMenus(), $this->currentPermission($routeName))->toArray();
    }


    private function currentPermission($routeName)
    {
        $permissions        = $this->getPermissions();
        $currentPermission  = array_values(array_filter($permissions, fn ($permission) => $permission['route_name'] == $routeName));
        if (empty($currentPermission)) {
            return [];
        }

        return $currentPermission[0];
    }

    public function getPermissions()
    {
        return Action::select()
                ->when(
                    !auth()->user()->isAdmin(),
                    fn ($query) =>
                    $query->join('role_has_permission', 'actions.id', 'permission_id')
                    ->where('role_id', auth()->user()->role_id)
                )->get()->toArray();
    }

    public function checkWhiteList($routeName)
    {
        return sizeof($this->currentPermission($routeName));
    }
}
