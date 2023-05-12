<?php

namespace App\Service;

use App\Enum\ServerFields;
use Symfony\Component\HttpFoundation\Request;

class OrderByService
{
    public function getOrderByField(Request $request): string
    {
        $orderBy = $request->get('order_by');
        if (!in_array($orderBy, ServerFields::getSupportedOrderByFields(), true)) {
            return "";
        }
        return $orderBy;
    }

    public function getOrderByDirection(Request $request): string
    {
        $orderByDirection = $request->get('order');
        if (!in_array($orderByDirection, ServerFields::getSupportedOrderByDirections(), true)) {
            return "";
        }
        return $orderByDirection;
    }
}
