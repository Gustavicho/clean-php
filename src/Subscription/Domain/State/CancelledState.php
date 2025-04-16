<?php

namespace DDD\Subscription\Domain\State;

use DDD\Subscription\Domain\State\SubscriptionState;
use DDD\Subscription\Domain\Subscription;

/**
 * Cancelled state: subscription cancelled by user.
 * Conditions:
 *  - Terminal state: no further transitions allowed.
 */
final class CancelledState implements SubscriptionState
{
    /**
     * @inheritdoc
     */
    public function activate(Subscription $subscription): void
    {
        throw new \DomainException('Cannot activate a cancelled subscription.');
    }

    /**
     * @inheritdoc
     */
    public function markPastDue(Subscription $subscription): void
    {
        throw new \DomainException('Cannot mark a cancelled subscription as past due.');
    }

    /**
     * @inheritdoc
     */
    public function suspend(Subscription $subscription): void
    {
        throw new \DomainException('Cannot suspend a cancelled subscription.');
    }

    /**
     * @inheritdoc
     */
    public function cancel(Subscription $subscription): void
    {
        throw new \DomainException('Subscription is already cancelled.');
    }

    /**
     * @inheritdoc
     */
    public function expire(Subscription $subscription): void
    {
        throw new \DomainException('Cannot expire a cancelled subscription.');
    }

    /**
     * @inheritdoc
     */
    public function renew(Subscription $subscription): void
    {
        throw new \DomainException('Cannot renew a cancelled subscription.');
    }
}
