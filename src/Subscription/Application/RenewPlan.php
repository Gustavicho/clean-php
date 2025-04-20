<?php

namespace DDD\Subscription\Application;

use DDD\Subscription\Application\Output\RenewPlanOutput;
use DDD\Subscription\Infra\Repository\SubscriptionRepositoryI;
use DDD\User\Infra\Repository\UserRepositoryI;

final class RenewPlan
{
    public function __construct(
        private readonly SubscriptionRepositoryI $repo,
        private readonly UserRepositoryI $userRepo,
    ) {
    }

    public function execute(string $userId): RenewPlanOutput
    {
        $user = $this->userRepo->findById($userId)
          ?? throw new \DomainException('user not found');

        $subscription = $this->repo->findByUser($user)
          ?? throw new \DomainException('user don\'t has a subscribtion');

        $subscription->renew();
        $this->repo->update($subscription);

        // TODO: dispatch event -> waiting payment confimation

        return new RenewPlanOutput(
            $subscription->id,
            $subscription->userId,
            $subscription->planId,
            $subscription->price,
            $subscription->period,
            $subscription->state(),
        );
    }
}
