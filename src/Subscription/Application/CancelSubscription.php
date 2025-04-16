<?php

namespace DDD\Subscription\Application;

final class CancelSubscription
{
    public function execute(CancelSubscriptionInput $input): CancelSubscriptionOutput
    {
        return new CancelSubscriptionOutput();
    }
}

final readonly class CancelSubscriptionInput
{
}

final readonly class CancelSubscriptionOutput
{
}
