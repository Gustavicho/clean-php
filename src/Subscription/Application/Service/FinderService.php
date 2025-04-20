<?php

namespace DDD\Subscription\Application\Service;

use DDD\Subscription\Domain\Subscription;
use DDD\Subscription\Infra\Repository\SubscriptionRepositoryI;
use DDD\User\Infra\Repository\UserRepositoryI;

final class FinderService implements FinderServiceI
{
    public function __construct(
        private readonly SubscriptionRepositoryI $repo,
        private readonly UserRepositoryI $userRepo,
    ) {
    }

    public function findSubscriptionByUserId(string $userId): Subscription
    {
        $user = $this->userRepo->findById($userId)
          ?? throw new \DomainException('user not found');

        $subscription = $this->repo->findByUser($user)
          ?? throw new \DomainException('user don\'t has a subscription');

        return $subscription;
    }
}
