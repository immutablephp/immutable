<?php
declare(strict_types=1);

namespace Immutable;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;

class Bag extends Immutable implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    protected $data = [];

    public function __construct(array $data)
    {
        $this->set($data);
        parent::__construct();
    }

    public function __get($key)
    {
        return $this->data[$key];
    }

    public function __isset($key)
    {
        return isset($this->data[$key]);
    }

    public function __debugInfo() : array
    {
        return $this->data;
    }

    public function offsetExists($key)
    {
        return isset($this->data[$key]);
    }

    public function offsetGet($key)
    {
        return $this->data[$key];
    }

    public function count()
    {
        return count($this->data);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }

    public function jsonSerialize()
    {
        return $this->data;
    }

    public function getArrayCopy() : array
    {
        $copy = [];
        foreach ($this->data as $key => $value) {
            if ($value instanceof Immutable) {
                $copy[$key] = $value->getArrayCopy();
            } else {
                $copy[$key] = $value;
            }
        }
        return $copy;
    }

    public function with($key, $value) : self
    {
        $clone = clone $this;
        $clone->set([$key => $value]);
        return $clone;
    }

    public function without(...$keys) : self
    {
        $clone = clone $this;
        foreach ($keys as $key) {
            unset($clone->data[$key]);
        }
        return $clone;
    }

    public function replace(array $data) : self
    {
        $clone = clone $this;
        $clone->set($data);
        return $clone;
    }

    protected function set(array $data) : void
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $this->data[$key] = new Bag($value);
                continue;
            }
            Type::assertImmutable($value, $key);
            $this->data[$key] = $value;
        }
    }
}
