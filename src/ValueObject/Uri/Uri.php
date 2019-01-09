<?php
declare(strict_types=1);

namespace Immutable\ValueObject\Uri;

use Immutable\ValueObject\ValueObject;

abstract class Uri extends ValueObject
{
    protected $identifier = null;

    protected $scheme = null;

    public function get() : string
    {
        return $this->identifier;
    }

    public function getScheme() : string
    {
        return $this->scheme;
    }

    public function withParts(array $parts) : Uri
    {
        foreach ($parts as $key => $value) {
            $parts[$key] = $this->normalizePart($value);
        }
        $clone = $this->with($parts);
        $clone->assertValid($clone);
        $clone->setIdentifier($clone);
        return $clone;
    }

    protected function normalizePart($value)
    {
        if (is_null($value)) {
            return $value;
        }

        if (is_int($value)) {
            return $value !== 0 ? $value : null;
        }

        return trim($value) !== '' ? $value : null;
    }

    abstract protected function assertValid() : void;

    abstract protected function setIdentifier() : void;
}
