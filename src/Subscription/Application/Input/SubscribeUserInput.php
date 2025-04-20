<?php

namespace DDD\Subscription\Application\Input;

final readonly class SubscribeUserInput
{
    public function __construct(
        public string $userId,
        public string $planId
    ) {
    }
}
