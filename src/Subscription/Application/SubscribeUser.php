<?php

namespace DDD\Subscription\Application;

final class SubscribeUser
{
    public function execute(SubscribeUserInput $input): SubscribeUserOutput
    {
        return new SubscribeUserOutput();
    }
}

final readonly class SubscribeUserInput
{
}

final readonly class SubscribeUserOutput
{
}
