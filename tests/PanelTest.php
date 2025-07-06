<?php

namespace BeastBytes\Yii\Tracy\Panel\User\Tests;

use BeastBytes\Yii\Tracy\Panel\User\Panel;
use BeastBytes\Yii\Tracy\Panel\User\Tests\Support\MockIdentity;
use BeastBytes\Yii\Tracy\Panel\User\Tests\Support\MockIdentityRepository;
use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\BeforeClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Yiisoft\Rbac\AssignmentsStorageInterface;
use Yiisoft\Rbac\ItemsStorageInterface;
use Yiisoft\Rbac\Php\AssignmentsStorage;
use Yiisoft\Rbac\Php\ItemsStorage;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Test\Support\EventDispatcher\SimpleEventDispatcher;
use Yiisoft\Translator\CategorySource;
use Yiisoft\Translator\IntlMessageFormatter;
use Yiisoft\Translator\Message\Php\MessageSource;
use Yiisoft\Translator\Translator;
use Yiisoft\User\CurrentUser;
use Yiisoft\User\Guest\GuestIdentityFactory;
use Yiisoft\View\View;

class PanelTest extends TestCase
{
    public const UUID = '0601f6dfdadc4c6bb4241462c89440cd';

    private const USER_1 = 'user-1';
    private const USER_2 = 'user-2';
    private const LOCALE = 'en-GB';

    private static SimpleContainer $container;

    private Panel $panel;

    #[BeforeClass]
    public static function setUpBeforeClass(): void
    {
        self::$container = new SimpleContainer([
            CurrentUser::class => new CurrentUser(
                new MockIdentityRepository(new MockIdentity()),
                new SimpleEventDispatcher(),
                new GuestIdentityFactory()
            ),
            View::class => (new View())
                ->setParameter(
                    'translator',
                    (new Translator())
                        ->withLocale(self::LOCALE)
                        ->addCategorySources(new CategorySource(
                            Panel::MESSAGE_CATEGORY,
                            new MessageSource(
                                dirname(__DIR__)
                                . DIRECTORY_SEPARATOR . 'resources'
                                . DIRECTORY_SEPARATOR . 'messages'
                            ),
                            new IntlMessageFormatter(),
                        ))
                )
            ,
            ItemsStorageInterface::class => new ItemsStorage(
                __DIR__ . DIRECTORY_SEPARATOR . 'Support' . DIRECTORY_SEPARATOR . 'rbac'
                . DIRECTORY_SEPARATOR . 'items.php'
            ),
            AssignmentsStorageInterface::class => new AssignmentsStorage(
                __DIR__ . DIRECTORY_SEPARATOR . 'Support' . DIRECTORY_SEPARATOR . 'rbac'
                . DIRECTORY_SEPARATOR . 'assignments.php'
            ),
        ]);
    }

    #[After]
    public function tearDown(): void
    {
        self::$container
            ->get(CurrentUser::class)
            ->logout()
        ;
    }

    #[Before]
    public function setUp(): void
    {
        $this->panel = (new Panel())
            ->withContainer(self::$container)
        ;
    }

    #[Test]
    public function viewPath(): void
    {
        $this->assertSame(
            dirname(__DIR__)
                . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR,
            $this->panel->getViewPath());
    }

    #[Test]
    public function tabGuest(): void
    {
        $expected = <<<HTML
<span title="Current User"><svg
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
</svg><span class="tracy-label">User: Guest</span></span>
HTML;

        $this->assertSame($expected, $this->panel->getTab());
    }

    #[Test]
    public function tabAuthorised(): void
    {
        self::$container
            ->get(CurrentUser::class)
            ->login(new MockIdentity(self::USER_1))
        ;

        $expected = sprintf(<<<HTML
<span title="Current User"><svg
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
</svg><span class="tracy-label">User: %s</span></span>
HTML, self::USER_1);

        $this->assertSame($expected, $this->panel->getTab());
    }

    #[Test]
    public function panelGuest(): void
    {
        $expected = "<h1>Current User</h1>\n"
            . '<div class="tracy-inner"><div class="tracy-inner-container">'
            . '<table><tbody>'
            . '<tr><th>ID</th><td>Guest</td></tr>'
            . '<tr><th>Roles</th><td>No roles assigned</td></tr>'
            . '<tr><th>Permissions</th><td>No permissions granted</td></tr>'
            . '</tbody></table>'
            . '</div></div>'
        ;

        $this->assertSame($expected, $this->panel->getPanel());
    }

    #[Test]
    public function panelAuthorised(): void
    {
        self::$container
            ->get(CurrentUser::class)
            ->login(new MockIdentity(self::USER_1))
        ;

        $expected = sprintf(
            "<h1>Current User</h1>\n"
            . '<div class="tracy-inner"><div class="tracy-inner-container">'
            . '<table><tbody>'
            . '<tr><th>ID</th><td>%s</td></tr>'
            . '<tr><th>Roles</th><td>No roles assigned</td></tr>'
            . '<tr><th>Permissions</th><td>No permissions granted</td></tr>'
            . '</tbody></table>'
            . '</div></div>',
    self::USER_1
        );

        $this->assertSame($expected, $this->panel->getPanel());
    }

    #[Test]
    public function panelAuthorisedWithRBAC(): void
    {
        self::$container
            ->get(CurrentUser::class)
            ->login(new MockIdentity(self::USER_2))
        ;

        $expected = sprintf(
            "<h1>Current User</h1>\n"
            . '<div class="tracy-inner"><div class="tracy-inner-container">'
            . '<table><tbody>'
            . '<tr><th>ID</th><td>%s</td></tr>'
            . '<tr><th>Roles</th><td><dl>'
            . '<dt>blog.manager</dt><dd>Blog Manager</dd>'
            . '</dl></td></tr>'
            . '<tr><th>Permissions</th><td><dl>'
            . '<dt>post.create</dt><dd>Create post</dd>'
            . '<dt>post.delete</dt><dd>Delete post</dd>'
            . '<dt>post.update</dt><dd>Update post</dd>'
            . '</dl></td></tr>'
            . '</tbody></table>'
            . '</div></div>',
    self::USER_2
        );

        $this->assertSame($expected, $this->panel->getPanel());
    }

    #[Test]
    public function tabWithTabValue(): void
    {
        $this->panel = (new Panel(tabValue: fn (MockIdentity $identity) => $identity->getTabValue()))
            ->withContainer(self::$container)
        ;

        self::$container
            ->get(CurrentUser::class)
            ->login(new MockIdentity(self::USER_1))
        ;

        $expected = sprintf(<<<HTML
<span title="Current User"><svg
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
</svg><span class="tracy-label">User: %s</span></span>
HTML, MockIdentity::TAB_VALUE);

        $this->assertSame($expected, $this->panel->getTab());
    }

    #[Test]
    public function panelUsingId2pk(): void
    {
        $this->panel = (new Panel(id2pk: fn (string $id) => hex2bin($id)))
            ->withContainer(self::$container)
        ;

        self::$container
            ->get(CurrentUser::class)
            ->login(new MockIdentity(self::UUID))
        ;

        $expected = sprintf(
            "<h1>Current User</h1>\n"
            . '<div class="tracy-inner"><div class="tracy-inner-container">'
            . '<table><tbody>'
            . '<tr><th>ID</th><td>%s</td></tr>'
            . '<tr><th>Roles</th><td><dl>'
            . '<dt>user.manager</dt><dd>User Manager</dd>'
            . '</dl></td></tr>'
            . '<tr><th>Permissions</th><td><dl>'
            . '<dt>user.create</dt><dd>Create user</dd>'
            . '<dt>user.delete</dt><dd>Delete user</dd>'
            . '<dt>user.update</dt><dd>Update user</dd>'
            . '</dl></td></tr>'
            . '</tbody></table>'
            . '</div></div>',
            self::UUID
        );

        $this->assertSame($expected, $this->panel->getPanel());
    }

    #[Test]
    public function panelWithUserParameters(): void
    {
        $this->panel = (new Panel(
            userParameters: fn (MockIdentity $identity) => [
                'Parameter 1' => $identity->getParameter1(),
                'Parameter 2' => $identity->getParameter2(),
            ]
        ))
            ->withContainer(self::$container)
        ;

        self::$container
            ->get(CurrentUser::class)
            ->login(new MockIdentity(self::USER_1))
        ;

        $expected = sprintf(
            "<h1>Current User</h1>\n"
            . '<div class="tracy-inner"><div class="tracy-inner-container">'
            . '<table><tbody>'
            . '<tr><th>ID</th><td>%s</td></tr>'
            . '<tr><th>Parameter 1</th><td>parameter1</td></tr>'
            . '<tr><th>Parameter 2</th><td>parameter2</td></tr>'
            . '<tr><th>Roles</th><td>No roles assigned</td></tr>'
            . '<tr><th>Permissions</th><td>No permissions granted</td></tr>'
            . '</tbody></table>'
            . '</div></div>',
            self::USER_1
        );

        $this->assertSame($expected, $this->panel->getPanel());
    }
}