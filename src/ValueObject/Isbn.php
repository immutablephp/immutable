<?php
/**
 * @package immutablephp/immutable
 * @license http://opensource.org/licenses/MIT MIT
 */
declare(strict_types=1);

namespace Immutable\ValueObject;

class Isbn extends ValueObject
{
    protected $identifier = '';

    public function __construct(string $identifier)
    {
        $this->withIdentifier($identifier);
        parent::__construct();
    }

    public function withIdentifier(string $identifier) : self
    {
        $this->assertValid($identifier);
        return $this->with(['identifier' => $identifier]);
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
                'a valid ISBN identifier',
                $identifier
            );
        }
    }

    protected function isValid(string $identifier) : bool
    {
        $identifier = preg_replace('/(?:(?!([0-9|X$])).)*/', '', $identifier);
        return preg_match('/^[0-9]{10,13}$|^[0-9]{9}X$/', $identifier)
            && ($this->isValid13Digit($identifier) || $this->isValid10Digit($identifier));
    }

    protected function isValid13Digit(string $identifier) : bool
    {
        if (strlen($identifier) != 13) {
            return false;
        }

        $even = $identifier{0}  + $identifier{2}  + $identifier{4} + $identifier{6}
               + $identifier{8} + $identifier{10} + $identifier{12};

        $odd   = $identifier{1} + $identifier{3} + $identifier{5} + $identifier{7}
               + $identifier{9} + $identifier{11};

        $sum   = $even + ($odd * 3);

        if ($sum % 10) {
            return false;
        }

        return true;
    }

    protected function isValid10Digit(string $identifier) : bool
    {
        if (strlen($identifier) != 10) {
            return false;
        }

        $sum = $identifier{0}
             + $identifier{1} * 2
             + $identifier{2} * 3
             + $identifier{3} * 4
             + $identifier{4} * 5
             + $identifier{5} * 6
             + $identifier{6} * 7
             + $identifier{7} * 8
             + $identifier{8} * 9;

        if ($identifier{9} == 'X') {
            $sum += 100;
        } else {
            $sum += $identifier{9} * 10;
        }

        if ($sum % 11) {
            return false;
        }

        return true;
    }
}
