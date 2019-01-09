<?php
declare(strict_types=1);

namespace Immutable;

use JsonSerializable;
use RuntimeException;

class JsonBag extends Bag
{
    public function __construct(string $json, int $depth = 512, int $options = 0)
    {
        $data = json_decode($json, true, $depth, $options);
        if ($data === null) {
            throw new RuntimeException(
                "JSON decoding error: " . json_last_error_msg(),
                json_last_error()
            );
        }
        return parent::__construct($data);
    }
}
