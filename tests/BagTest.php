<?php
declare(strict_types=1);

namespace Immutable;

use ArrayIterator;
use RuntimeException;

class BagTest extends \PHPUnit\Framework\TestCase
{
    protected $data = [
        'foo' => 'bar',
        'baz' => [
            'dib' => [
                'zim',
                'gir',
            ],
        ],
    ];

    public function testCount()
    {
        $bag = new Bag($this->data);
        $this->assertSame(2, count($bag));
    }

    public function testIssetAndOffsetExists()
    {
        $bag = new Bag($this->data);

        $this->assertTrue(isset($bag->foo));
        $this->assertSame('bar', $bag->foo);

        $this->assertTrue(isset($bag['foo']));
        $this->assertSame('bar', $bag['foo']);
    }

    public function testArraysAsBags()
    {
        $bag = new Bag($this->data);

        $this->assertInstanceOf(Bag::CLASS, $bag->baz);
        $this->assertInstanceOf(Bag::CLASS, $bag->baz->dib);

        $this->assertSame('zim', $bag->baz->dib[0]);
        $this->assertSame('gir', $bag->baz->dib[1]);
    }

    public function testIteration()
    {
        $bag = new Bag($this->data);
        $this->assertInstanceOf(ArrayIterator::CLASS, $bag->getIterator());
    }

    public function testWith()
    {
        $old = new Bag($this->data);

        $new = $old->with('irk', 'gir');
        $this->assertSame('gir', $new->irk);

        $this->assertNotSame($old, $new);
        $this->assertFalse(isset($old->irk));
    }

    public function testWithout()
    {
        $old = new Bag($this->data);

        $new = $old->without('foo');
        $this->assertFalse(isset($new->foo));

        $this->assertNotSame($old, $new);
        $this->assertTrue(isset($old->foo));
    }

    public function testReplace()
    {
        $old = new Bag($this->data);

        $new = $old->replace(['irk' => 'gir']);
        $this->assertSame('gir', $new->irk);

        $this->assertNotSame($old, $new);
        $this->assertFalse(isset($old->irk));
    }

    public function testJsonSerialize()
    {
        $bag = new Bag(['foo' => 'bar']);
        $actual = json_encode($bag);
        $this->assertSame('{"foo":"bar"}', $actual);
    }
}
