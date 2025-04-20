<?php

namespace DDD\Subscription\Application;

use DDD\Bundle\Service\Time\TimeServiceI;
use DDD\Subscription\Infra\Repository\SubscriptionRepositoryI;
use DDD\User\Infra\Repository\UserRepositoryI;

final class ExpireSubscription
{
    public function __construct(
        private readonly SubscriptionRepositoryI $repo,
        private readonly UserRepositoryI $userRepo,
        private readonly TimeServiceI $time,
    ) {
    }

    public function execute(string $userId): string
    {
        $user = $this->userRepo->findById($userId)
          ?? throw new \DomainException('user not found');

        $subscription = $this->repo->findByUser($user)
          ?? throw new \DomainException('user don\'t has a subscribtion');

        if ($subscription->period->isInPeriod($this->time->getTimeNow())) {
            throw new \DomainException('can\'t expire a valid subscription');
        }

        $subscription->expire();
        $this->repo->update($subscription);

        return 'plan is expired';
    }
}
