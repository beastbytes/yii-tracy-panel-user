<?php

declare(strict_types=1);

return [
    [
        'name' => 'blog.manager',
        'type' => 'role',
        'description' => 'Blog Manager',
        'created_at' => 1746392184,
        'updated_at' => 1746392184,
        'children' => [
            'post.create',
            'post.delete',
            'post.update'
        ]
    ],
    [
        'name' => 'post.create',
        'type' => 'permission',
        'description' => 'Create post',
        'created_at' => 1746392184,
        'updated_at' => 1746392184,
    ],
    [
        'name' => 'post.delete',
        'type' => 'permission',
        'description' => 'Delete post',
        'created_at' => 1746392184,
        'updated_at' => 1746392184,
    ],
    [
        'name' => 'post.update',
        'type' => 'permission',
        'description' => 'Update post',
        'created_at' => 1746392184,
        'updated_at' => 1746392184,
    ],
    [
        'name' => 'user.manager',
        'type' => 'role',
        'description' => 'User Manager',
        'created_at' => 1746392184,
        'updated_at' => 1746392184,
        'children' => [
            'user.create',
            'user.delete',
            'user.update'
        ]
    ],
    [
        'name' => 'user.create',
        'type' => 'permission',
        'description' => 'Create user',
        'created_at' => 1746392184,
        'updated_at' => 1746392184,
    ],
    [
        'name' => 'user.delete',
        'type' => 'permission',
        'description' => 'Delete user',
        'created_at' => 1746392184,
        'updated_at' => 1746392184,
    ],
    [
        'name' => 'user.update',
        'type' => 'permission',
        'description' => 'Update user',
        'created_at' => 1746392184,
        'updated_at' => 1746392184,
    ],
];