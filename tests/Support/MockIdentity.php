<?php

declare(strict_types=1);

namespace BeastBytes\Yii\Tracy\Panel\User\Tests\Support;

use Yiisoft\Auth\IdentityInterface;

class MockIdentity implements IdentityInterface
{
    public const TAB_VALUE = 'MI';

    public function __construct(private readonly ?string $id = null)
    {
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getParameter1(): string
    {
        return 'parameter1';
    }

    public function getParameter2(): string
    {
        return 'parameter2';
    }

    public function getTabValue(): string
    {
        return self::TAB_VALUE;
    }
}