<?php

use DDD\Plan\Infra\Repository\PlanRepositoryInMemory;
use DDD\Subscription\Infra\Repository\SubscriptionRepositoryInMemory;
use DDD\User\Infra\Repository\UserRepositoryInMemory;

beforeAll(function () {
    $this->planRepository = new PlanRepositoryInMemory();
    $this->SubscriptionRepository = new SubscriptionRepositoryInMemory();
    $this->userRepository = new UserRepositoryInMemory();
});
