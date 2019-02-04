<?php
/**
 * @package immutablephp/immutable
 * @license http://opensource.org/licenses/MIT MIT
 */
declare(strict_types=1);

namespace Immutable\ValueObject;

class Email extends ValueObject
{
    protected $address = '';

    public function __construct(string $address)
    {
        $this->withAddress($address);
        parent::__construct();
    }

    public function withAddress(string $address) : self
    {
        $this->assertValid($address);
        return parent::with([
            'address' => $address,
        ]);
    }

    public function getAddress() : string
    {
        return $this->address;
    }

    protected function assertValid(string $address) : void
    {
        if (! filter_var($address, FILTER_VALIDATE_EMAIL)) {
            $this->throwInvalidValueException(
                '$address',
                'a valid email address',
                $address
            );
        }
    }
}
