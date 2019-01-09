<?php
declare(strict_types=1);

namespace Immutable\ValueObject;

use ArrayAccess;

class FakeValueObject extends ValueObject implements ArrayAccess
{
    protected $foo = '';

    public function __construct(string $foo)
    {
        $this->withFoo($foo);
        parent::__construct();
    }

    public function withFoo(string $foo) : self
    {
        return $this->with(['foo' => $foo]);
    }

    public function withBar(string $bar) : self
    {
        return $this->with(['bar' => $bar]);
    }

    public function getFoo() : string
    {
        return $this->foo;
    }

    public function offsetGet($key)
    {
        return $this->$key;
    }

    public function offsetExists($key)
    {
        return isset($this->$key);
    }
}
