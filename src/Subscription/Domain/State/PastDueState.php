<?php

namespace DDD\Subscription\Domain\State;

use DDD\Subscription\Domain\State\SubscriptionState;
use DDD\Subscription\Domain\Subscription;

/**
 * PastDue state: subscription end date passed without payment.
 * Conditions:
 *  - Allowed transitions: suspend(), renew(), cancel()
 *  - Disallowed: activate(), markPastDue(), expire()
 */
final class PastDueState implements SubscriptionState
{
    /**
     * @inheritdoc
     */
    public function activate(Subscription $subscription): void
    {
        throw new \DomainException('Cannot activate a past due subscription.');
    }

    /**
     * @inheritdoc
     */
    public function markPastDue(Subscription $subscription): void
    {
        throw new \DomainException('Subscription is already past due.');
    }

    /**
     * @inheritdoc
     */
    public function suspend(Subscription $subscription): void
    {
        $subscription->setState(new SuspendedState());
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
        throw new \DomainException('Past due subscription cannot expire directly.');
    }

    /**
     * @inheritdoc
     */
    public function renew(Subscription $subscription): void
    {
        $subscription->setState(new RenewingState());
    }
}
