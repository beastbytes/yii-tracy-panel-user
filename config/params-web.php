<?php

declare(strict_types=1);

return [
    'beastbytes/yii-tracy' => [
        'panelConfig' => [
            'user' => [
                'class' => BeastBytes\Yii\Tracy\Panel\User\Panel::class,
            ],
        ],
    ],
];