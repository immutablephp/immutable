<?php
declare(strict_types=1);

namespace Immutable\ValueObject;

use Immutable\Exception\InvalidValueException;

class IpTest extends \PHPUnit\Framework\TestCase
{
    public function testValid()
    {
        $old = new Ip('127.0.0.1');
        $this->assertSame('127.0.0.1', $old->getAddress());

        $new = $old->withAddress('192.168.0.1');
        $this->assertNotSame($old, $new);
        $this->assertSame('192.168.0.1', $new->getAddress());
    }

    public function testInvalid()
    {
        $this->expectException(InvalidValueException::CLASS);
        $ip = new Ip('abcd');
    }
}
