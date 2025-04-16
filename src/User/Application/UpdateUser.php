<?php

namespace DDD\User\Application;

use DDD\User\Application\Input\RegisterUserInput;
use DDD\User\Application\Output\UpdateUserOutput;

final class UpdateUser
{
    public function execute(RegisterUserInput $input): UpdateUserOutput
    {
        return new UpdateUserOutput();
    }
}
