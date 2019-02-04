<?php
/**
 * @package immutablephp/immutable
 * @license http://opensource.org/licenses/MIT MIT
 */
declare(strict_types=1);

namespace Immutable\ValueObject;

class Uuid extends ValueObject
{
    protected $identifier;

    public static function newVersion4() : self
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        $identifier = vsprintf(
            '%s%s-%s-%s-%s-%s%s%s',
            str_split(bin2hex($data), 4)
        );
        return new static($identifier);
    }

    public function __construct(string $identifier)
    {
        $this->withIdentifier($identifier);
        parent::__construct();
    }

    public function withIdentifier(string $identifier) : self
    {
        $this->assertValid($identifier);
        return $this->with([
            'identifier' => $identifier,
        ]);
    }

    public function getIdentifier() : string
    {
        return $this->identifier;
    }

    protected function assertValid(string $identifier) : void
    {
        if (! $this->isValid($identifier)) {
            $this->throwInvalidValueException(
                '$identifier',
                'a valid UUID',
                $identifier
            );
        }
    }

    protected function isValid(string $identifier) : bool
    {
        return $this->isValidCanonical($identifier)
            || $this->isValidHexOnly($identifier);
    }

    protected function isValidCanonical(string $identifier) : bool
    {
        $regex = '/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i';
        return (bool) preg_match($regex, $identifier);
    }

    protected function isValidHexOnly(string $identifier) : bool
    {
        $regex = '/^[a-f0-9]{32}$/i';
        return (bool) preg_match($regex, $identifier);
    }
}
