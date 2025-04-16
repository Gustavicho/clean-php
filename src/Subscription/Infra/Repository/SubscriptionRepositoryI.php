<?php

namespace DDD\Subscription\Infra\Repository;

use DDD\Subscription\Domain\Subscription;

interface SubscriptionRepositoryI
{
    public function save(Subscription $subscription): void;
    public function findById(string $id): ?Subscription;
    public function update(Subscription $subscription): void;
}
