<?php

declare(strict_types=1);

namespace BeastBytes\Yii\Tracy\Panel\User;

use BeastBytes\Yii\Tracy\ViewTrait;
use Yiisoft\Rbac\AssignmentsStorageInterface;
use Yiisoft\Rbac\ItemsStorageInterface;
use Yiisoft\Rbac\Manager;
use Yiisoft\User\CurrentUser;

final class Panel extends \BeastBytes\Yii\Tracy\Panel\Panel
{
    use ViewTrait;

    public const MESSAGE_CATEGORY = 'tracy-user';

    private const ICON_AUTHORISED = <<<ICON
<svg 
    xmlns="http://www.w3.org/2000/svg"
    height="24px"
    viewBox="0 -960 960 960"
    width="24px"
    fill="#5ea500"
>
    <path
        d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34
        17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5
        43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5
        14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5
        56.5T480-560Zm0-80Zm0 400Z"
    />
</svg>
ICON;

    private const ICON_GUEST = <<<ICON
<svg
    xmlns="http://www.w3.org/2000/svg"
    height="24px"
    viewBox="0 -960 960 960"
    width="24px"
    fill="#404040"
>
    <path 
        d="M791-55 686-160H160v-112q0-34 17.5-62.5T224-378q45-23 91.5-37t94.5-21L55-791l57-57 736 736-57 
        57ZM240-240h366L486-360h-6q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm496-138q29 14 46 42.5t18 
        61.5L666-408q18 7 35.5 14t34.5 16ZM568-506l-59-59q23-9 37-29.5t14-45.5q0-33-23.5-56.5T480-720q-25 0-45.5 
        14T405-669l-59-59q23-34 58-53t76-19q66 0 113 47t47 113q0 41-19 76t-53 58Zm38 266H240h366ZM457-617Z"
    />
</svg> 
ICON;

    private ?CurrentUser $currentUser = null;
    private $id2pk;
    private $tabValue;
    private $userParameters;

    /**
     * @param callable|null $id2pk Converts a string ID to a database primary key.
     * Example, if using a UUID that is stored in binary format.
     * The signature is (string $id); returns string
     * @param callable|null $tabValue Function that returns the value displayed on the debugger bar.
     * If not defined, the current user ID is shown.
     * The signature is (IdentityInterface $identity); returns string
     * @param callable|null $userParameters Function that returns additional user parameters to show on the panel.
     * The signature is (IdentityInterface $identity); returns array{string: string}
     */
    public function __construct(callable $id2pk = null, callable $tabValue = null, callable $userParameters = null)
    {
        if (is_callable($id2pk)) {
            $this->id2pk = $id2pk;
        }
        if (is_callable($tabValue)) {
            $this->tabValue = $tabValue;
        }
        if (is_callable($userParameters)) {
            $this->userParameters = $userParameters;
        }
    }

    public function panelParameters(): array
    {
        $panelParameters = [
            'currentUser' => $this->getCurrentUser(),
            'permissions' => [],
            'roles' => [],
            'userParameters' => [],
        ];
        
        if (
            $this
                ->container
                ->has(AssignmentsStorageInterface::class)
        ) {
            $rbacManager = new Manager(
                $this
                    ->container
                    ->get(ItemsStorageInterface::class)
                ,
                $this
                    ->container
                    ->get(AssignmentsStorageInterface::class)
            );

            $userId = $this
                ->currentUser
                ->getId()
            ;

            if ($userId === null) {
                $guestRoleName = $rbacManager->getGuestRoleName();

                $panelParameters['roles'] = $guestRoleName !== null
                    ? [$rbacManager->getGuestRole()]
                    : []
                ;

                $panelParameters['permissions'] = $guestRoleName !== null
                    ? $rbacManager->getPermissionsByRoleName($guestRoleName)
                    : []
                ;
            } else {
                $panelParameters['roles'] = $rbacManager
                    ->getRolesByUserId(
                        is_callable($this->id2pk) ? ($this->id2pk)($userId) : $userId
                    )
                ;

                $panelParameters['permissions'] = $rbacManager
                    ->getPermissionsByUserId(
                        is_callable($this->id2pk) ? ($this->id2pk)($userId) : $userId
                    )
                ;

                if (is_callable($this->userParameters)) {
                    $panelParameters['userParameters'] = ($this->userParameters)($this->currentUser->getIdentity());
                }
            }
        }
        
        return $panelParameters;
    }

    public function panelTitle(): array
    {
        return [
            'id' => 'user.title.panel',
            'category' => 'tracy-user',
        ];
    }

    /**
     * @param array $parameters
     * @psalm-param array{
     *     currentUser: CurrentUser
     * }
     * @return string
     */
    public function tabIcon(array $parameters): string
    {
        return $this->getCurrentUser()->isGuest()
            ? self::ICON_GUEST
            : self::ICON_AUTHORISED
        ;
    }

    public function tabParameters(): array
    {
        if ($this->getCurrentUser()->isGuest()) {
            $value = null;
        } else {
            $value = is_callable($this->tabValue)
                ? ($this->tabValue)($this->getCurrentUser()->getIdentity())
                : $this->getCurrentUser()->getId()
            ;
        }

        return ['value' => $value];
    }

    public function tabTitle(): array
    {
        return [
            'id' => 'user.title.tab',
            'category' => 'tracy-user',
       ];
    }

    private function getCurrentUser(): CurrentUser
    {
        if ($this->currentUser === null) {
            $this->currentUser = $this->container->get(CurrentUser::class);
        }

        return $this->currentUser;
    }
}