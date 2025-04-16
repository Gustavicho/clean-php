<?php

namespace DDD\Plan\Application\Input;

final readonly class CreatePlanInput
{
    public function __construct(
        public string $name,
        public float $price,
        public string $duration
    ) {
    }
}
