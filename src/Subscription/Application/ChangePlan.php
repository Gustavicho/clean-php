<?php

namespace DDD\Subscription\Application;

use Brick\Money\Money;
use DDD\Bundle\Service\Time\TimeServiceFaker;
use DDD\Bundle\Service\Time\TimeServiceI;
use DDD\Plan\Infra\Repository\PlanRepositoryI;
use DDD\Subscription\Application\Input\ChangePlanInput;
use DDD\Subscription\Application\Output\ChangePlanOutput;
use DDD\Subscription\Domain\Period;
use DDD\Subscription\Domain\State\SubscriptionState;
use DDD\Subscription\Domain\Subscription;
use DDD\Subscription\Infra\Repository\SubscriptionRepositoryI;
use DDD\User\Infra\Repository\UserRepositoryI;

final class ChangePlan
{
    public function __construct(
        private readonly SubscriptionRepositoryI $repo,
        private readonly UserRepositoryI $userRepo,
        private readonly PlanRepositoryI $planRepo,
        private readonly TimeServiceI $time,
    ) {
    }

    public function execute(ChangePlanInput $input): ChangePlanOutput
    {
        $user = $this->userRepo->findById($input->userId)
          ?? throw new \DomainException('user not found');

        $oldSubscription = $this->repo->findByUser($user)
            ?? throw new \DomainException('user don\'t has a subscribtion');

        $plan = $this->planRepo->findById($input->newPlanId)
            ?? throw new \DomainException('plan don\'t exist');

        $oldSubscription->cancel();

        $period = $plan->duration->toPeriod(
            $this->time->getTimeNow()
        );
        $newSubscription = Subscription::create(
            $user->id,
            $plan->id,
            $plan->price,
            $period
        );

        $this->repo->update($oldSubscription);
        $this->repo->save($newSubscription);

        return new ChangePlanOutput(
            $newSubscription->id,
            $newSubscription->userId,
            $newSubscription->planId,
            $newSubscription->price,
            $newSubscription->period,
            $newSubscription->state(),
        );
    }
}
