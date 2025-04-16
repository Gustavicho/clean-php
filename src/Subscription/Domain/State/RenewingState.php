<?php

namespace DDD\Subscription\Domain\State;

use DDD\Subscription\Domain\State\SubscriptionState;
use DDD\Subscription\Domain\Subscription;

/**
 * Renewing state: subscription renewal process in progress.
 * Conditions:
 *  - Allowed transitions: activate(), cancel(), expire()
 *  - Disallowed: markPastDue(), suspend(), renew()
 */
final class RenewingState implements SubscriptionState
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
        throw new \DomainException('Cannot mark renewing subscription as past due.');
    }

    /**
     * @inheritdoc
     */
    public function suspend(Subscription $subscription): void
    {
        throw new \DomainException('Cannot suspend subscription during renewal.');
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
        throw new \DomainException('Subscription is already in renewing state.');
    }
}
