<?php

namespace DDD\User\Application;

final class RegisterUser
{
    public function execute(RegisterUserInput $input): RegisterUserOutput
    {
        return new RegisterUserOutput();
    }
}

final readonly class RegisterUserInput
{
}

final readonly class RegisterUserOutput
{
}
