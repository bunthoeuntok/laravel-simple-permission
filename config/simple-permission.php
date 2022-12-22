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
            "menu_name" => "pages",
            "level" => "page",
            "actions" => [
                [
                    "action_name" => "index",
                    "route_name" => "modules.pages.index",
                    "default" => true
                ],
                [
                    "action_name" => "create",
                    "route_name" => "modules.pages.create"
                ],
                [
                    "action_name" => "edit",
                    "route_name" => "modules.pages.edit"
                ],
                [
                    "action_name" => "delete",
                    "route_name" => "modules.pages.delete"
                ]
            ]
        ]
    ]
];
