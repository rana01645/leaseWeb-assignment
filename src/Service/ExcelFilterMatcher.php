<?php

namespace App\Service;

class ExcelFilterMatcher
{

    public function matchesFilters(array $server, array $filters): bool
    {
        foreach ($filters as $field => $value) {
            // if ($field === 'storage') {
            //     $server[$field] = $this->getStorageFromHdd($server['hdd']);
            // }

            if (!$this->matchesFilter($server[$field], $value)) {
                return false;
            }
        }
        return true;
    }

    private function matchesFilter(string $value, $filter): bool
    {
        if (is_array($filter)) {
            foreach ($filter as $item) {
                if ($this->matchesFilter($value, $item)) {
                    return true;
                }
            }
            return false;
        }

        if (str_contains($filter, '-')) {
            [$min, $max] = explode('-', $filter);
            return $value >= $min && $value <= $max;
        }

        if (str_contains($filter, ',')) {
            $values = explode(',', $filter);
            return in_array($value, $values);
        }

        return $value === $filter || str_contains($value, $filter);
    }

}
