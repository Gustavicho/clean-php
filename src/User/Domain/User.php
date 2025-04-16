<?php

namespace DDD\User\Domain;

use function DDD\Bundle\generateRandomUUID;

final class User
{
    public function __construct(
        public readonly string $id,
        public Email $email,
        public string $name,
    ) {
    }

    public static function create(string $name, Email $email): self
    {
        return new self(
            generateRandomUUID(),
            $email,
            $name,
        );
    }
}
