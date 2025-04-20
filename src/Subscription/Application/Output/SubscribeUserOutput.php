<?php

namespace DDD\Subscription\Application\Output;

use Brick\Money\Money;
use DDD\Subscription\Domain\Period;
use DDD\Subscription\Domain\State\SubscriptionState;
use DDD\Subscription\Domain\Subscription;

final readonly class SubscribeUserOutput
{
    public function __construct(
        public string $id,
        public string $userId,
        public string $planId,
        public Money $price,
        public Period $period,
        public SubscriptionState $state
    ) {
    }

    public static function from(Subscription $subscription): self
    {
        return new self(
            $subscription->id,
            $subscription->userId,
            $subscription->planId,
            $subscription->price,
            $subscription->period,
            $subscription->state(),
        );
    }
}
