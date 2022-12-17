<?php

namespace Bunthoeuntok\SimplePermission\Models;

use Bunthoeuntok\SimplePermission\Exceptions\ActionAlreadyExist;
use Bunthoeuntok\SimplePermission\Exceptions\ActionAlreadyExists;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory;

    protected $table = 'actions';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function (Model $model) {
            if (self::query()->where('route_name', $model->route_name)->first()) {
                throw new ActionAlreadyExists($model->route_name);
            }
        });
    }
}
