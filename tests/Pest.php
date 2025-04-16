<?php

use DDD\Plan\Infra\Repository\PlanRepositoryInMemory;
use DDD\Subscription\Infra\Repository\SubscriptionRepositoryInMemory;
use DDD\User\Infra\Repository\UserRepositoryInMemory;

/**
 * Returns an instance of an anonymous class containing in‑memory repository instances.
 *
 * This helper is useful in your tests to quickly access repository implementations
 * without coupling your tests to external persistence. Each call creates fresh repositories,
 * ensuring test isolation.
 *
 * @return object{user: UserRepositoryInMemory, plan: PlanRepositoryInMemory, subscription: SubscriptionRepositoryInMemory}
 */
function inMemoryRepository()
{
    return new class () {
        public readonly UserRepositoryInMemory $user;
        public readonly PlanRepositoryInMemory $plan;
        public readonly SubscriptionRepositoryInMemory $subscription;

        public function __construct()
        {
            $this->user = new UserRepositoryInMemory();
            $this->plan = new PlanRepositoryInMemory();
            $this->subscription = new SubscriptionRepositoryInMemory();
        }
    };
}
