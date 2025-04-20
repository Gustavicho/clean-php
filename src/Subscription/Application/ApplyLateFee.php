<?php

namespace DDD\Subscription\Application;

use Brick\Money\Money;
use DDD\Subscription\Application\Input\ApplyLateFeeInput;
use DDD\Subscription\Application\Output\ApplyLateFeeOutput;
use DDD\Subscription\Application\Service\Fee\FeeCalculatorI;
use DDD\Subscription\Domain\Period;
use DDD\Subscription\Domain\State\SubscriptionState;
use DDD\Subscription\Infra\Repository\SubscriptionRepositoryI;
use DDD\User\Infra\Repository\UserRepositoryI;

final class ApplyLateFee
{
    public function __construct(
        private readonly SubscriptionRepositoryI $repo,
        private readonly UserRepositoryI $userRepo,
        private readonly FeeCalculatorI $feeCalculator,
    ) {
    }

    public function execute(ApplyLateFeeInput $input): ApplyLateFeeOutput
    {
        $user = $this->userRepo->findById($input->userId)
          ?? throw new \DomainException('user not found');

        $subscription = $this->repo->findByUser($user)
          ?? throw new \DomainException('user don\'t has a subscribtion');

        // TODO: the base fee can be a value configured by gym
        $subscription = $this->feeCalculator->applyLateFee($subscription, $input->baseFee);
        $this->repo->save($subscription);

        return new ApplyLateFeeOutput(
            $subscription->id,
            $subscription->userId,
            $subscription->planId,
            $subscription->price,
            $subscription->period,
            $subscription->state(),
            $subscription->fee(),
            $subscription->discount(),
            $subscription->total(),
        );
    }
}
