<?php

namespace DDD\Subscription\Domain;

final class Period
{
    public function __construct(
        public readonly \DateTimeImmutable $start,
        public readonly \DateTimeImmutable $end
    ) {
        if ($start > $end) {
            throw new \InvalidArgumentException('Start must be before or equal to end');
        }
    }

    public function isInPeriod(\DateTimeInterface $dateTime): bool
    {
        $timestamp = $dateTime->getTimestamp();
        return $this->start->getTimestamp() <= $timestamp
            && $timestamp <= $this->end->getTimestamp();
    }

    public function isOutPeriod(\DateTimeInterface $dateTime): bool
    {
        return ! $this->isInPeriod($dateTime);
    }
}
