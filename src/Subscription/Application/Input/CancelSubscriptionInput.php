<?php

namespace DDD\Subscription\Application\Input;

final readonly class CancelSubscriptionInput
{
    public function __construct(
        public string $userId,
    ) {
    }
}
