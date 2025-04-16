<?php

namespace DDD\Subscription\Domain\State;

use DDD\Subscription\Domain\Subscription;

interface SubscriptionState
{
    /**
     * Activate the subscription.
     *
     * Should be called when the subscription has been paid and is now considered valid.
     * Only valid from the 'Pending', 'Renewing', or possibly 'PastDue' states.
     *
     * @param Subscription $subscription The subscription being modified.
     * @throws \DomainException If the state does not allow this transition.
     */
    public function activate(Subscription $subscription): void;

    /**
     * Mark the subscription as Past Due.
     *
     * Should be called when the current date is past the subscription's end date,
     * and the user hasn't renewed or paid to extend the subscription.
     *
     * @param Subscription $subscription
     * @throws \DomainException If not allowed in the current state.
     */
    public function markPastDue(Subscription $subscription): void;

    /**
     * Suspend the subscription.
     *
     * Should be called when a subscription has been past due for a specific grace period
     * and should no longer grant access to services until resolved.
     *
     * @param Subscription $subscription
     * @throws \DomainException If not allowed in the current state.
     */
    public function suspend(Subscription $subscription): void;

    /**
     * Cancel the subscription.
     *
     * Triggered when the user chooses to cancel their subscription manually.
     * Can typically be called from any state, except already Cancelled or Expired.
     *
     * @param Subscription $subscription
     * @throws \DomainException If not allowed in the current state.
     */
    public function cancel(Subscription $subscription): void;

    /**
     * Expire the subscription.
     *
     * Called when a subscription period ends naturally and is not renewed.
     * This should be triggered by a domain event or scheduled job.
     *
     * @param Subscription $subscription
     * @throws \DomainException If not allowed in the current state.
     */
    public function expire(Subscription $subscription): void;

    /**
     * Begin renewing the subscription.
     *
     * Called when a user initiates a renewal process (e.g., submitting a payment).
     * This may transition the state to 'Renewing' while awaiting confirmation.
     *
     * @param Subscription $subscription
     * @throws \DomainException If not allowed in the current state.
     */
    public function renew(Subscription $subscription): void;
}
