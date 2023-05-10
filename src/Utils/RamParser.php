<?php

namespace App\Utils;

class RamParser
{
    public function parseCapacity(string $ramValue): ?int
    {
        if (preg_match('/(\d+)GB/', $ramValue, $matches)) {
            return (int) $matches[1];
        }
         throw new \InvalidArgumentException('Invalid RAM value provided, currently only value in GB is supported');
    }

    public function parseType(string $ramValue): ?string
    {
        if (preg_match('/\d+GB(\w+)/', $ramValue, $matches)) {
            return $matches[1];
        }
        throw new \InvalidArgumentException('Invalid RAM Type value provided, currently only value in GB is supported');
    }
}
