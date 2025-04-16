<?php

use DDD\User\Application\Input\RegisterUserInput;
use DDD\User\Application\Output\RegisterUserOutput;
use DDD\User\Application\RegisterUser;

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
