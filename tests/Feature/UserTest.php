<?php

use DDD\User\Application\Input\RegisterUserInput;
use DDD\User\Application\RegisterUser;

describe('User craetion related test', function () {
    it('Should register a new User', function () {
        $repo = inMemoryRepository();
        $input = new RegisterUserInput(
            'Gustavo Oliveira',
            'gustavo@test.com',
        );

        $registerUser = new RegisterUser($repo->user);
        $output = $registerUser->execute($input);

        expect($output)->toBeReadonly();
    });

    it('Should fail for invalid Email format', function () {
        $repo = inMemoryRepository();
        $input = new RegisterUserInput(
            'Gustavo Oliveira',
            'gustavo.test.com',
        );

        $registerUser = new RegisterUser($repo->user);
        expect(
            $registerUser->execute($input)
        )->toThrow(\InvalidArgumentException::class);
    });

    it('Should fail for Email already exist', function () {
        $repo = inMemoryRepository();
        $input = new RegisterUserInput(
            'Gustavo Oliveira',
            'gustavo.test.com',
        );

        $registerUser = new RegisterUser($repo->user);
        $registerUser->execute($input);

        expect(
            $registerUser->execute($input)
        )->toThrow(\DomainException::class);
    });
});

test('Should update the user data', function () {

});
