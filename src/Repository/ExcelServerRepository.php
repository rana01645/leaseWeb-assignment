<?php

namespace App\Repository;

use App\Enum\ServerFields;
use App\Utils\RamParser;
use App\Utils\StorageParser;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExcelServerRepository implements ServerRepositoryInterface
{
    private Worksheet $worksheet;

    private StorageParser $storageParser;

    private RamParser $ramParser;


    public function __construct(
        string $excelFilePath,
        StorageParser $storageParser,
        RamParser $ramParser
    ) {
        try {
            $this->worksheet = IOFactory::load($excelFilePath)->getActiveSheet();
        } catch (\Exception $e) {
            throw new \RuntimeException('Error loading Excel file: '.$e->getMessage());
        }
        $this->storageParser = $storageParser;
        $this->ramParser = $ramParser;
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

            $servers[] = $server;
        }


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
}
