<?php

namespace Bunthoeuntok\SimplePermission\Models;

use Bunthoeuntok\SimplePermission\Exceptions\MenuAlreadyExists;
use Bunthoeuntok\SimplePermission\Exceptions\MenuLevelNotMatch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Menu extends Model
{
    use HasFactory;
    use HasRecursiveRelationships;

    protected $table = 'menus';

    protected $guarded = ['slug'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function (Model $model) {
            if (! in_array($model->level, config('simple-permission.menu_levels', ['page']))) {
                throw new MenuLevelNotMatch();
            }
            if (self::query()->where('slug', str($model->menu_name)->slug()->toString())->where('parent_id', $model->parent_id)->first()) {
                throw new MenuAlreadyExists();
            }
            $model->slug = str($model->menu_name)->slug()->toString();
        });
    }

    public function actions(): HasMany
    {
        return $this->hasMany(Action::class);
    }
}
