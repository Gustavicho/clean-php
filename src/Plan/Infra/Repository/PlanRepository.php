<?php

namespace DDD\Plan\Infra\Repository;

use DDD\Plan\Domain\Plan;

interface PlanRepositoryI
{
    public function save(Plan $plan): void;
    public function findById(string $id): ?Plan;
}

final class PlanRepositoryInMemory implements PlanRepositoryI
{
    public function __construct(
        /** @var ArrayObject<Plan> */
        private \ArrayObject $storage = new \ArrayObject(),
    ) {
    }

    public function save(Plan $plan): void
    {
        $this->storage->append($plan);
    }

    public function findById(string $id): ?Plan
    {
        return $this->firstWhere(function (Plan $plan) use ($id) {
            return $plan->id === $id;
        });
    }

    private function filter(\Closure $closure): \ArrayObject
    {
        $plans = [];
        foreach ($this->storage->getIterator() as $plan) {
            if ($closure($plan)) {
                $plans[] = $plan;
            }
        }

        return new \ArrayObject($plans);
    }

    private function firstWhere(\Closure $closure): ?Plan
    {
        foreach ($this->storage->getIterator() as $plan) {
            if ($closure($plan)) {
                return $plan;
            }
        }

        return null;
    }
}
