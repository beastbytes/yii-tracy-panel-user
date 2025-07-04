<?php
/** 
 * @var \Yiisoft\User\CurrentUser $currentUser
 * @var \Yiisoft\Rbac\Permission[] $permissions
 * @var \Yiisoft\Rbac\Role[] $roles
 * @var \Yiisoft\Translator\TranslatorInterface $translator
 * @var array $userParameters
 */
?>
<table>
    <tbody>
    <tr>
        <th><?= $translator->translate('user.heading.id', category: 'tracy-user') ?></th>
        <td>
            <?= $currentUser->isGuest()
                ? $translator->translate('user.value.guest', category: 'tracy-user')
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
        <th><?= $translator->translate('user.heading.roles', category: 'tracy-user') ?></th>
        <td>
            <ul>
                <?php foreach ($roles as $role): ?>
                <li><?= $role->getName() ?></li>
                <?php endforeach; ?>
            </ul>
        </td>
    </tr>
    <tr>
        <th><?= $translator->translate('user.heading.permissions', category: 'tracy-user') ?></th>
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