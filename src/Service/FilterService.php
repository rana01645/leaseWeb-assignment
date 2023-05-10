<?php

namespace App\Service;

use App\Enum\ExcelServerFields;
use Symfony\Component\HttpFoundation\Request;

class FilterService
{
    public function generateFilter(Request $request): array
    {
        $filter = [];

        $location = $request->get('location');
        if ($location) {
            $filter[ExcelServerFields::LOCATION] = $location;
        }

        $hddType = $request->get('hdd_type');
        if ($hddType) {
            $filter[ExcelServerFields::HDD_TYPE] = $hddType;
        }

        $ramCapacity = $request->get('ram_capacity');
        if ($ramCapacity) {
            $ramCapacity = explode(',', $ramCapacity);
            $filter[ExcelServerFields::RAM_CAPACITY] = $ramCapacity;
        }

        $hddCapacity = $request->get('hdd_capacity');
        if ($hddCapacity) {
            $filter[ExcelServerFields::HDD_CAPACITY] = $hddCapacity;
        }

        return $filter;
    }
}
