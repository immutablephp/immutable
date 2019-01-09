<?php
declare(strict_types=1);

namespace Immutable\ValueObject;

use Immutable\Exception\InvalidValueException;

class EmailTest extends \PHPUnit\Framework\TestCase
{
    public function testValid()
    {
        $old = new Email('foo@example.com');
        $this->assertSame('foo@example.com', $old->getAddress());

        $new = $old->withAddress('bar@example.net');
        $this->assertNotSame($old, $new);
        $this->assertSame('bar@example.net', $new->getAddress());
    }

    public function testInvalid()
    {
        $this->expectException(InvalidValueException::CLASS);
        $email = new Email('abcd');
    }
}
