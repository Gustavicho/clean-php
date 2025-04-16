<?php

namespace DDD\Plan\Application;

use DDD\Plan\Application\Input\UpdatePlanInput;
use DDD\Plan\Application\Output\UpdatePlanOutput;

final class UpdatePlan
{
    public function execute(UpdatePlanInput $input): UpdatePlanOutput
    {
        return new UpdatePlanOutput();
    }
}
