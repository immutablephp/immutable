<?php
declare(strict_types=1);

namespace Immutable\Exception;

class InvalidValueException extends Exception
{
    public static function new($name, $expect, $value) : self
    {
        $message = "Invalid value for {$name}: expected $expect but got " . var_export($value, true);
        return new self($message);
    }
}
