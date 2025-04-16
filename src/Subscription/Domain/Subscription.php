<?php

namespace DDD\Subscription\Domain;

use Brick\Money\Currency;
use Brick\Money\Money;
use DDD\Service\Random\RandomGenerator;
use DDD\Subscription\Domain\State\PendingState;
use DDD\Subscription\Domain\State\SubscriptionState;

final class Subscription
{
    public function __construct(
        public readonly string $id,
        public readonly string $userId,
        public readonly string $planId,
        public Period $period,
        public Money $basePrice,
        private SubscriptionState $state,
        private Money $fee,
    ) {
    }

    public static function create(string $userId, string $planId, Money $price, Period $period): self
    {
        return new self(
            RandomGenerator::UUID(),
            $userId,
            $planId,
            $period,
            $price,
            new PendingState(),
            Money::zero(Currency::of('BRL'))
        );
    }

    /**
     * This method allows the correct state transition by `SubscriptionState`
     *   - **Avoid use this outside that interface**
     *
     * @internal
     */
    public function setState(SubscriptionState $state): void
    {
        $this->state = $state;
    }

    public function activate(): void
    {
        $this->state->activate($this);
    }

    public function markPastDue(): void
    {
        $this->state->markPastDue($this);
    }

    public function suspend(): void
    {
        $this->state->suspend($this);
    }

    public function cancel(): void
    {
        $this->state->cancel($this);
    }

    public function expire(): void
    {
        $this->state->expire($this);
    }

    public function renew(): void
    {
        $this->state->renew($this);
    }
}
