<?php
declare(strict_types=1);

namespace Immutable\Exception;

class MutableValueException extends Exception
{
    public static function new($name, $value) : self
    {
        if (is_object($value)) {
            $type = get_class($value);
        } else {
            $type = gettype($value);
        }

        $message = "Value for \${$name} is of mutable type '$type'.";
        return new self($message);
    }
}
