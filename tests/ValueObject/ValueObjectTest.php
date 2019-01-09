<?php
declare(strict_types=1);

namespace Immutable\ValueObject;

use Immutable\Exception\ImmutableObjectException;
use Immutable\Exception\InvalidValueException;

class ValueObjectTest extends \PHPUnit\Framework\TestCase
{
    public function setUp()
    {
        $this->fake = new FakeValueObject('bar');
    }

    public function testReconstruct()
    {
        $this->expectException(ImmutableObjectException::CLASS);
        $this->fake->__construct('baz');
    }

    public function testMagicSet()
    {
        $this->expectException(ImmutableObjectException::CLASS);
        $this->fake->foo = 'baz';
    }

    public function testMagicUnset()
    {
        $this->expectException(ImmutableObjectException::CLASS);
        unset($this->fake->foo);
    }

    public function testOffsetSet()
    {
        $this->expectException(ImmutableObjectException::CLASS);
        $this->fake['foo'] = 'baz';
    }

    public function testOffsetUnset()
    {
        $this->expectException(ImmutableObjectException::CLASS);
        unset($this->fake['foo']);
    }

    public function testWith()
    {
        $actual = $this->fake->withFoo('dib');
        $this->assertNotSame($this->fake, $actual);
        $this->assertSame('dib', $actual->getFoo());
    }

    public function testWithMissingProperty()
    {
        $this->expectException(ImmutableObjectException::CLASS);
        $actual = $this->fake->withBar('foo');
    }

    public function testJsonSerialize()
    {
        $actual = json_encode($this->fake);
        $this->assertSame('{"foo":"bar"}', $actual);
    }
}
