<?php

namespace DDD\Bundle\Service\Time;

final class TimeServiceFaker implements TimeServiceI
{
    public function __construct(
        private readonly \DateTimeImmutable $time,
    ) {
    }

    public function getTimeNow(): \DateTimeImmutable
    {
        return $this->time;
    }
}
