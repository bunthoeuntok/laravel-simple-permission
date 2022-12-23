# Laravel simple permission

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bunthoeuntok/laravel-simple-permission.svg?style=flat-square)](https://packagist.org/packages/bunthoeuntok/laravel-simple-permission)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/bunthoeuntok/laravel-simple-permission/run-tests?label=tests)](https://github.com/bunthoeuntok/laravel-simple-permission/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/bunthoeuntok/laravel-simple-permission/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/bunthoeuntok/laravel-simple-permission/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/bunthoeuntok/laravel-simple-permission.svg?style=flat-square)](https://packagist.org/packages/bunthoeuntok/laravel-simple-permission)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.



## Installation

You can install the package via composer:

```bash
composer require bunthoeuntok/laravel-simple-permission
```

You can publish config, migrations and middleware by running:

```bash
php artisan permission:install
```

Then migrate tables by running:

```bash
php artisan migrate
```

This is the contents of the published config file:
```php
return [
    // Menu level structure
    'menu_levels' => [
        'module',
        'sub-module',
        'page'
    ],

    // Cache key
    'cache_key' => 'permissions',

    // Menu structure to import
    'data' => [

    ]
];
```


## Usage

### Step 1
You need to use ```HasRole``` trait in ```User.php``` model

```php
use Bunthoeuntok\SimplePermission\Traits\HasRole;

class User extends Authenticatable
{
    ...
    use HasRole;
    ...
}
```

### Step 2
Register ```PermissionMiddleware.php``` in ```Kernel.php```

```php
protected $routeMiddleware = [
    ...
    'role.permission' => \App\Http\Middleware\PermissionMiddleware::class
];
```

### Step 3
Use naming route middleware in route file.
- Note: When you defined a route you should provide it with ```name```, which match to route_name in [menu action](README.md#actions).

```php
// Index action
Route::get('users', [UserController::class, 'index'])->middleware(['role.psermission', 'auth'])->name('users.index');

// Delete action
Route::get('users/{user}', [UserController::class, 'destroy'])->middleware(['role.psermission', 'auth'])->name('users.destroy');
```


# What we can do
- Create a role and assign user a role
```php
    // Create a role
    $role = Bunthoeuntok\SimplePermission\Models\Role::create([
        'role_name' => 'Admin',
        'is_admin' => false;
    ]);

    // Create user
    $user =  User::factory()->create();

    // Assign role to a user
    $user->assignRole($role);

```
# actions
- Create menu and its actions

```php
    $menu = Bunthoeuntok\SimplePermission\Models\Menu::create([
        'menu_name' => 'User',
        'level' => 'page'; // base on menu_levels in simple-permissions.php config
    ]);

    // Create menu actions
    $menu->actions()->saveMany([
        new Bunthoeuntok\SimplePermission\Models\Menu([
            'action_name' => 'index',
            'route_name' => 'modules.root-pages.index',
            'default' => true,
        ]),
        new Bunthoeuntok\SimplePermission\Models\Menu([
            'action_name' => 'delete',
            'route_name' => 'modules.root-pages.delete',
        ])
    ]);

```
- Or by running command ```php artisan permission:import```, this command will import menu structure, which was set ```data``` key in simple-permission.php config.

```php
return [
    // Menu structure to import
    'data' => [
        [
            'menu_name' => 'User',
            'level' => 'page',
            'actions' => [
                [
                    'action_name' => 'index',
                    'route_name' => 'users.index',
                    'default' => true,
                ],
                [
                    'action_name' => 'delete',
                    'route_name' => 'users.delete',
                ],
            ]
        ],
    ],
]
```
## Changelog


Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Bunthoeun Tok](https://github.com/bunthoeuntok)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
