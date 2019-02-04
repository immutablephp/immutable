<?php
/**
 * @package immutablephp/immutable
 * @license http://opensource.org/licenses/MIT MIT
 */
declare(strict_types=1);

namespace Immutable;

use Immutable\Exception\ImmutableObjectException;

abstract class Immutable
{
    private $__INITIALIZED__ = false;

    public function __construct()
    {
        if ($this->__INITIALIZED__) {
            throw ImmutableObjectException::new($this);
        }
        $this->__INITIALIZED__ = true;
    }

    final public function __set(string $key, $val) : void
    {
        throw ImmutableObjectException::new($this);
    }

    final public function __unset(string $key) : void
    {
        throw ImmutableObjectException::new($this);
    }

    final public function offsetSet($key, $value)
    {
        throw ImmutableObjectException::new($this);
    }

    final public function offsetUnset($key)
    {
        throw ImmutableObjectException::new($this);
    }

    final protected function isInitialized()
    {
        return $this->__INITIALIZED__;
    }
}
