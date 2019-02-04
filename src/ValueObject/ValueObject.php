<?php
declare(strict_types=1);

namespace Immutable\ValueObject;

use Immutable\Exception\InvalidValueException;
use Immutable\Exception\ImmutableObjectException;
use Immutable\Immutable;
use Immutable\Type;
use JsonSerializable;

abstract class ValueObject extends Immutable implements JsonSerializable
{
    protected function with(array $properties) // : object
    {
        if (parent::isInitialized()) {
            $object = clone $this;
        } else {
            $object = $this;
        }

        foreach ($properties as $name => $value) {
            if (! property_exists($this, $name)) {
                throw ImmutableObjectException::new($this);
            }

            Type::assertImmutable($value, $name);
            $object->$name = $value;
        }

        return $object;
    }

    public function jsonSerialize()
    {
        return $this->getArrayCopy();
    }

    public function getArrayCopy() : array
    {
        $copy = get_object_vars($this);
        unset($copy['__INITIALIZED__']);
        return $copy;
    }

    public function throwInvalidValueException($name, $expect, $value) : void
    {
        throw InvalidValueException::new($name, $expect, $value);
    }
}
