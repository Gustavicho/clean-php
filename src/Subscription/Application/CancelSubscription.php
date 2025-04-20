<?php

namespace DDD\Subscription\Application;

use DDD\Subscription\Application\Input\CancelSubscriptionInput;
use DDD\Subscription\Application\Output\CancelSubscriptionOutput;
use DDD\Subscription\Infra\Repository\SubscriptionRepositoryI;
use DDD\User\Infra\Repository\UserRepositoryI;

final class CancelSubscription
{
    public function __construct(
        private readonly SubscriptionRepositoryI $repo,
        private readonly UserRepositoryI $userRepo,
    ) {
    }

    public function execute(CancelSubscriptionInput $input): CancelSubscriptionOutput
    {
        $user = $this->userRepo->findById($input->userId)
            ?? throw new \DomainException('User not found');
        $subscription = $this->repo->findByUser($user)
            ?? throw new \DomainException('The user doest has a subscription');

        $subscription->cancel();
        $this->repo->update($subscription);

        return new CancelSubscriptionOutput(
            $subscription->id,
            $subscription->userId,
            $subscription->planId,
            $subscription->price,
            $subscription->period,
            $subscription->state(),
            $subscription->fee(),
        );
    }
}