<?php

namespace DDD\User\Infra\Repository;

use User;

interface UserRepositoryI
{
    public function save(User $user): void;
    public function findById(string $id): ?User;
}

final class UserRepositoryInMemory implements UserRepositoryI
{
    public function __construct(
        /** @var ArrayObject<User> */
        private \ArrayObject $storage = new \ArrayObject(),
    ) {
    }

    public function save(User $user): void
    {
        $this->storage->append($user);
    }

    public function findById(string $id): ?User
    {
        return $this->firstWhere(function (User $user) use ($id) {
            return $user->id === $id;
        });
    }

    private function filter(\Closure $closure): \ArrayObject
    {
        $users = [];
        foreach ($this->storage->getIterator() as $user) {
            if ($closure($user)) {
                $users[] = $user;
            }
        }

        return new \ArrayObject($users);
    }

    private function firstWhere(\Closure $closure): ?User
    {
        foreach ($this->storage->getIterator() as $user) {
            if ($closure($user)) {
                return $user;
            }
        }

        return null;
    }
}
