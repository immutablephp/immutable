<?php
declare(strict_types=1);

namespace Immutable\ValueObject;

class CreditCard extends ValueObject
{
    const AMERICAN_EXPRESS = 'American Express';
    const DINERS_CLUB = 'Diners Club';
    const DISCOVER = 'Discover';
    const JCB = 'JCB';
    const MASTERCARD = 'MasterCard';
    const VISA = 'Visa';
    const UNKNOWN = '';

    protected $number = '';

    protected $brand = '';

    public function __construct(string $number)
    {
        $this->withNumber($number);
        parent::__construct();
    }

    public function withNumber(string $number) : self
    {
        $digits = preg_replace('([^0-9])', '', $number);
        $this->assertValid($digits, $number);
        return $this->with([
            'number' => $number,
            'brand' => $this->determineBrand($digits)
        ]);
    }

    public function getNumber() : string
    {
        return $this->number;
    }

    public function getBrand() : string
    {
        return $this->brand;
    }

    protected function assertValid(string $digits, string $number) : void
    {
        if (! $this->isValid($digits)) {
            $this->throwInvalidValueException(
                '$number',
                'a valid credit card number',
                $number
            );
        }
    }

    protected function isValid(string $digits) : bool
    {
        // is it composed only of digits?
        if (! ctype_digit($digits)) {
            return false;
        }

        // luhn mod-10 algorithm: https://gist.github.com/1287893
        $sumTable = array(
            array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9),
            array(0, 2, 4, 6, 8, 1, 3, 5, 7, 9),
        );

        $sum = 0;
        $flip = 0;

        for ($i = strlen($digits) - 1; $i >= 0; $i--) {
            $sum += $sumTable[$flip++ & 0x1][$digits[$i]];
        }

        return $sum % 10 === 0;
    }

    protected function determineBrand($digits) : string
    {
        $digits = str_replace(array(' ', '-', '.'), '', $digits);

        $brands = [
            self::AMERICAN_EXPRESS => '/^3[47]\d{13}$/',
            self::DINERS_CLUB => '/^3(?:0[0-5]|[68]\d)\d{11}$/',
            self::DISCOVER => '/^6(?:011|5\d{2})\d{12}$/',
            self::JCB => '/^(?:2131|1800|35\d{3})\d{11}$/',
            self::MASTERCARD => '/^5[1-5]\d{14}$/',
            self::VISA => '/^4\d{12}(?:\d{3})?$/',
        ];

        foreach ($brands as $brand => $regex) {
            if (preg_match($regex, $digits)) {
                return $brand;
            }
        }

        return self::UNKNOWN;
    }
}
