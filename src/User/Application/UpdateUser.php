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
        $user = $this->repo->findById($input->id);
        if (!$user) {
            throw new \DomainException('user not found');
        }

        if ($input->email !== null) {
            $email = new Email($input->email);
            if (! $this->repo->isEmailUnique($email)) {
                throw new \DomainException('this email already exist');
            }
        }

        $newUser = new User(
            $user->id,
            $email ?? $user->email,
            $input->name ?? $user->name,
        );

        $this->repo->update($newUser);

        return new UpdateUserOutput(
            $newUser->id,
            $newUser->name,
            $newUser->email
        );
    }
}
