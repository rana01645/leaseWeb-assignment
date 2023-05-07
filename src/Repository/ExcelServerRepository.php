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
    private int $page = 1;
    private int $perPage = 10;

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

    public function setCurrentPage(int $page): ServerRepositoryInterface
    {
        $this->page = $page;
        return $this;
    }

    public function setPerPage(int $perPage): ServerRepositoryInterface
    {
        $this->perPage = $perPage;
        return $this;
    }

    public function getServers(): array
    {
        $startRow = ($this->page - 1) * $this->perPage + 2;
        $endRow = $startRow + $this->perPage - 1;

        $data = $this->worksheet->rangeToArray("A{$startRow}:E{$endRow}", null, true, true, true);
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

    public function getCurrentPage(): int
    {
        return $this->page;
    }

    public function getNextPage(): int
    {
        return $this->page + 1;
    }

    public function getPreviousPage(): int
    {
        return $this->page - 1;
    }


}
