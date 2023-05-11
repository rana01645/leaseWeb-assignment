<?php

namespace App\Tests\Feature;

use App\Enum\ServerFields;
use App\Repository\ExcelServerRepository;
use App\Service\ExcelFilterMatcher;
use App\Utils\RamParser;
use App\Utils\StorageParser;
use PHPUnit\Framework\TestCase;

class ServerFilteringTest extends TestCase
{

    private ExcelServerRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $filePath = __DIR__.'/../data/servers_filters_assignment.xlsx';

        $this->repository = new ExcelServerRepository(
            $filePath,
            new ExcelFilterMatcher(),
            new StorageParser(),
            new RamParser()
        );
    }

    public function testCanFilterByLocation()
    {
        $servers = $this->repository->setFilters([
            ServerFields::LOCATION => 'Amsterdam',
        ])->getServers();

        foreach ($servers as $server) {
            $this->assertStringContainsString('Amsterdam', $server[ServerFields::LOCATION]);
        }
    }

    public function testCanFilterByRamCapacity()
    {
        $servers = $this->repository->setFilters([
            ServerFields::RAM_CAPACITY => '16',
        ])->getServers();

        foreach ($servers as $server) {
            $this->assertEquals(16, $server[ServerFields::RAM_CAPACITY]);
        }
    }

    public function testCanFilterByHddCapacity()
    {
        $servers = $this->repository->setFilters([
            ServerFields::HDD_CAPACITY => '1024-2048',
        ])->getServers();

        foreach ($servers as $server) {
            $this->assertGreaterThanOrEqual(1024, $server[ServerFields::HDD_CAPACITY]);
            $this->assertLessThanOrEqual(2048, $server[ServerFields::HDD_CAPACITY]);
        }
    }

    public function testCanFilterByHddType()
    {
        $servers = $this->repository->setFilters([
            ServerFields::HDD_TYPE => 'SATA',
        ])->getServers();

        foreach ($servers as $server) {
            $this->assertStringContainsString('SATA', $server[ServerFields::HDD_TYPE]);
        }
    }
}
