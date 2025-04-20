<?php

namespace DDD\Bundle\Service\Random;

final class RandomGenerator
{
    public static function UUID(): string
    {
        try {
            $data = random_bytes(16);
            $hex = bin2hex($data);
            return vsprintf(
                '%s%s-%s-%s-%s-%s%s%s',
                str_split($hex, 4)
            );
        } catch (\Exception $ex) {
            throw new \RuntimeException('It isn\'t possible to generate the UUID', 0, $ex);
        }
    }
}
