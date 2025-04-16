<?php

namespace DDD\Subscription\Application;

final class ChangePlan
{
    public function execute(ChangePlanInput $input): ChangePlanOutput
    {
        return new ChangePlanOutput();
    }
}

final readonly class ChangePlanInput
{
}

final readonly class ChangePlanOutput
{
}
