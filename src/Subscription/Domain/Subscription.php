<?php

namespace DDD\Subscription\Domain;

use Brick\Money\Currency;
use Brick\Money\Money;
use DDD\Bundle\Service\Random\RandomGenerator;
use DDD\Subscription\Domain\State\PendingState;
use DDD\Subscription\Domain\State\SubscriptionState;

final class Subscription
{
    public function __construct(
        public readonly string $id,
        public readonly string $userId,
        public readonly string $planId,
        public readonly Money $price,
        public Period $period,
        private SubscriptionState $state,
        private Money $fee,
        private Money $discount
    ) {
    }

    public static function create(string $userId, string $planId, Money $price, Period $period): self
    {
        return new self(
            RandomGenerator::UUID(),
            $userId,
            $planId,
            $price,
            $period,
            new PendingState(),
            Money::zero(Currency::of('BRL')),
            Money::zero(Currency::of('BRL'))
        );
    }

    public function state(): SubscriptionState
    {
        return $this->state;
    }

    public function discount(): Money
    {
        return $this->discount();
    }

    public function fee(): Money
    {
        return $this->fee();
    }

    public function total(): Money
    {
        return $this->price->plus($this->fee)->minus($this->discount);
    }

    public function updateFee(Money $fee): self
    {
        if ($fee->isNegativeOrZero()) {
            throw new \DomainException('Can\'t aceppt value equal or less than Zero');
        }

        $this->fee = $fee;

        return $this;
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
