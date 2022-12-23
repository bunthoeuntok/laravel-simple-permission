<?php

// config for Bunthoeuntok/SimplePermission
return [
    'menu_levels' => [
        'module',
        'sub-module',
        'page',
    ],
    'cache_key' => 'permissions',

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
];
