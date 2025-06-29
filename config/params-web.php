<?php

declare(strict_types=1);

return [
    'beastbytes/yii-tracy' => [
        'panels' => [
            'user' => [
                'class' => BeastBytes\Yii\Tracy\Panel\User\Panel::class,
            ],
        ],
    ],
];
