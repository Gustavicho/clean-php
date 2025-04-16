<?php

namespace DDD\Subscription\Domain\State;

use DDD\Subscription\Domain\State\SubscriptionState;
use DDD\Subscription\Domain\Subscription;

/**
 * Active state: subscription is paid and within valid period.
 * Conditions:
 *  - Allowed transitions: markPastDue(), renew(), cancel(), expire()
 *  - Disallowed: activate(), suspend()
 */
final class ActiveState implements SubscriptionState
{
    /**
     * @inheritdoc
     */
    public function activate(Subscription $subscription): void
    {
        throw new \DomainException('Subscription is already active.');
    }

    /**
     * @inheritdoc
     */
    public function markPastDue(Subscription $subscription): void
    {
        $subscription->setState(new PastDueState());
    }

    /**
     * @inheritdoc
     */
    public function suspend(Subscription $subscription): void
    {
        throw new \DomainException('Cannot suspend an active subscription without past due.');
    }

    /**
     * @inheritdoc
     */
    public function cancel(Subscription $subscription): void
    {
        $subscription->setState(new CancelledState());
    }

    /**
     * @inheritdoc
     */
    public function expire(Subscription $subscription): void
    {
        $subscription->setState(new ExpiredState());
    }

    /**
     * @inheritdoc
     */
    public function renew(Subscription $subscription): void
    {
        $subscription->setState(new RenewingState());
    }
}
