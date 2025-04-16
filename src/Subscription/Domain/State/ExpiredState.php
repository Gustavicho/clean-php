<?php

namespace DDD\Subscription\Domain\State;

use DDD\Subscription\Domain\State\SubscriptionState;
use DDD\Subscription\Domain\Subscription;

/**
 * Expired state: subscription period ended without renewal.
 * Conditions:
 *  - Allowed transitions: renew()
 *  - Disallowed: activate(), markPastDue(), suspend(), cancel(), expire()
 */
final class ExpiredState implements SubscriptionState
{
    /**
     * @inheritdoc
     */
    public function activate(Subscription $subscription): void
    {
        throw new \DomainException('Cannot activate an expired subscription.');
    }

    /**
     * @inheritdoc
     */
    public function markPastDue(Subscription $subscription): void
    {
        throw new \DomainException('Cannot mark expired subscription as past due.');
    }

    /**
     * @inheritdoc
     */
    public function suspend(Subscription $subscription): void
    {
        throw new \DomainException('Cannot suspend an expired subscription.');
    }

    /**
     * @inheritdoc
     */
    public function cancel(Subscription $subscription): void
    {
        throw new \DomainException('Cannot cancel an expired subscription.');
    }

    /**
     * @inheritdoc
     */
    public function expire(Subscription $subscription): void
    {
        throw new \DomainException('Subscription is already expired.');
    }

    /**
     * @inheritdoc
     */
    public function renew(Subscription $subscription): void
    {
        $subscription->setState(new RenewingState());
    }
}
