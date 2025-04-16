<?php

namespace DDD\User\Application\Input;

final readonly class RegisterUserInput
{
    public function __construct(
        public string $name,
        public string $email,
    ) {
    }
}
