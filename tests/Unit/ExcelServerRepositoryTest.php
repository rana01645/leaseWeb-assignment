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

    public function testGetServersReturnsArray() : void
    {
        $servers = $this->repository->getServers();

        $this->assertIsArray($servers);
    }

    public function testSetFiltersWithInvalidFiltersThrowsException() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        $filters = [
            'invalid_filter' => 'value',
        ];

        $this->repository->setFilters($filters);
    }

    public function testOrderByWithInvalidFieldThrowsException() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->repository->orderBy('invalid_field');
    }

    public function testOrderByWithInvalidDirectionThrowsException() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->repository->orderBy('price', 'invalid_direction');
    }

    public function testGetServersWithValidFiltersReturnsCorrectData() : void
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

    public function testGetServersWithValidOrderByReturnsCorrectData() : void
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
}


