<?php

return [
    'roles' => [
        [
            'name' =>  'Admin',
            'guard_name' => 'api'
        ],

        [
            'name' =>  'Author',
            'guard_name' => 'api'
        ],

        [
            'name' =>  'User',
            'guard_name' => 'api'
        ],
    ],

    'permissions' => [
        ['name' => 'view roles', 'guard_name' => 'api'],
        ['name' => 'create roles', 'guard_name' => 'api'],
        ['name' => 'update roles', 'guard_name' => 'api'],
        ['name' => 'delete roles', 'guard_name' => 'api'],
        ['name' => 'view permissions', 'guard_name' => 'api'],
        ['name' => 'create permissions', 'guard_name' => 'api'],
        ['name' => 'update permissions', 'guard_name' => 'api'],
        ['name' => 'delete permissions', 'guard_name' => 'api'],
        ['name' => 'view users', 'guard_name' => 'api'],
        ['name' => 'create users', 'guard_name' => 'api'],
        ['name' => 'update users', 'guard_name' => 'api'],
        ['name' => 'delete users', 'guard_name' => 'api'],
        ['name' => 'view books', 'guard_name' => 'api'],
        ['name' => 'create books', 'guard_name' => 'api'],
        ['name' => 'update books', 'guard_name' => 'api'],
        ['name' => 'delete books', 'guard_name' => 'api'],
        ['name' => 'view authors', 'guard_name' => 'api'],
        ['name' => 'update authors', 'guard_name' => 'api'],
        ['name' => 'delete authors', 'guard_name' => 'api'],
        ['name' => 'change user status', 'guard_name' => 'api'],
    ],

    'author_permissions' => [
        'view books',
        'create books',
        'update books',
        'delete books',
    ]
];