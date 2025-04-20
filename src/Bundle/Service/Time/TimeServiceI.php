<?php

namespace DDD\Bundle\Service\Time;

interface TimeServiceI
{
    public function getTimeNow(): \DateTimeImmutable;
}
