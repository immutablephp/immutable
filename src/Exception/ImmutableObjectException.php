<?php
declare(strict_types=1);

namespace Immutable\Exception;

class ImmutableObjectException extends Exception
{
    public static function new($object) : self
    {
        $class = get_class($object);
        return new self("Object of class $class is immutable after construction.");
    }
}
