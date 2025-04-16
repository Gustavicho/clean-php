<?php

namespace DDD\Subscription\Infra\Repository;

use DDD\Subscription\Domain\Subscription;

final class SubscriptionRepositoryInMemory implements SubscriptionRepositoryI
{
    public function __construct(
        /** @var ArrayObject<Subscription> */
        private \ArrayObject $storage = new \ArrayObject(),
    ) {
    }

    public function save(Subscription $subscription): void
    {
        $this->storage->append($subscription);
    }

    public function findById(string $id): ?Subscription
    {
        return $this->firstWhere(function (Subscription $subscription) use ($id) {
            return $subscription->id === $id;
        });
    }

    public function update(Subscription $subscription): void
    {
        foreach ($this->storage as $key => $existingSubscription) {
            if ($existingSubscription->id === $subscription->id) {
                $this->storage[$key] = $subscription;
                return;
            }
        }

        throw new \DomainException('User not found for update');
    }

    private function filter(\Closure $closure): \ArrayObject
    {
        $subscriptions = [];
        foreach ($this->storage->getIterator() as $subscription) {
            if ($closure($subscription)) {
                $subscriptions[] = $subscription;
            }
        }

        return new \ArrayObject($subscriptions);
    }

    private function firstWhere(\Closure $closure): ?Subscription
    {
        foreach ($this->storage->getIterator() as $subscription) {
            if ($closure($subscription)) {
                return $subscription;
            }
        }

        return null;
    }
}
