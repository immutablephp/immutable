<?php
declare(strict_types=1);

namespace Immutable;

use Immutable\Exception\MutableValueException;
use stdClass;
use Error;

class TypeTest extends \PHPUnit\Framework\TestCase
{
    public function testIsImmutable()
    {
        $this->assertTrue(Type::isImmutable(null));
        $this->assertTrue(Type::isImmutable(1));
        $this->assertTrue(Type::isImmutable('foo'));
        $this->assertTrue(Type::isImmutable(true));
        $this->assertTrue(Type::isImmutable(false));
        $this->assertFalse(Type::isImmutable([]));
        $this->assertFalse(Type::isImmutable(new stdClass()));
    }

    public function testConstruct()
    {
        $this->expectException(Error::CLASS);
        new Type();
    }

    public function testAssertImmutable()
    {
        $this->expectException(MutableValueException::CLASS);
        Type::assertImmutable(new stdClass(), 'fake');
    }

    public function testRegister()
    {
        $this->assertFalse(Type::isImmutable(new stdClass()));
        Type::register(stdClass::CLASS);
        $this->assertTrue(Type::isImmutable(new stdClass()));
    }
}
