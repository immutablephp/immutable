<?php
declare(strict_types=1);

namespace Immutable\ValueObject;

use Immutable\Exception\InvalidValueException;

class UuidTest extends \PHPUnit\Framework\TestCase
{
    public function testNewVersion4()
    {
        $uuid = Uuid::newVersion4();
        $this->assertInstanceOf(Uuid::CLASS, $uuid);
    }

    /**
     * @dataProvider provideValid
     */
    public function testValid($identifier)
    {
        $uuid = new Uuid($identifier);
        $this->assertSame($identifier, $uuid->getIdentifier());
    }

    public function provideValid()
    {
        return [
            ['12345678-90ab-cdef-1234-567890123456'],
            ['12345678-90Ab-cdef-1234-5678901abc56'],
            ['12345678-90ab-cdef-1234-567890123456'],
            ['11111111-1111-1111-1111-111111111111'],
            ['aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa'],
            ['1234567890abcdef1234567890123456'],
        ];
    }

    /**
     * @dataProvider provideInvalid
     */
    public function testInvalid($identifier)
    {
        $this->expectException(InvalidValueException::CLASS);
        $creditCard = new Uuid($identifier);
    }

    public function provideInvalid()
    {
        return [
            ['12345678-90ab-cdef-1234-5678901234567'],
            ['123-34324'],
            ['97844444-asdf-fgfd-vf45-383621139112'],
            ['aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaaa'],
            ['10000678-90ab-cdef-1234-56240&123456'],
            ['100Ga678-90ab-cdef-1234-562340&123456'],
            ['100Aa678-90ab-cdef-1234-562340&123456'],
            ['1234567890abcdef12345678901234567'],
            [''],
        ];
    }
}
