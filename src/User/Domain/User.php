<?php

namespace DDD\User\Domain;

final class User
{
    public function __construct(
        public readonly string $id,
        public Email $email,
        public string $name,
    ) {
    }
}
