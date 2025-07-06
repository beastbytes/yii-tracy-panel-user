<?php
/** 
 * @var CurrentUser $currentUser
 * @var Permission[] $permissions
 * @var Role[] $roles
 * @var TranslatorInterface $translator
 * @var array $userParameters
 */

use BeastBytes\Yii\Tracy\Helper;
use BeastBytes\Yii\Tracy\Panel\User\Panel;
use Yiisoft\Rbac\Item;
use Yiisoft\Rbac\Permission;
use Yiisoft\Rbac\Role;
use Yiisoft\Translator\TranslatorInterface;
use Yiisoft\User\CurrentUser;

$translator = $translator->withDefaultCategory(Panel::MESSAGE_CATEGORY);

$tbody = [[
    $translator->translate('user.heading.id'),
    $currentUser->isGuest()
        ? $translator->translate('user.value.guest')
        : $currentUser->getId()
]];

foreach ($userParameters as $key => $value) {
    $tbody[] = [$key, $value];
}

$tbody[] = [
    $translator->translate('user.heading.roles'),
    $roles === []
        ? $translator->translate('user.value.no-roles-assigned')
        : '<dl>' . implode('', array_map(
            fn (Item $item) => '<dt>' . $item->getName() . '</dt><dd>' . $item->getDescription() . '</dd>',
            $roles
        )) . '</dl>'
];

$tbody[] = [
    $translator->translate('user.heading.permissions'),
    $roles === []
        ? $translator->translate('user.value.no-permissions-granted')
        : '<dl>' . implode('', array_map(
            fn (Item $item) => '<dt>' . $item->getName() . '</dt><dd>' . $item->getDescription() . '</dd>',
            $permissions
        )) . '</dl>'
];

echo Helper::table($tbody);