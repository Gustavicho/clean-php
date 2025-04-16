<?php

use Brick\Money\Money;
use DDD\Plan\Application\CreatePlan;
use DDD\Plan\Application\Input\CreatePlanInput;
use DDD\Plan\Application\Input\UpdatePlanInput;
use DDD\Plan\Application\UpdatePlan;
use DDD\Plan\Domain\Duration;
use DDD\Plan\Domain\Plan;

describe('create plan', function () {
    it('should create a plan with valid data', function () {
        $repo = inMemoryRepository();

        $input = new CreatePlanInput(
            'Basic Plan',
            49.90,
            '3 months'
        );

        $useCase = new CreatePlan($repo->plan);
        $output = $useCase->execute($input);

        expect($output->id)->not->toBeEmpty();
        expect($repo->plan->findById($output->id))->not->toBeNull();
    });

    it('should not allow creating a plan with empty name', function () {
        $repo = inMemoryRepository();

        $input = new CreatePlanInput(
            '',
            49.90,
            '3 months'
        );

        $useCase = new CreatePlan($repo->plan);

        expect(fn () => $useCase->execute($input))
            ->toThrow(InvalidArgumentException::class);
    });

    it('should not allow creating a plan with zero or negative price', function () {
        $repo = inMemoryRepository();

        $input = new CreatePlanInput(
            name: 'Zero Plan',
            price: 0.00,
            duration: '1 month'
        );

        $useCase = new CreatePlan($repo->plan);

        expect(fn () => $useCase->execute($input))
            ->toThrow(InvalidArgumentException::class);
    });

    it('should not allow creating a plan with invalid duration', function () {
        $repo = inMemoryRepository();

        $input = new CreatePlanInput(
            'Invalid Duration Plan',
            29.90,
            '0 months'
        );

        $useCase = new CreatePlan($repo->plan);

        expect(fn () => $useCase->execute($input))
            ->toThrow(InvalidArgumentException::class);
    });
});

describe('update plan', function () {
    it('should update the plan name', function () {
        $repo = inMemoryRepository();

        $plan = Plan::create('Starter Plan', Money::of(49.9, 'BRL'), Duration::fromString('3 months'));
        $repo->plan->save($plan);

        $input = new UpdatePlanInput(
            id: $plan->id,
            name: 'Updated Plan',
            price: null,
            duration: null
        );

        $useCase = new UpdatePlan($repo->plan);
        $useCase->execute($input);

        $updated = $repo->plan->findById($plan->id);
        expect($updated->name)->toBe('Updated Plan');
    });

    it('should fail to update non-existent plan', function () {
        $repo = inMemoryRepository();

        $input = new UpdatePlanInput(
            id: 'non-existent-id',
            name: 'Any Plan',
            price: null,
            duration: null
        );

        $useCase = new UpdatePlan($repo->plan);

        expect(fn () => $useCase->execute($input))
            ->toThrow(DomainException::class);
    });

    it('should not allow setting empty name during update', function () {
        $repo = inMemoryRepository();

        $plan = Plan::create('Starter Plan', Money::of(49.9, 'BRL'), Duration::fromString('3 months'));
        $repo->plan->save($plan);

        $input = new UpdatePlanInput(
            id: $plan->id,
            name: '',
            price: null,
            duration: null
        );

        $useCase = new UpdatePlan($repo->plan);

        expect(fn () => $useCase->execute($input))
            ->toThrow(InvalidArgumentException::class);
    });
});
