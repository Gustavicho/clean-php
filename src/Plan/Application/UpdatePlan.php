<?php

namespace DDD\Plan\Application;

final class UpdatePlan
{
    public function execute(UpdatePlanInput $input): UpdatePlanOutput
    {
        return new UpdatePlanOutput();
    }
}

final readonly class UpdatePlanInput
{
}

final readonly class UpdatePlanOutput
{
}
