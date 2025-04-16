<?php

namespace DDD\Bundle;

function generateRandomUUID(): string
{
    try {
        $data = random_bytes(16);
        $hex = bin2hex($data);
        return vsprintf(
            '%s%s-%s-%s-%s-%s%s%s',
            str_split($hex, 4)
        );
    } catch (\Exception $ex) {
        throw new \RuntimeException('Isn\'t possible generate the UUID', 0, $ex);
    }
}
