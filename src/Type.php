<?php
declare(strict_types=1);

namespace Immutable;

use DateTimeImmutable;
use Immutable\Exception\MutableValueException;

final class Type
{
    protected static $immutableClasses = [
        Immutable::CLASS,
        DateTimeImmutable::CLASS,
    ];

    /**
     * Disallow construction.
     */
    private function __construct() {}

    public static function register(string ...$immutableClasses) : void
    {
        self::$immutableClasses = array_merge(
            self::$immutableClasses,
            $immutableClasses
        );
    }

    public static function assertImmutable($value, $name) : void
    {
        if (! self::isImmutable($value)) {
            throw MutableValueException::new($name, $value);
        }
    }

    public static function isImmutable($value) : bool
    {
        if ($value === null || is_scalar($value)) {
            return true;
        }

        if (! is_object($value)) {
            return false;
        }

        foreach (self::$immutableClasses as $class) {
            if ($value instanceof $class) {
                return true;
            }
        }

        return false;
    }
}
