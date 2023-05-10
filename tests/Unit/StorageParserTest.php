<?php

namespace App\Tests\Unit;


use App\Utils\StorageParser;
use PHPUnit\Framework\TestCase;

class StorageParserTest extends TestCase
{
    private StorageParser $parser;

    protected function setUp(): void
    {
        $this->parser = new StorageParser();
    }

    public function testParseCapacityWithSingleDiskInGBReturnsCorrectValue()
    {
        $value = '500GBSATA';
        $result = $this->parser->parseCapacity($value);
        $this->assertEquals(500, $result);
    }

    public function testParseCapacityWithSingleDiskInTBReturnsCorrectValue()
    {
        $value = '2TBSSD';
        $result = $this->parser->parseCapacity($value);
        $this->assertEquals(2048, $result);
    }

    public function testParseCapacityWithMultipleDisksInGBReturnsCorrectValue()
    {
        $value = '4x250GB';
        $result = $this->parser->parseCapacity($value);
        $this->assertEquals(1000, $result);
    }

    public function testParseCapacityWithMultipleDisksInTBReturnsCorrectValue()
    {
        $value = '2x2TB';
        $result = $this->parser->parseCapacity($value);
        $this->assertEquals(4096, $result);
    }

    public function testParseCapacityWithInvalidValueThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $value = 'invalid_value';
        $this->parser->parseCapacity($value);
    }

    public function testParseTypeWithSATAValueReturnsCorrectValue()
    {
        $value = '100GBSATA3';
        $result = $this->parser->parseType($value);
        $this->assertEquals('SATA3', $result);
    }

    public function testParseTypeWithSASValueReturnsCorrectValue()
    {
        $value = 'SAS';
        $result = $this->parser->parseType($value);
        $this->assertEquals('SAS', $result);
    }

    public function testParseTypeWithSSDValueReturnsCorrectValue()
    {
        $value = '50TBSSD';
        $result = $this->parser->parseType($value);
        $this->assertEquals('SSD', $result);
    }

    public function testParseTypeWithInvalidValueThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $value = 'invalid_value';
        $this->parser->parseType($value);
    }
}

