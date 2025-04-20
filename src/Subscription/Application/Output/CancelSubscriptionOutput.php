<?php

namespace DDD\Subscription\Application\Output;

use Brick\Money\Money;
use DDD\Subscription\Domain\Period;
use DDD\Subscription\Domain\State\SubscriptionState;

final readonly class CancelSubscriptionOutput
{
    public function __construct(
        public string $id,
        public string $userId,
        public string $planId,
        public Money $price,
        public Period $period,
        public SubscriptionState $state,
        public Money $fee,
    ) {
    }
}
