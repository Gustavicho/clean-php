<?php

namespace DDD\Bundle\Service\Time;

final class TimeServiceSystem implements TimeServiceI
{
    public function getTimeNow(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}