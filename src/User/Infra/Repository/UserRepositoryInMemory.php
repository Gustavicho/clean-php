<?php

namespace DDD\User\Infra\Repository;

use DDD\User\Domain\Email;
use DDD\User\Domain\User;

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

    public function findByEmail(Email $email): ?User
    {
        return $this->firstWhere(function (User $user) use ($email) {
            return $user->email == $email;
        });
    }

    public function update(User $user): void
    {
        foreach ($this->storage as $key => $existingUser) {
            if ($existingUser->id === $user->id) {
                $this->storage[$key] = $user;
                return;
            }
        }

        throw new \DomainException('User not found for update');
    }

    /** @return ArrayObject<User> */
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
