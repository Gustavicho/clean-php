<?php

namespace DDD\Subscription\Domain\State;

use DDD\Subscription\Domain\Subscription;
use DomainException;

/**
 * Initial state: subscription created but not yet paid.
 * Conditions:
 *  - Allowed transitions: activate(), cancel()
 *  - Disallowed: markPastDue(), suspend(), expire(), renew()
 */
final class PendingState implements SubscriptionState
{
    /**
     * @inheritdoc
     */
    public function activate(Subscription $subscription): void
    {
        $subscription->setState(new ActiveState());
    }

    /**
     * @inheritdoc
     */
    public function markPastDue(Subscription $subscription): void
    {
        throw new DomainException('Cannot mark pending subscription as past due.');
    }

    /**
     * @inheritdoc
     */
    public function suspend(Subscription $subscription): void
    {
        throw new DomainException('Cannot suspend a pending subscription.');
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
        throw new DomainException('Pending subscription cannot expire before activation.');
    }

    /**
     * @inheritdoc
     */
    public function renew(Subscription $subscription): void
    {
        throw new DomainException('Cannot renew a subscription that has not been activated.');
    }
}
