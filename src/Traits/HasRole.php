<?php

namespace Bunthoeuntok\SimplePermission\Traits;

use Bunthoeuntok\SimplePermission\Exceptions\UserDoesNotHaveRole;
use Bunthoeuntok\SimplePermission\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasRole
{
    public function role(): BelongsTo
    {
        return $this->BelongsTo(Role::class);
    }

    public function assignRole(Role $role): void
    {
        $this->role()->associate($role)->save();
    }

    public function isAdmin()
    {
        if (! $this->role) {
            throw new UserDoesNotHaveRole();
        }

        return $this->role->is_admin;
    }
}
