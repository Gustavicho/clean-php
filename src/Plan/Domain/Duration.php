<?php

namespace DDD\Plan\Domain;

use DDD\Subscription\Domain\Period;

final class Duration
{
    private const UNIT_MAP = [
        'day'   => 'P%dD',
        'month' => 'P%dM',
        'year'  => 'P%dY',
    ];

    private function __construct(
        public readonly int $quantity,
        public readonly string $unit,
    ) {
    }

    public static function fromString(string $value): self
    {
        $value = strtolower(trim($value));
        $value = preg_replace('/s$/', '', $value);

        [$qty, $unit] = explode(' ', $value, 2);
        if (!ctype_digit($qty) || !isset(self::UNIT_MAP[$unit])) {
            throw new \InvalidArgumentException("Invalid duration '{$value}'");
        }

        return new self((int)$qty, $unit);
    }

    public function endsAt(\DateTimeImmutable $start): \DateTimeImmutable
    {
        $format = sprintf(self::UNIT_MAP[$this->unit], $this->quantity);
        $interval = new \DateInterval($format);
        return $start->add($interval);
    }

    public function toPeriod(\DateTimeImmutable $start): Period
    {
        return new Period(
            $start,
            $this->endsAt($start),
        );
    }

    public function isZero(): bool
    {
        return $this->quantity === 0;
    }

    public function isNegative(): bool
    {
        return $this->quantity < 0;
    }

    public function isPositive(): bool
    {
        return $this->quantity > 0;
    }

    public function isNegativeOrZero(): bool
    {
        return $this->quantity <= 0;
    }

    public function isPositiveOrZero(): bool
    {
        return $this->quantity >= 0;
    }
}
