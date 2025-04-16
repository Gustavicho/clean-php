<?php

namespace DDD\Plan\Application;

use Brick\Money\Currency;
use Brick\Money\Money;
use DDD\Plan\Application\Input\CreatePlanInput;
use DDD\Plan\Application\Output\CreatePlanOutput;
use DDD\Plan\Domain\Duration;
use DDD\Plan\Domain\Plan;
use DDD\Plan\Infra\Repository\PlanRepositoryI;

final class CreatePlan
{
    public function __construct(
        private readonly PlanRepositoryI $repo,
    ) {
    }

    public function execute(CreatePlanInput $input): CreatePlanOutput
    {
        $duration = Duration::fromString($input->duration);
        $price = Money::of($input->price, Currency::of('BRL'));

        $plan = Plan::create($input->name, $price, $duration);

        $this->repo->save($plan);

        return new CreatePlanOutput(
            $plan->id,
            $plan->name,
            $plan->price,
            $plan->duration,
        );
    }
}
