<?php

namespace DDD\User\Application;

final class UpdateUser
{
    public function execute(RegisterUserInput $input): UpdateUserOutput
    {
        return new UpdateUserOutput();
    }
}

final readonly class UpdateUserInput
{
}

final readonly class UpdateUserOutput
{
}
