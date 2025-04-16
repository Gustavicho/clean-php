<?php

namespace DDD\User\Domain;

final class Email
{
    public readonly string $value;

    public function __construct(string $value)
    {
        $cleaned = trim($value);
        if (!filter_var($cleaned, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('The email doesn\'t fit in the format');
        }

        $this->value = $cleaned;
    }
}
