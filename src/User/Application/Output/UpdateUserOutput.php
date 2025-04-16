<?php

namespace DDD\User\Application\Output;

use DDD\User\Domain\Email;

final readonly class UpdateUserOutput
{
    public function __construct(
        public string $id,
        public string $name,
        public Email $email
    ) {
    }
}
