<?php

namespace DDD\User\Domain;

use DDD\Bundle\Service\Random\RandomGenerator;

final class User
{
    public string $name;
    public function __construct(
        public readonly string $id,
        public Email $email,
        string $name,
    ) {
        $cleaned = trim($name);
        if (mb_strlen($cleaned) < 3) {
            throw new \InvalidArgumentException('name must contains at least 3 chars');
        }

        $this->name = $cleaned;
    }

    public static function create(string $name, Email $email): self
    {
        return new self(
            RandomGenerator::UUID(),
            $email,
            $name,
        );
    }
}
