<?php
/** 
 * @var \Yiisoft\Userr\CurrentUser $currentUser
 * @var \Yiisoft\Rbac\Permission[] $permissions
 * @var \Yiisoft\Rbac\Role[] $roles
 * @var array $userParameters
 */
?>
<table>
    <tbody>
    <tr>
        <th>ID</th>
        <td><?= $currentUser->isGuest() ? 'Guest' : $currentUser->getId() ?></td>
    </tr>
    <?php foreach ($userParameters as $key => $value): ?>
    <tr>
        <th><?= $key ?></th>
        <td><?= $value ?></td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <th>Roles<?= count($roles) !== 1 ? 's' : '' ?></th>
        <td>
            <ul>
                <?php foreach ($roles as $role): ?>
                <li><?= $role->getName() ?></li>
                <?php endforeach; ?>
            </ul>
        </td>
    </tr>
    <tr>
        <th>Permissions<?= count($permissions) !== 1 ? 's' : '' ?></th>
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