<?php

namespace DDD\Subscription\Application\Service;

use DDD\Subscription\Domain\Subscription;

interface FinderServiceI
{
    public function findSubscriptionByUserId(string $userId): Subscription;
}
