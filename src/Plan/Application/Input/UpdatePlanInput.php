<?php

namespace DDD\Plan\Application\Input;

final readonly class UpdatePlanInput
{
    public function __construct(
        public string $id,
        public ?string $name,
        public ?float $price,
        public ?string $duration
    ) {
    }
}
