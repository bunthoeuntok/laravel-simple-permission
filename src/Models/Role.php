<?php

namespace Bunthoeuntok\SimplePermission\Models;

use Bunthoeuntok\SimplePermission\Exceptions\RoleAlreadyExists;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $guarded = [];

    protected static function bood()
    {
        parent::boot();

        static::creating(function (Model $model) {
            if (self::query()->where('role_name', $model->role_name)->first()) {
                throw new RoleAlreadyExists($model->role_name);
            }
        });
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Action::class, 'role_has_permission', 'role_id', 'permission_id');
    }

    public function givePermissions($actions)
    {
        if ($actions instanceof Collection) {
            $ids = $actions->pluck('id');
        } else {
            $ids = array_column($actions, 'id');
        }

        $this->permissions()->sync($ids);
    }
}
