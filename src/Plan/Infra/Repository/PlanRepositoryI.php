<?php

namespace DDD\Plan\Infra\Repository;

use DDD\Plan\Domain\Plan;
use DDD\User\Domain\User;

interface PlanRepositoryI
{
    public function save(Plan $plan): void;
    public function update(Plan $plan): void;
    public function findById(string $id): ?Plan;
}
