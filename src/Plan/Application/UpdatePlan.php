<?php

namespace DDD\Plan\Application;

use Brick\Money\Money;
use DDD\Plan\Application\Input\UpdatePlanInput;
use DDD\Plan\Application\Output\UpdatePlanOutput;
use DDD\Plan\Domain\Duration;
use DDD\Plan\Domain\Plan;
use DDD\Plan\Infra\Repository\PlanRepositoryI;

final class UpdatePlan
{
    public function __construct(
        private readonly PlanRepositoryI $repo,
    ) {
    }

    public function execute(UpdatePlanInput $input): UpdatePlanOutput
    {
        $plan = $this->repo->findById($input->id);
        if (!$plan) {
            throw new \DomainException('Plan not found');
        }

        $price = $input->price !== null
            ? Money::of($input->price, 'BRL')
            : $plan->price;

        $duration = $input->duration !== null
            ? Duration::fromString($input->duration)
            : $plan->duration;

        $newPlan = new Plan(
            $plan->id,
            $input->name,
            $price,
            $duration,
        );

        $this->repo->update($newPlan);

        return new UpdatePlanOutput(
            $plan->id,
            $plan->name,
            $plan->price,
            $plan->duration,
        );
    }
}
