<?php

namespace Bunthoeuntok\SimplePermission;

use Bunthoeuntok\SimplePermission\Contracts\Permission;
use Bunthoeuntok\SimplePermission\Exceptions\UnauthorizedException;
use Bunthoeuntok\SimplePermission\Models\Action;
use Bunthoeuntok\SimplePermission\Models\Menu;
use Illuminate\Support\Facades\Cache;

class SimplePermission implements Permission
{
    protected $roleId;
    protected $cacheKey;
    protected $menuLevels;
    protected $permissions;
    protected $menus;

    public function __construct()
    {
        if (!auth()->user() && !auth()->user()->role) {
            throw new UnauthorizedException(403);
        }
        $this->initial();
        dd($this->menuLevels);
    }

    private function initial(): void
    {
        $this->user = auth()->user();
        $this->roleId = auth()->user()->role_id;
        $this->cacheKey = config('simple-permission.cache_key');
        $this->menuLevels = config('simple-permission.menu_levels', ['page']);
        $this->menus = $this->setMenu();
        $this->permissions = $this->setPemrissions();
    }

    public function reloadCache(): void
    {
        Cache::forget($this->cacheKey);
        $this->initial();
    }

    private function setMenu()
    {
        return Cache::rememberForever("{$this->cacheKey}.role.{$this->roleId}.pages", fn () => Menu::tree()->addSelect([
            'route_name' => Action::select('route_name')
                ->when(!$this->user->isAdmin(), function ($query) {
                    return $query->join('role_has_permission', 'permission_id', 'actions.id')
                        ->where('role_id', auth()->user()->role_id)
                        ->where('actions.default', true);
                })
                ->whereColumn('menu_id', 'laravel_cte.id')
                ->where('actions.default', true)
                ->take(1),
        ])
        ->get()
        ->toTree());
    }

    public function treeMenu(string $routeName = ''): array
    {
        function recursive($menus, $currentPermission, $menuLevels)
        {
            foreach ($menus as $key => $menu) {
                if ($menu->level == $menuLevels[count($menuLevels) -1]) {
                    if (! $menu->route_name) {
                        $menus->forget($key);
                    } else {
                        if (! empty($currentPermission)) {
                            $menu->is_current = $menu->id === $currentPermission['menu_id'] ? true : false;
                        } else {
                            $menu->is_current = false;
                        }
                    }
                } else {
                    if (hasPages($menu->children, $menuLevels)) {
                        recursive($menu->children, $currentPermission, $menuLevels);
                    } else {
                        $menus->forget($key);
                    }
                }
            }

            return $menus;
        }

        function hasPages($menus, $menuLevels)
        {
            return $menus->some(function ($menu) use ($menuLevels) {
                return ($menu->level == $menuLevels[count($menuLevels) - 1] && $menu->route_name) || hasPages($menu->children, $menuLevels);
            });
        }

        return recursive($this->menus, $this->currentPermission($routeName), $this->menuLevels)->toArray();
    }

    private function currentPermission($routeName): array
    {
        $permissions = $this->allPermissions();
        $currentPermission = array_values(array_filter($permissions, fn ($permission) => $permission['route_name'] == $routeName));
        if (empty($currentPermission)) {
            return [];
        }

        return $currentPermission[0];
    }

    private function setPemrissions(): array
    {
        return Cache::rememberForever("{$this->cacheKey}.roles.{$this->roleId}.actions", fn () => Action::select()
                ->when(
                    !$this->user->isAdmin(),
                    fn ($query) => $query->join('role_has_permission', 'actions.id', 'permission_id')
                    ->where('role_id', auth()->user()->role_id)
                )->get()->toArray());
    }

    public function checkWhiteList($routeName): int
    {
        return count($this->currentPermission($routeName));
    }

    public function allPermissions(): array
    {
        return $this->permissions;
    }
}
