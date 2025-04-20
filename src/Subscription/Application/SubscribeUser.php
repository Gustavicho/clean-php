<?php

namespace DDD\Subscription\Application;

use DDD\Plan\Infra\Repository\PlanRepositoryI;
use DDD\Bundle\Service\Time\TimeServiceI;
use DDD\Subscription\Application\Input\SubscribeUserInput;
use DDD\Subscription\Application\Output\SubscribeUserOutput;
use DDD\Subscription\Domain\Subscription;
use DDD\Subscription\Infra\Repository\SubscriptionRepositoryI;
use DDD\User\Infra\Repository\UserRepositoryI;

final class SubscribeUser
{
    public function __construct(
        private readonly SubscriptionRepositoryI $repo,
        private readonly PlanRepositoryI $planRepo,
        private readonly UserRepositoryI $userRepo,
        private readonly TimeServiceI $time,
    ) {
    }

    public function execute(SubscribeUserInput $input): SubscribeUserOutput
    {
        $user = $this->userRepo->findById($input->userId)
            ?? throw new \DomainException('User not found');
        $plan = $this->planRepo->findById($input->planId)
            ?? throw new \DomainException('Plan not found');

        $period = $plan->duration->toPeriod(
            $this->time->getTimeNow()
        );
        $subscription = Subscription::create(
            $user->id,
            $plan->id,
            $plan->price,
            $period,
        );

        return SubscribeUserOutput::from($subscription);
    }
}
