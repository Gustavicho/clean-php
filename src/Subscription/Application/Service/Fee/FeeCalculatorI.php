<?php

namespace DDD\Subscription\Application\Service\Fee;

use Brick\Money\Money;
use DDD\Subscription\Domain\Subscription;

interface FeeCalculatorI
{
    public function applyLateFee(Subscription $subscription, Money $baseFee): Subscription;
}
