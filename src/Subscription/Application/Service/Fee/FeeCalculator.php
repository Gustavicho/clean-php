<?php

namespace DDD\Subscription\Application\Service\Fee;

use DDD\Subscription\Domain\Subscription;
use Brick\Money\Money;
use DDD\Bundle\Service\Time\TimeServiceI;
use DDD\Subscription\Domain\State\ExpiredState;
use DDD\Subscription\Domain\State\PastDueState;
use DDD\Subscription\Domain\State\SuspendedState;

final class FeeCalculator implements FeeCalculatorI
{
    private const APPLYABLE_FEE = [
      ExpiredState::class, PastDueState::class, SuspendedState::class
    ];

    public function __construct(
        private readonly TimeServiceI $time
    ) {
    }

    public function applyLateFee(Subscription $subscription, Money $baseFee): Subscription
    {
        if (! $this->canApplyFeeOnSubscription($subscription)) {
            throw new \DomainException('can\t apply fee on the current subscription');
        }

        $daysOfPastDue = $subscription->period->end->diff(
            $this->time->getTimeNow()
        )->days;

        $newFee = $baseFee->multipliedBy($daysOfPastDue);

        return $subscription->updateFee($newFee);
    }

    private function canApplyFeeOnSubscription(Subscription $subscription): bool
    {
        $sub = new \ReflectionClass($subscription->state());
        return in_array(($sub->getNamespaceName()), self::APPLYABLE_FEE)
          || $subscription->period->isOutPeriod($this->time->getTimeNow());
    }
}
