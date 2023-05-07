<?php

namespace App\Utils;

class RamParser
{
    public function parseCapacity(string $ramValue): ?int
    {
        if (preg_match('/(\d+)GB/', $ramValue, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }

    public function parseType(string $ramValue): ?string
    {
        if (preg_match('/\d+GB(\w+)/', $ramValue, $matches)) {
            return $matches[1];
        }
        return null;
    }
}
