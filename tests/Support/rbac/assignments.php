<?php

declare(strict_types=1);

use BeastBytes\Yii\Tracy\Panel\User\Tests\PanelTest;

return [
    [
        'item_name' => 'blog.manager',
        'user_id' => 'user-2',
        'created_at' => 1746392303,
    ],
    [
        'item_name' => 'user.manager',
        'user_id' => hex2bin(PanelTest::UUID),
        'created_at' => 1746392303,
    ],
];