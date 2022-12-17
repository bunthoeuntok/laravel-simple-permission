<?php

namespace Bunthoeuntok\SimplePermission\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function (Model $model) {
            $model->slug = str($model->name)->slug();
        });
    }

    public function actions(): HasMany
    {
        return $this->hasMany(Action::class);
    }
}
