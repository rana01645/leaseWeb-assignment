<?php

namespace App\Tests\Service;

use App\Enum\ExcelServerFields;
use App\Service\ExcelFilterMatcher;
use PHPUnit\Framework\TestCase;

class ExcelFilterMatcherTest extends TestCase
{
    private ExcelFilterMatcher $matcher;

    protected function setUp(): void
    {
        $this->matcher = new ExcelFilterMatcher();
    }

    public function testMatchesFilterWithStringFilterReturnsTrue()
    {
        $server = [
            ExcelServerFields::LOCATION => 'Amsterdam',
            ExcelServerFields::HDD_TYPE => 'SSD',
            ExcelServerFields::RAM_CAPACITY => '16',
            ExcelServerFields::HDD_CAPACITY => '500',
        ];

        $filter = 'Amsterdam';

        $result = $this->matcher->matchesFilter($server[ExcelServerFields::LOCATION], $filter);

        $this->assertTrue($result);
    }

    public function testMatchesFilterWithArrayFilterReturnsTrue()
    {
        $server = [
            ExcelServerFields::LOCATION => 'Amsterdam',
            ExcelServerFields::HDD_TYPE => 'SSD',
            ExcelServerFields::RAM_CAPACITY => '16',
            ExcelServerFields::HDD_CAPACITY => '500',
        ];

        $filter = ['Amsterdam', 'Rotterdam'];

        $result = $this->matcher->matchesFilter($server[ExcelServerFields::LOCATION], $filter);

        $this->assertTrue($result);
    }

    public function testMatchesFilterWithRangeFilterReturnsTrue()
    {
        $server = [
            ExcelServerFields::LOCATION => 'Amsterdam',
            ExcelServerFields::HDD_TYPE => 'SSD',
            ExcelServerFields::RAM_CAPACITY => '16',
            ExcelServerFields::HDD_CAPACITY => '500',
        ];

        $filter = '400-600';

        $result = $this->matcher->matchesFilter($server[ExcelServerFields::HDD_CAPACITY], $filter);

        $this->assertTrue($result);
    }

    public function testMatchesFilterWithMultipleRangeFilterReturnsTrue()
    {
        $server = [
            ExcelServerFields::LOCATION => 'Amsterdam',
            ExcelServerFields::HDD_TYPE => 'SSD',
            ExcelServerFields::RAM_CAPACITY => '16',
            ExcelServerFields::HDD_CAPACITY => '500',
        ];

        $filter = ['100-300', '400-600'];

        $result = $this->matcher->matchesFilter($server[ExcelServerFields::HDD_CAPACITY], $filter);

        $this->assertTrue($result);
    }

    public function testMatchesFilterWithCommaSeparatedFilterReturnsTrue()
    {
        $server = [
            ExcelServerFields::LOCATION => 'Amsterdam',
            ExcelServerFields::HDD_TYPE => 'SSD',
            ExcelServerFields::RAM_CAPACITY => '16',
            ExcelServerFields::HDD_CAPACITY => '500',
        ];

        $filter = 'SSD,SATA';

        $result = $this->matcher->matchesFilter($server[ExcelServerFields::HDD_TYPE], $filter);

        $this->assertTrue($result);
    }

    public function testMatchesFilterWithStringFilterReturnsFalse()
    {
        $server = [
            ExcelServerFields::LOCATION => 'Amsterdam',
            ExcelServerFields::HDD_TYPE => 'SSD',
            ExcelServerFields::RAM_CAPACITY => '16',
            ExcelServerFields::HDD_CAPACITY => '500',
        ];

        $filter = 'Rotterdam';

        $result = $this->matcher->matchesFilter($server[ExcelServerFields::LOCATION], $filter);

        $this->assertFalse($result);
    }

    public function testMatchesFilterWithArrayFilterReturnsFalse()
    {
        $server = [
            ExcelServerFields::LOCATION => 'Amsterdam',
            ExcelServerFields::HDD_TYPE => 'SSD',
            ExcelServerFields::RAM_CAPACITY => '16',
            ExcelServerFields::HDD_CAPACITY => '500',
        ];
        $filter = ['Rotterdam', 'Utrecht'];

        $result = $this->matcher->matchesFilter($server[ExcelServerFields::LOCATION], $filter);

        $this->assertFalse($result);
    }

    public function testMatchesFilterWithRangeFilterReturnsFalse()
    {
        $server = [
            ExcelServerFields::LOCATION => 'Amsterdam',
            ExcelServerFields::HDD_TYPE => 'SSD',
            ExcelServerFields::RAM_CAPACITY => '16',
            ExcelServerFields::HDD_CAPACITY => '500',
        ];

        $filter = '600-800';

        $result = $this->matcher->matchesFilter($server[ExcelServerFields::HDD_CAPACITY], $filter);

        $this->assertFalse($result);
    }

    public function testMatchesFilterWithMultipleRangeFilterReturnsFalse()
    {
        $server = [
            ExcelServerFields::LOCATION => 'Amsterdam',
            ExcelServerFields::HDD_TYPE => 'SSD',
            ExcelServerFields::RAM_CAPACITY => '16',
            ExcelServerFields::HDD_CAPACITY => '500',
        ];

        $filter = ['0-100', '600-800'];

        $result = $this->matcher->matchesFilter($server[ExcelServerFields::HDD_CAPACITY], $filter);

        $this->assertFalse($result);
    }

    public function testMatchesFilterWithCommaSeparatedFilterReturnsFalse()
    {
        $server = [
            ExcelServerFields::LOCATION => 'Amsterdam',
            ExcelServerFields::HDD_TYPE => 'SSD',
            ExcelServerFields::RAM_CAPACITY => '16',
            ExcelServerFields::HDD_CAPACITY => '500',
        ];

        $filter = 'SATA,SAS';

        $result = $this->matcher->matchesFilter($server[ExcelServerFields::HDD_TYPE], $filter);

        $this->assertFalse($result);
    }
}
