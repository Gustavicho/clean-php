<?php

final class User
{
    public function __construct(
        public readonly string $id,
        public Email $email,
        public string $name,
    ) {
    }
}

final class Email
{
    public function __construct(
        public readonly string $value
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if (filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('The email doesn\'t fit in the format');
        }
    }
}
