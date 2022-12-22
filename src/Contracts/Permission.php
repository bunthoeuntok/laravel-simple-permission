<?php

namespace Bunthoeuntok\SimplePermission\Contracts;

use Illuminate\Http\Request;

interface Permission
{
    public function reloadCache(): void;

    public function treeMenu(string $routeName = ''): array;

    public function allPermissions(): array;

    public function checkWhiteList(string $routeName): int;

    public function pageActions(): array;
}
