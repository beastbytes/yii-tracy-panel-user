<?php
/** 
 * @var CurrentUser $currentUser
 * @var Permission[] $permissions
 * @var Role[] $roles
 * @var TranslatorInterface $translator
 * @var array $userParameters
 */

use BeastBytes\Yii\Tracy\Panel\User\Panel;
use Yiisoft\Rbac\Permission;
use Yiisoft\Rbac\Role;
use Yiisoft\Translator\TranslatorInterface;
use Yiisoft\User\CurrentUser;

$translator = $translator->withDefaultCategory(Panel::MESSAGE_CATEGORY);
?>
<table>
    <tbody>
    <tr>
        <th><?= $translator->translate('user.heading.id') ?></th>
        <td>
            <?= $currentUser->isGuest()
                ? $translator->translate('user.value.guest')
                : $currentUser->getId()
            ?>
        </td>
    </tr>
    <?php foreach ($userParameters as $key => $value): ?>
    <tr>
        <th><?= $key ?></th>
        <td><?= $value ?></td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <th><?= $translator->translate('user.heading.roles') ?></th>
        <td>
            <ul>
                <?php foreach ($roles as $role): ?>
                <li><?= $role->getName() ?></li>
                <?php endforeach; ?>
            </ul>
        </td>
    </tr>
    <tr>
        <th><?= $translator->translate('user.heading.permissions') ?></th>
        <td>
            <ul>
                <?php foreach ($permissions as $permission): ?>
                <li><?= $permission->getName() ?></li>
                <?php endforeach; ?>
            </ul>
        </td>
    </tr>
    </tbody>
</table>