<?php

namespace App\Service;

use App\Enum\ServerFields;
use Symfony\Component\HttpFoundation\Request;

class FilterService
{
    public function generateFilter(Request $request): array
    {
        $filter = [];

        $location = $request->get('location');
        if ($location) {
            $filter[ServerFields::LOCATION] = $location;
        }

        $hddType = $request->get('hdd_type');
        if ($hddType) {
            $filter[ServerFields::HDD_TYPE] = $hddType;
        }

        $ramCapacity = $request->get('ram_capacity');
        if ($ramCapacity) {
            $ramCapacity = explode(',', $ramCapacity);
            $filter[ServerFields::RAM_CAPACITY] = $ramCapacity;
        }

        $hddCapacity = $request->get('hdd_capacity');
        if ($hddCapacity) {
            $filter[ServerFields::HDD_CAPACITY] = $hddCapacity;
        }

        return $filter;
    }
}
