<?php

namespace App\Enum;

class ExcelServerFields
{
    const MODEL = 'model';
    const RAM = 'ram';
    const HDD = 'hdd';
    const LOCATION = 'location';
    const PRICE = 'price';
    const HDD_TYPE = 'hdd_type';
    const HDD_CAPACITY = 'hdd_capacity';
    const RAM_TYPE = 'ram_type';
    const RAM_CAPACITY = 'ram_capacity';

    public static function getSupportedFilters(): array
    {
        return [
            self::RAM_TYPE,
            self::RAM_CAPACITY,
            self::HDD_TYPE,
            self::HDD_CAPACITY,
            self::LOCATION,
        ];
    }

    //get order by fields
    public static function getSupportedOrderByFields(): array
    {
        return [
            self::MODEL,
            self::RAM_CAPACITY,
            self::HDD_CAPACITY,
            self::LOCATION,
            self::PRICE,
        ];
    }
}

