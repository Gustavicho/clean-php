<?php

namespace DDD\User\Infra\Repository;

use DDD\User\Domain\Email;
use DDD\User\Domain\User;

interface UserRepositoryI
{
    public function save(User $user): void;
    public function update(User $user): void;
    public function findById(string $id): ?User;
    public function findByEmail(Email $email): ?User;
    public function isEmailUnique(Email $email): bool;
}
