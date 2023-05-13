<?php

namespace App\Tests\Unit;

use App\Enum\ServerFields;
use App\Repository\ExcelServerRepository;
use App\Utils\RamParser;
use App\Utils\StorageParser;
use PHPUnit\Framework\TestCase;

class ExcelServerRepositoryTest extends TestCase
{
    private ExcelServerRepository $repository;

    public function setUp(): void
    {
        $filePath = __DIR__.'/../data/servers_filters_assignment.xlsx';


        $this->repository = new ExcelServerRepository(
            $filePath,
            new StorageParser(),
            new RamParser()
        );
    }

    public function testGetServersReturnsArray(): void
    {
        $servers = $this->repository->getServers();

        $this->assertIsArray($servers);
    }


    public function testGetLocations(): void
    {
        $locations = $this->repository->getLocations();
        $this->assertCount(7, $locations);
        $this->assertContains('AmsterdamAMS', $locations);
        $this->assertContains('Washington D.C.WDC', $locations);
        $this->assertContains('San FranciscoSFO', $locations);
        $this->assertContains('SingaporeSIN', $locations);
        $this->assertContains('DallasDAL', $locations);
        $this->assertContains('FrankfurtFRA', $locations);
        $this->assertContains('Hong KongHKG', $locations);
    }

    public function testGetRamOptions(): void
    {
        $ramOptions = $this->repository->getRamOptions();
        $this->assertCount(7, $ramOptions);
        $this->assertContains(4, $ramOptions);
        $this->assertContains(8, $ramOptions);
        $this->assertContains(16, $ramOptions);
        $this->assertContains(32, $ramOptions);
        $this->assertContains(64, $ramOptions);
        $this->assertContains(96, $ramOptions);
        $this->assertContains(128, $ramOptions);
    }
}


