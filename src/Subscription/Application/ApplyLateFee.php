<?php

namespace DDD\Subscription\Application;

final class ApplyLateFee
{
    public function execute(ApplyLateFeeInput $input): ApplyLateFeeOutput
    {
        return new ApplyLateFeeOutput();
    }
}

final readonly class ApplyLateFeeInput
{
}

final readonly class ApplyLateFeeOutput
{
}
