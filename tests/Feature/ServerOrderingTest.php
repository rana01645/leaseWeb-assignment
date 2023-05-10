<?php

namespace App\Tests\Feature;

use App\Enum\ExcelServerFields;
use App\Repository\ExcelServerRepository;
use App\Service\ExcelFilterMatcher;
use App\Utils\RamParser;
use App\Utils\StorageParser;
use PHPUnit\Framework\TestCase;

class ServerOrderingTest extends TestCase
{
    private ExcelServerRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $excelFilePath = __DIR__.'/../data/servers_filters_assignment.xlsx';
        $filterMatcher = new ExcelFilterMatcher();
        $storageParser = new StorageParser();
        $ramParser = new RamParser();

        $this->repository = new ExcelServerRepository($excelFilePath, $filterMatcher, $storageParser, $ramParser);
    }

    public function testOrderByLocationAscending(): void
    {
        $servers = $this->repository->orderBy(ExcelServerFields::LOCATION)->getServers();

        $this->assertNotEmpty($servers);
        $this->assertIsArray($servers);
        $this->assertStringContainsString('Amsterdam', $servers[0][ExcelServerFields::LOCATION]);
    }

    public function testOrderByLocationDescending(): void
    {
        $servers = $this->repository->orderBy(ExcelServerFields::LOCATION, 'desc')->getServers();

        $this->assertNotEmpty($servers);
        $this->assertIsArray($servers);
        $this->assertStringContainsString('Washington', $servers[0][ExcelServerFields::LOCATION]);
    }

    public function testOrderByRamCapacityAscending(): void
    {
        $servers = $this->repository->orderBy(ExcelServerFields::RAM_CAPACITY)->getServers();

        $this->assertNotEmpty($servers);
        $this->assertIsArray($servers);
        $this->assertEquals(4, $servers[0][ExcelServerFields::RAM_CAPACITY]);
    }

    public function testOrderByRamCapacityDescending(): void
    {
        $servers = $this->repository->orderBy(ExcelServerFields::RAM_CAPACITY, 'desc')->getServers();

        $this->assertNotEmpty($servers);
        $this->assertIsArray($servers);
        $this->assertEquals(128, $servers[0][ExcelServerFields::RAM_CAPACITY]);
    }

    public function testOrderByHddCapacityAscending(): void
    {
        $servers = $this->repository->orderBy(ExcelServerFields::HDD_CAPACITY)->getServers();

        $this->assertNotEmpty($servers);
        $this->assertIsArray($servers);
        $this->assertEquals(120, $servers[0][ExcelServerFields::HDD_CAPACITY]);
    }

    public function testOrderByHddCapacityDescending(): void
    {
        $servers = $this->repository->orderBy(ExcelServerFields::HDD_CAPACITY, 'desc')->getServers();

        $this->assertNotEmpty($servers);
        $this->assertIsArray($servers);
        $this->assertEquals(24576, $servers[0][ExcelServerFields::HDD_CAPACITY]);
    }

    public function testOrderByValidationForInvalidValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->repository->orderBy(ExcelServerFields::HDD_TYPE)->getServers();

    }

}
