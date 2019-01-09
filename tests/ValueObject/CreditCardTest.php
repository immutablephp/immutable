<?php
declare(strict_types=1);

namespace Immutable\ValueObject;

use Immutable\Exception\InvalidValueException;

class CreditCardTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider provideValid
     */
    public function testValid($number, $brand)
    {
        $creditCard = new CreditCard($number);
        $this->assertSame($number, $creditCard->getNumber());
        $this->assertSame($brand, $creditCard->getBrand());
    }

    public function provideValid()
    {
        return [
            ['340-3161-9380-9364', CreditCard::AMERICAN_EXPRESS],
            ['30351042633884', CreditCard::DINERS_CLUB],
            ['5376 7473 9720 8720', CreditCard::MASTERCARD],
            ['4024.0071.5336.1885', CreditCard::VISA],
            ['4024 007 193 879', CreditCard::VISA],
        ];
    }

    public function testInvalid()
    {
        $this->expectException(InvalidValueException::CLASS);
        $creditCard = new CreditCard('foobar');
    }
}
