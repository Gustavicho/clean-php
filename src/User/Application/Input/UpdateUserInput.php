<?php

namespace DDD\User\Application\Input;

final readonly class UpdateUserInput
{
    public function __construct(
        public string $id,
        public ?string $name,
        public ?string $email
    ) {
    }
}
