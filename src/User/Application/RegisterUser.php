<?php

namespace DDD\User\Application;

use DDD\User\Application\Input\RegisterUserInput;
use DDD\User\Application\Output\RegisterUserOutput;
use DDD\User\Domain\Email;
use DDD\User\Domain\User;
use DDD\User\Infra\Repository\UserRepositoryI;

final class RegisterUser
{
    public function __construct(
        private readonly UserRepositoryI $repo,
    ) {
    }

    public function execute(RegisterUserInput $input): RegisterUserOutput
    {
        $email = new Email($input->email);
        if (! $this->repo->isEmailUnique($email)) {
            throw new \DomainException('This email already exist');
        }

        $user = User::create(
            $input->name,
            $email,
        );
        $this->repo->save($user);

        return new RegisterUserOutput(
            $user->id,
            $user->name,
            $user->email,
        );
    }
}
