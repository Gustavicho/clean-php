<?php

namespace DDD\Subscription\Application\Input;

final readonly class ChangePlanInput
{
    public function __construct(
        public string $userId,
        public string $newPlanId,
    ) {
    }
}
