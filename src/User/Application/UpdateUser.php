<?php

namespace DDD\User\Application;

use DDD\User\Application\Input\UpdateUserInput;
use DDD\User\Application\Output\UpdateUserOutput;
use DDD\User\Domain\Email;
use DDD\User\Domain\User;
use DDD\User\Infra\Repository\UserRepositoryI;

final class UpdateUser
{
    public function __construct(
        public readonly UserRepositoryI $repo,
    ) {
    }

    public function execute(UpdateUserInput $input): UpdateUserOutput
    {
        $email = new Email($input->email);
        if ($this->repo->isEmailUnique($email)) {
            throw new \DomainException('this email already exist');
        }

        $user = $this->repo->findById($input->id);
        if (!$user) {
            throw new \DomainException('user not found');
        }

        $user->name = $input->name;
        $user->email = $email;

        $this->repo->update($user);

        return new UpdateUserOutput(
            $user->id,
            $user->name,
            $user->email
        );
    }
}
