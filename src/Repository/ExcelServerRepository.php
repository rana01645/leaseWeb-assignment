<?php

namespace App\Repository;

use App\Service\ExcelFilterMatcher;
use App\Utils\StorageParser;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExcelServerRepository implements ServerRepositoryInterface
{
    private Worksheet $worksheet;

    private StorageParser $storageParser;

    private ExcelFilterMatcher $excelFilterMatcher;
    private array $filters = [];

    public function __construct(string $excelFilePath,ExcelFilterMatcher $excelFilterMatcher, StorageParser $storageParser)
    {
        $this->worksheet = IOFactory::load($excelFilePath)->getActiveSheet();
        $this->excelFilterMatcher = $excelFilterMatcher;
        $this->storageParser = $storageParser;
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
                'storage' => $this->storageParser->getStorageFromHdd($row['C']),
                'location' => $row['D'],
                'price' => $row['E'],
            ];
            if ($this->excelFilterMatcher->matchesFilters($server, $this->filters)) {
                $servers[] = $server;
            }
        }
        return $servers;
    }


}
