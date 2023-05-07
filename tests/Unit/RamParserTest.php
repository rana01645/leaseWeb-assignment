<?php

namespace App\Tests\Unit;


use App\Utils\RamParser;
use PHPUnit\Framework\TestCase;


class RamParserTest extends TestCase
{
    private RamParser $ramParser;

    protected function setUp(): void
    {
        $this->ramParser = new RamParser();
    }

    public function testParseCapacityWithValidValueReturnsInteger(): void
    {
        $ramValue = '16GBDDR3';

        $result = $this->ramParser->parseCapacity($ramValue);

        $this->assertSame(16, $result);
    }

    public function testParseCapacityWithInvalidValueThrowsException(): void
    {
        $ramValue = 'InvalidValue';

        $this->expectException(\InvalidArgumentException::class);

        $this->ramParser->parseCapacity($ramValue);
    }

    public function testParseTypeWithValidValueReturnsString(): void
    {
        $ramValue = '16GBDDR3';

        $result = $this->ramParser->parseType($ramValue);

        $this->assertSame('DDR3', $result);
    }

    public function testParseTypeWithInvalidValueThrowsException(): void
    {
        $ramValue = 'InvalidValue';

        $this->expectException(\InvalidArgumentException::class);

        $this->ramParser->parseType($ramValue);
    }
}


