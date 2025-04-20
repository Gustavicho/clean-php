<?php

use Brick\Money\Money;
use DDD\User\Domain\User;
use DDD\Bundle\Service\Time\TimeServiceFaker;
use DDD\Plan\Domain\Duration;
use DDD\Plan\Domain\Plan;
use DDD\Subscription\Application\CancelSubscription;
use DDD\Subscription\Application\ChangePlan;
use DDD\Subscription\Application\Input\CancelSubscriptionInput;
use DDD\Subscription\Application\Input\ChangePlanInput;
use DDD\Subscription\Application\RenewPlan;
use DDD\Subscription\Domain\State\CancelledState;
use DDD\Subscription\Domain\State\PendingState;
use DDD\Subscription\Domain\Subscription;
use DDD\User\Domain\Email;

describe('Canceling a subscription', function () {
    it('should cancel an active subscription', function () {
        $repo = inMemoryRepository();
        $time = new TimeServiceFaker(new \DateTimeImmutable('2025-02-20T10:00:00Z'));

        $user = User::create('Gustavo', new Email('gustavo@test.com'));

        $plan = Plan::create('test', Money::of(120, 'BRL'), Duration::fromString('1 month'));
        $period = $plan->duration->toPeriod($time->getTimeNow());
        $subscription = Subscription::create(
            $user->id,
            $plan->id,
            $plan->price,
            $period
        );

        $repo->user->save($user);
        $repo->plan->save($plan);
        $repo->subscription->save($subscription);

        $input = new CancelSubscriptionInput($user->id);
        $useCase = new CancelSubscription($repo->subscription, $repo->user, $repo->plan, $time);
        $output = $useCase->execute($input);

        expect($output->state)->toBeInstanceOf(CancelledState::class);
        expect($output->period->end)->toBe($subscription->period->end);
    });

    it('should fail if doesn\'t has a plan', function () {
        $repo = inMemoryRepository();
        $time = new TimeServiceFaker(new \DateTimeImmutable('2025-02-20T10:00:00Z'));
        $user = User::create('Gustavo', new Email('gustavo@test.com'));
        $repo->user->save($user);

        $input = new CancelSubscriptionInput($user->id);
        $useCase = new CancelSubscription($repo->subscription, $repo->user, $repo->plan, $time);

        expect(fn () => $useCase->execute($input))
            ->toThrow(\DomainException::class);
    });

    it('should fail if user does not exist', function () {
        $repo = inMemoryRepository();
        $time = new TimeServiceFaker(new \DateTimeImmutable('2025-02-20T10:00:00Z'));

        $input = new CancelSubscriptionInput('user-inexistente');
        $useCase = new CancelSubscription($repo->subscription, $repo->user, $repo->plan, $time);

        expect(fn () => $useCase->execute($input))
            ->toThrow(\DomainException::class);
    });

    it('should fail if subscription already canceled', function () {
        $repo = inMemoryRepository();
        $time = new TimeServiceFaker(new \DateTimeImmutable('2025-02-20T10:00:00Z'));

        $user = User::create('Gustavo', new Email('gustavo@test.com'));
        $plan = Plan::create('test', Money::of(120, 'BRL'), Duration::fromString('1 month'));
        $period = $plan->duration->toPeriod($time->getTimeNow());
        $subscription = Subscription::create(
            $user->id,
            $plan->id,
            $plan->price,
            $period,
        );
        $subscription->cancel();

        $repo->user->save($user);
        $repo->plan->save($plan);
        $repo->subscription->save($subscription);

        $input = new CancelSubscriptionInput($user->id);
        $useCase = new CancelSubscription($repo->subscription, $repo->user, $repo->repo, $time);

        expect(fn () => $useCase->execute($input))
            ->toThrow(\DomainException::class);
    });
});

describe('Changing plan', function () {
    it('should switch from one plan to another', function () {
        $repo = inMemoryRepository();
        $time = new TimeServiceFaker(new \DateTimeImmutable('2025-03-01T12:00:00Z'));

        $user = User::create('Ana', new Email('ana@test.com'));
        $planOld = Plan::create('basic', Money::of(50, 'BRL'), Duration::fromString('1 month'));
        $planNew = Plan::create('premium', Money::of(100, 'BRL'), Duration::fromString('1 month'));
        $period = $planOld->duration->toPeriod($time->getTimeNow());
        $subscription = Subscription::create(
            $user->id,
            $planOld->id,
            $planOld->price,
            $period,
        );

        $repo->user->save($user);
        $repo->plan->save($planOld);
        $repo->plan->save($planNew);
        $repo->subscription->save($subscription);

        $input = new ChangePlanInput($user->id, $planNew->id);
        $useCase = new ChangePlan($repo->subscription, $repo->user, $repo->repo, $time);
        $output = $useCase->execute($input);

        expect($repo->subscription->findById($subscription->id)->state())
            ->toBeInstanceOf(CancelledState::class);

        expect($output->planId)->toBe($planNew->id);
        expect($output->period->start)->toBe($time->getTimeNow());
        expect($output->state)->toBeInstanceOf(PendingState::class);
    });
});

describe('Renewing plan', function () {
    it('should renew an active subscription before expiry', function () {
        $repo = inMemoryRepository();
        $time = new TimeServiceFaker(new \DateTimeImmutable('2025-04-01T08:00:00Z'));

        $user = User::create('Bruno', new Email('bruno@test.com'));
        $plan = Plan::create('gold', Money::of(200, 'BRL'), Duration::fromString('1 month'));
        $period = $plan->duration->toPeriod($time->getTimeNow());
        $subscription = Subscription::create(
            $user->id,
            $plan->id,
            $plan->price,
            $period,
        );

        $time = new TimeServiceFaker(new \DateTimeImmutable('2025-04-29T08:00:00Z'));
        $repo->user->save($user);
        $repo->plan->save($plan);
        $repo->subscription->save($subscription);

        $useCase = new RenewPlan($repo->subscription, $repo->user, $repo->plan, $time);
        $output = $useCase->execute($user->id);

        expect($output->period->start->format('Y-m-d'))->toBe('2025-05-01');
        expect($output->period->end->format('Y-m-d'))->toBe('2025-06-01');
    });
});
