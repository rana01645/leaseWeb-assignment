<?php

namespace App\Repository;

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
        $this->worksheet = IOFactory::load($excelFilePath)->getActiveSheet();
        $this->excelFilterMatcher = $excelFilterMatcher;
        $this->storageParser = $storageParser;
        $this->ramParser = $ramParser;
    }


    public function setFilters(array $filters): ServerRepositoryInterface
    {
        $this->filters = $filters;
        return $this;
    }

    public function getServers(): array
    {
        $data = $this->worksheet->rangeToArray('A2:E'.$this->worksheet->getHighestRow(), null, true, true, true);
        $servers = [];
        foreach ($data as $row) {
            $server = [
                'model' => $row['A'],
                'ram' => $row['B'],
                'hdd' => $row['C'],
                'location' => $row['D'],
                'price' => $row['E'],

                'hdd_type' => $this->storageParser->parseType($row['C']),
                'hdd_capacity' => $this->storageParser->parseCapacity($row['C']),
                'ram_type' => $this->ramParser->parseType($row['B']),
                'ram_capacity' => $this->ramParser->parseCapacity($row['B']),
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


    public function orderBy(string $field, string $direction = 'asc'): ServerRepositoryInterface
    {
        $this->orderByField = $field;
        $this->orderByDirection = $direction;
        return $this;
    }
}
