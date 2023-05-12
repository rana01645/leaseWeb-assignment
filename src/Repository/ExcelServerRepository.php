<?php

namespace App\Repository;

use App\Enum\ServerFields;
use App\Service\ExcelFilterMatcher;
use App\Utils\RamParser;
use App\Utils\StorageParser;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExcelServerRepository implements ServerRepositoryInterface
{
    private Worksheet $worksheet;

    private StorageParser $storageParser;

    private RamParser $ramParser;

    private ExcelFilterMatcher $excelFilterMatcher;
    private array $filters = [];
    private ?string $orderByField = null;
    private ?string $orderByDirection = null;

    public function __construct(
        string $excelFilePath,
        ExcelFilterMatcher $excelFilterMatcher,
        StorageParser $storageParser,
        RamParser $ramParser
    ) {
        try {
            $this->worksheet = IOFactory::load($excelFilePath)->getActiveSheet();
        } catch (\Exception $e) {
            throw new \RuntimeException('Error loading Excel file: '.$e->getMessage());
        }
        $this->excelFilterMatcher = $excelFilterMatcher;
        $this->storageParser = $storageParser;
        $this->ramParser = $ramParser;
    }


    public function setFilters(array $filters): ServerRepositoryInterface
    {
        $this->checkValidFilters($filters);
        $this->filters = $filters;
        return $this;
    }

    private function checkValidFilters(array $filters): void
    {
        foreach ($filters as $field => $value) {
            if (!in_array($field, ServerFields::getSupportedFilters(), true)) {
                throw new \InvalidArgumentException('Invalid filter '.$field.' provided, supported filters are: '.implode(', ', ServerFields::getSupportedFilters()));
            }
        }
    }

    public function getServers(): array
    {
         try {
             $data = $this->worksheet->rangeToArray('A2:E'.$this->worksheet->getHighestRow(), null, true, true, true);
         } catch (\Exception $e) {
             throw new \RuntimeException('Error parsing Excel file: '.$e->getMessage());
         }
        $servers = [];
        foreach ($data as $row) {
            $server = [
                ServerFields::MODEL => $row['A'],
                ServerFields::RAM => $row['B'],
                ServerFields::HDD => $row['C'],
                ServerFields::LOCATION => $row['D'],
                ServerFields::PRICE => $row['E'],

                ServerFields::HDD_TYPE => $this->storageParser->parseType($row['C']),
                ServerFields::HDD_CAPACITY => $this->storageParser->parseCapacity($row['C']),
                ServerFields::RAM_TYPE => $this->ramParser->parseType($row['B']),
                ServerFields::RAM_CAPACITY => $this->ramParser->parseCapacity($row['B']),
            ];
            if ($this->excelFilterMatcher->matchesFilters($server, $this->filters)) {
                $servers[] = $server;
            }
        }

        // sort the data based on the selected column and direction
        if ($this->orderByField) {
            usort($servers, function ($a, $b) {
                $result = $a[$this->orderByField] <=> $b[$this->orderByField];
                if ($this->orderByDirection === 'desc') {
                    $result *= -1;
                }
                return $result;
            });
            dd('test');
        }
        // usort($servers, function ($a, $b) {
        //     $aValue = $a[$this->orderByColumn];
        //     $bValue = $b[$this->orderByColumn];
        //     if ($this->orderByDirection === 'desc') {
        //         return $bValue <=> $aValue;
        //     }
        //     return $aValue <=> $bValue;
        // });

        return $servers;
    }

    public function getLocations(): array
    {
        try {
            $data = $this->worksheet->rangeToArray('D2:D'.$this->worksheet->getHighestRow(), null, true, true, true);
        } catch (\Exception $e) {
            throw new \RuntimeException('Error parsing Excel file: '.$e->getMessage());
        }
        $locations = [];
        foreach ($data as $row) {
            $location = $row['D'];
            $city = substr($location, 0, strpos($location, '-'));
            $locations[] = $city;
        }
        return array_values(array_unique($locations));
    }


    public function getRamOptions(): array
    {
        try {
            $data = $this->worksheet->rangeToArray('B2:B'.$this->worksheet->getHighestRow(), null, true, true, true);
        } catch (\Exception $e) {
            throw new \RuntimeException('Error parsing Excel file: '.$e->getMessage());
        }
        $ramFilters = [];
        foreach ($data as $row) {
            $ramFilters[] = $this->ramParser->parseCapacity($row['B']);
        }
        //sort the data
        sort($ramFilters);

        return array_values(array_unique($ramFilters));
    }



    public function orderBy(string $field, string $direction = 'asc'): ServerRepositoryInterface
    {
        if (!in_array($field, ServerFields::getSupportedOrderByFields())) {
            throw new \InvalidArgumentException('Invalid orderBy field: '.$field);
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            throw new \InvalidArgumentException('Invalid orderBy direction: ' . $direction);
        }
        $this->orderByField = $field;
        $this->orderByDirection = $direction;
        return $this;
    }

    public function hasFilters() :bool
    {
        return !empty($this->filters);
    }

    public function getFilters(): array
    {
        return $this->filters;
    }
}
