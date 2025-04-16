<?php

use DDD\User\Application\Input\RegisterUserInput;
use DDD\User\Application\Input\UpdateUserInput;
use DDD\User\Application\Output\RegisterUserOutput;
use DDD\User\Application\Output\UpdateUserOutput;
use DDD\User\Application\RegisterUser;
use DDD\User\Application\UpdateUser;
use DDD\User\Domain\Email;
use DDD\User\Domain\User;

describe('User creation', function () {
    it('should register a new user', function () {
        $repo = inMemoryRepository();
        $input = new RegisterUserInput(
            'Gustavo Oliveira',
            'gustavo@example.com'
        );

        $registerUser = new RegisterUser($repo->user);
        $output = $registerUser->execute($input);

        expect($output)->toBeInstanceOf(RegisterUserOutput::class);
        expect($output->name)->toBe('Gustavo Oliveira');
        expect($output->email->value)->toBe('gustavo@example.com');
    });

    it('should fail for invalid email format', function () {
        $repo = inMemoryRepository();
        $input = new RegisterUserInput(
            'Gustavo Oliveira',
            'gustavo.test.com'
        );

        $registerUser = new RegisterUser($repo->user);

        expect(fn () => $registerUser->execute($input))
            ->toThrow(\InvalidArgumentException::class);
    });

    it('should fail when email already exists', function () {
        $repo = inMemoryRepository();
        $input = new RegisterUserInput(
            'Gustavo Oliveira',
            'gustavo@example.com'
        );

        $registerUser = new RegisterUser($repo->user);
        // First registration should succeed.
        $registerUser->execute($input);

        expect(fn () => $registerUser->execute($input))
            ->toThrow(\DomainException::class);
    });
});

describe('User update', function () {
    it('should update the user data', function () {
        $repo = inMemoryRepository();
        $user = new User(
            '1234-asdf',
            new Email('gustavo@example.com'),
            'Gustavo Oliveira',
        );
        $repo->user->save($user);

        $input = new UpdateUserInput(
            '1234-asdf',
            'Gustavo Carvalho',
            'carvalho@example.com',
        );

        $updateUser = new UpdateUser($repo->user);
        $output = $updateUser->execute($input);

        expect($output)->toBeInstanceOf(UpdateUserOutput::class);
        expect($output->id)->toBe('1234-asdf');
        expect($output->name)->toBe('Gustavo Carvalho');
        expect($output->email->value)->toBe('carvalho@example.com');
    });

    it('should not update the user, because user don\'t exist', function () {
        $repo = inMemoryRepository();

        $input = new UpdateUserInput(
            '1234-asdf',
            'Gustavo Carvalho',
            'carvalho@example.com',
        );

        $updateUser = new UpdateUser($repo->user);
        expect(fn () => $updateUser->execute($input))
          ->toThrow(DomainException::class, 'user not found');
    });

    it('should fail for try insert a email that already exist', function () {
        $repo = inMemoryRepository();
        $user = new User(
            '1qw4-36aj',
            new Email('carvalho@example.com'),
            'Gustavo Carvalho',
        );
        $repo->user->save($user);

        $input = new UpdateUserInput(
            '1234-asdf',
            'Lucas Carvalho',
            'carvalho@example.com',
        );

        $updateUser = new UpdateUser($repo->user);
        expect(fn () => $updateUser->execute($input))
          ->toThrow(DomainException::class, 'this email is already exist');
    });

    it('should fail for update the email to the same one', function () {
        $repo = inMemoryRepository();
        $user = new User(
            '1234-asdf',
            new Email('gustavo@example.com'),
            'Gustavo Oliveira',
        );
        $repo->user->save($user);

        $input = new UpdateUserInput(
            '1234-asdf',
            'Gustavo Carvalho',
            'gustavo@example.com',
        );

        $updateUser = new UpdateUser($repo->user);
        expect(fn () => $updateUser->execute($input))
          ->toThrow(DomainException::class, 'this is the current user email');
    });
});