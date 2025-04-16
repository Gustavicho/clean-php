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
    it('creates a user with valid name and email', function () {
        $repo = inMemoryRepository();
        $input = new RegisterUserInput('Gustavo Oliveira', 'gustavo@example.com');

        $output = (new RegisterUser($repo->user))->execute($input);

        expect($output)->toBeInstanceOf(RegisterUserOutput::class);
        expect($output->name)->toBe('Gustavo Oliveira');
        expect($output->email->value)->toBe('gustavo@example.com');
    });

    it('trims whitespace from name and email before validation', function () {
        $repo = inMemoryRepository();
        $input = new RegisterUserInput("  Ana Silva  ", "  ana@teste.com  ");

        $output = (new RegisterUser($repo->user))->execute($input);

        expect($output->name)->toBe('Ana Silva');
        expect($output->email->value)->toBe('ana@teste.com');
    });

    it('fails when email already exists', function () {
        $repo = inMemoryRepository();
        $input = new RegisterUserInput('Gustavo', 'gustavo@dup.com');

        (new RegisterUser($repo->user))->execute($input);

        expect(
            fn () => (new RegisterUser($repo->user))->execute($input)
        )->toThrow(DomainException::class, 'This email already exist');
    });
});

describe('User update', function () {
    beforeEach(function () {
        $this->repo = inMemoryRepository();
        $user = new User('id-1', new Email('orig@example.com'), 'Original Name');
        $this->repo->user->save($user);
    });

    it('updates both name and email when provided', function () {
        $input = new UpdateUserInput('id-1', 'New Name', 'new@example.com');

        $output = (new UpdateUser($this->repo->user))->execute($input);

        expect($output)->toBeInstanceOf(UpdateUserOutput::class)
            ->and($output->id)->toBe('id-1')
            ->and($output->name)->toBe('New Name')
            ->and($output->email->value)->toBe('new@example.com');
    });

    it('updates only the name when email is null', function () {
        $input = new UpdateUserInput('id-1', 'Solo Name', null);

        $output = (new UpdateUser($this->repo->user))->execute($input);

        expect($output->name)->toBe('Solo Name');
        expect($output->email->value)->toBe('orig@example.com');
    });

    it('updates only the email when name is null', function () {
        $input = new UpdateUserInput('id-1', null, 'solo@example.com');

        $output = (new UpdateUser($this->repo->user))->execute($input);

        expect($output->name)->toBe('Original Name');
        expect($output->email->value)->toBe('solo@example.com');
    });

    it('fails for invalid email format on update', function () {
        $input = new UpdateUserInput('id-1', 'Name Test', 'bad-email@@');

        expect(fn () => (new UpdateUser($this->repo->user))->execute($input))
            ->toThrow(InvalidArgumentException::class);
    });

    it('fails when updating non-existent user', function () {
        $input = new UpdateUserInput('no-id', 'Name', 'no@user.com');

        expect(fn () => (new UpdateUser($this->repo->user))->execute($input))
            ->toThrow(DomainException::class, 'user not found');
    });

    it('fails when new email collides with another user', function () {
        $other = new User('id-2', new Email('taken@example.com'), 'Other');
        $this->repo->user->save($other);

        $input = new UpdateUserInput('id-1', 'Name', 'taken@example.com');

        expect(fn () => (new UpdateUser($this->repo->user))->execute($input))
            ->toThrow(DomainException::class, 'this email already exist');
    });
});
