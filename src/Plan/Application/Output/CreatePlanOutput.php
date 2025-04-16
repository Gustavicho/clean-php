<?php

namespace DDD\Plan\Application\Output;

use Brick\Money\Money;
use DDD\Plan\Domain\Duration;

final readonly class CreatePlanOutput
{
    public function __construct(
        public string $id,
        public string $name,
        public Money $price,
        public Duration $duration
    ) {
    }
}
