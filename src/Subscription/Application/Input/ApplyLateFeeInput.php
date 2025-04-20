<?php

namespace DDD\Subscription\Application\Input;

use Brick\Money\Money;

final readonly class ApplyLateFeeInput
{
    public function __construct(
        public string $userId,
        public Money $baseFee,
    ) {
    }
}
