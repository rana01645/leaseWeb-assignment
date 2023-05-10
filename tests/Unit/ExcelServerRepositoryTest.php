<?php

namespace App\Tests\Unit;

use App\Enum\ExcelServerFields;
use App\Repository\ExcelServerRepository;
use App\Service\ExcelFilterMatcher;
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
            new ExcelFilterMatcher(),
            new StorageParser(),
            new RamParser()
        );
    }

    public function testGetServersReturnsArray(): void
    {
        $servers = $this->repository->getServers();

        $this->assertIsArray($servers);
    }

    public function testSetFiltersWithInvalidFiltersThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $filters = [
            'invalid_filter' => 'value',
        ];

        $this->repository->setFilters($filters);
    }

    public function testOrderByWithInvalidFieldThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->repository->orderBy('invalid_field');
    }

    public function testOrderByWithInvalidDirectionThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->repository->orderBy('price', 'invalid_direction');
    }

    public function testGetServersWithValidFiltersReturnsCorrectData(): void
    {
        $filters = [
            ExcelServerFields::LOCATION => 'Amsterdam',
            ExcelServerFields::HDD_TYPE => 'SSD',
            ExcelServerFields::RAM_CAPACITY => ['16', '32'],
            ExcelServerFields::HDD_CAPACITY => '0-1024',
        ];

        $this->repository->setFilters($filters);

        $servers = $this->repository->getServers();

        $expected = json_decode(file_get_contents(__DIR__.'/../data/expected_servers.json'), true, 512,
            JSON_THROW_ON_ERROR);

        $this->assertEquals($expected, $servers);
    }

    public function testGetServersWithValidOrderByReturnsCorrectData(): void
    {
        $filters = [
            ExcelServerFields::LOCATION => 'Amsterdam',
            ExcelServerFields::HDD_TYPE => 'SSD',
            ExcelServerFields::RAM_CAPACITY => ['16', '32'],
            ExcelServerFields::HDD_CAPACITY => '0-1024',
        ];
        $this->repository->orderBy(ExcelServerFields::LOCATION, 'desc')->setFilters($filters);

        $servers = $this->repository->getServers();
        $expected = json_decode(file_get_contents(__DIR__.'/../data/expected_ordered_servers.json'), true, 512,
            JSON_THROW_ON_ERROR);

        $this->assertEquals($expected, $servers);
    }


    public function testGetLocations(): void
    {
        $locations = $this->repository->getLocations();
        $this->assertCount(7, $locations);
        $this->assertContains('AmsterdamAMS-01', $locations);
        $this->assertContains('Washington D.C.WDC-01', $locations);
        $this->assertContains('San FranciscoSFO-12', $locations);
        $this->assertContains('SingaporeSIN-11', $locations);
        $this->assertContains('DallasDAL-10', $locations);
        $this->assertContains('FrankfurtFRA-10', $locations);
        $this->assertContains('Hong KongHKG-10', $locations);
    }

    public function testGetRamOptions(): void
    {
        $ramOptions = $this->repository->getRamOptions();
        $this->assertCount(7, $ramOptions);
        $this->assertContains('4GB', $ramOptions);
        $this->assertContains('8GB', $ramOptions);
        $this->assertContains('16GB', $ramOptions);
        $this->assertContains('32GB', $ramOptions);
        $this->assertContains('64GB', $ramOptions);
        $this->assertContains('96GB', $ramOptions);
        $this->assertContains('128GB', $ramOptions);
    }
}


