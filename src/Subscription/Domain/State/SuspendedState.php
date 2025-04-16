<?php

namespace DDD\Subscription\Domain\State;

use DDD\Subscription\Domain\State\SubscriptionState;
use DDD\Subscription\Domain\Subscription;

/**
 * Suspended state: subscription suspended after excessive past due.
 * Conditions:
 *  - Allowed transitions: renew(), cancel()
 *  - Disallowed: activate(), markPastDue(), suspend(), expire()
 */
final class SuspendedState implements SubscriptionState
{
    /**
     * @inheritdoc
     */
    public function activate(Subscription $subscription): void
    {
        throw new \DomainException('Cannot activate a suspended subscription.');
    }

    /**
     * @inheritdoc
     */
    public function markPastDue(Subscription $subscription): void
    {
        throw new \DomainException('Cannot mark suspended subscription as past due.');
    }

    /**
     * @inheritdoc
     */
    public function suspend(Subscription $subscription): void
    {
        throw new \DomainException('Subscription is already suspended.');
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
        throw new \DomainException('Suspended subscription cannot expire directly.');
    }

    /**
     * @inheritdoc
     */
    public function renew(Subscription $subscription): void
    {
        $subscription->setState(new RenewingState());
    }
}
