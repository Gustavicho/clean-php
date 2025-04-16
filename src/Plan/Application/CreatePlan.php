<?php

namespace DDD\Plan\Application;

final class CreatePlan
{
    public function execute(CreatePlanInput $input): CreatePlanOutput
    {
        return new CreatePlanOutput();
    }
}

final readonly class CreatePlanInput
{
}

final readonly class CreatePlanOutput
{
}
