<?php

namespace DDD\Plan\Application;

use DDD\Plan\Application\Input\CreatePlanInput;
use DDD\Plan\Application\Output\CreatePlanOutput;

final class CreatePlan
{
    public function execute(CreatePlanInput $input): CreatePlanOutput
    {
        return new CreatePlanOutput();
    }
}
