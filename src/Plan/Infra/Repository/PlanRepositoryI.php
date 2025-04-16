<?php

namespace DDD\Plan\Infra\Repository;

use DDD\Plan\Domain\Plan;

interface PlanRepositoryI
{
    public function save(Plan $plan): void;
    public function findById(string $id): ?Plan;
}
