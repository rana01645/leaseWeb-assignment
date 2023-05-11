<?php

namespace App\Tests\Service;

use App\Enum\ServerFields;
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
            ServerFields::LOCATION => 'Amsterdam',
            ServerFields::HDD_TYPE => 'SSD',
            ServerFields::RAM_CAPACITY => '16',
            ServerFields::HDD_CAPACITY => '500',
        ];

        $filter = 'Amsterdam';

        $result = $this->matcher->matchesFilter($server[ServerFields::LOCATION], $filter);

        $this->assertTrue($result);
    }

    public function testMatchesFilterWithArrayFilterReturnsTrue()
    {
        $server = [
            ServerFields::LOCATION => 'Amsterdam',
            ServerFields::HDD_TYPE => 'SSD',
            ServerFields::RAM_CAPACITY => '16',
            ServerFields::HDD_CAPACITY => '500',
        ];

        $filter = ['Amsterdam', 'Rotterdam'];

        $result = $this->matcher->matchesFilter($server[ServerFields::LOCATION], $filter);

        $this->assertTrue($result);
    }

    public function testMatchesFilterWithRangeFilterReturnsTrue()
    {
        $server = [
            ServerFields::LOCATION => 'Amsterdam',
            ServerFields::HDD_TYPE => 'SSD',
            ServerFields::RAM_CAPACITY => '16',
            ServerFields::HDD_CAPACITY => '500',
        ];

        $filter = '400-600';

        $result = $this->matcher->matchesFilter($server[ServerFields::HDD_CAPACITY], $filter);

        $this->assertTrue($result);
    }

    public function testMatchesFilterWithMultipleRangeFilterReturnsTrue()
    {
        $server = [
            ServerFields::LOCATION => 'Amsterdam',
            ServerFields::HDD_TYPE => 'SSD',
            ServerFields::RAM_CAPACITY => '16',
            ServerFields::HDD_CAPACITY => '500',
        ];

        $filter = ['100-300', '400-600'];

        $result = $this->matcher->matchesFilter($server[ServerFields::HDD_CAPACITY], $filter);

        $this->assertTrue($result);
    }

    public function testMatchesFilterWithCommaSeparatedFilterReturnsTrue()
    {
        $server = [
            ServerFields::LOCATION => 'Amsterdam',
            ServerFields::HDD_TYPE => 'SSD',
            ServerFields::RAM_CAPACITY => '16',
            ServerFields::HDD_CAPACITY => '500',
        ];

        $filter = 'SSD,SATA';

        $result = $this->matcher->matchesFilter($server[ServerFields::HDD_TYPE], $filter);

        $this->assertTrue($result);
    }

    public function testMatchesFilterWithStringFilterReturnsFalse()
    {
        $server = [
            ServerFields::LOCATION => 'Amsterdam',
            ServerFields::HDD_TYPE => 'SSD',
            ServerFields::RAM_CAPACITY => '16',
            ServerFields::HDD_CAPACITY => '500',
        ];

        $filter = 'Rotterdam';

        $result = $this->matcher->matchesFilter($server[ServerFields::LOCATION], $filter);

        $this->assertFalse($result);
    }

    public function testMatchesFilterWithArrayFilterReturnsFalse()
    {
        $server = [
            ServerFields::LOCATION => 'Amsterdam',
            ServerFields::HDD_TYPE => 'SSD',
            ServerFields::RAM_CAPACITY => '16',
            ServerFields::HDD_CAPACITY => '500',
        ];
        $filter = ['Rotterdam', 'Utrecht'];

        $result = $this->matcher->matchesFilter($server[ServerFields::LOCATION], $filter);

        $this->assertFalse($result);
    }

    public function testMatchesFilterWithRangeFilterReturnsFalse()
    {
        $server = [
            ServerFields::LOCATION => 'Amsterdam',
            ServerFields::HDD_TYPE => 'SSD',
            ServerFields::RAM_CAPACITY => '16',
            ServerFields::HDD_CAPACITY => '500',
        ];

        $filter = '600-800';

        $result = $this->matcher->matchesFilter($server[ServerFields::HDD_CAPACITY], $filter);

        $this->assertFalse($result);
    }

    public function testMatchesFilterWithMultipleRangeFilterReturnsFalse()
    {
        $server = [
            ServerFields::LOCATION => 'Amsterdam',
            ServerFields::HDD_TYPE => 'SSD',
            ServerFields::RAM_CAPACITY => '16',
            ServerFields::HDD_CAPACITY => '500',
        ];

        $filter = ['0-100', '600-800'];

        $result = $this->matcher->matchesFilter($server[ServerFields::HDD_CAPACITY], $filter);

        $this->assertFalse($result);
    }

    public function testMatchesFilterWithCommaSeparatedFilterReturnsFalse()
    {
        $server = [
            ServerFields::LOCATION => 'Amsterdam',
            ServerFields::HDD_TYPE => 'SSD',
            ServerFields::RAM_CAPACITY => '16',
            ServerFields::HDD_CAPACITY => '500',
        ];

        $filter = 'SATA,SAS';

        $result = $this->matcher->matchesFilter($server[ServerFields::HDD_TYPE], $filter);

        $this->assertFalse($result);
    }
}
