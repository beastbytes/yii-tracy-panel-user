<?php

declare(strict_types=1);

namespace BeastBytes\Yii\Tracy\Panel\User\Tests\Support;

use Yiisoft\Auth\IdentityInterface;
use Yiisoft\Auth\IdentityRepositoryInterface;

final class MockIdentityRepository implements IdentityRepositoryInterface
{
    public function __construct(private readonly IdentityInterface $identity)
    {
    }

    public function findIdentity(string $id): ?IdentityInterface
    {
        return $this->identity->getId() === $id ? $this->identity : null;
    }
}