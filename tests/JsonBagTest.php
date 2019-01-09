<?php
declare(strict_types=1);

namespace Immutable;

use RuntimeException;

class JsonBagTest extends \PHPUnit\Framework\TestCase
{
    protected $data = [
        'foo' => 'bar',
        'baz' => [
            'dib' => [
                'zim',
                'gir',
            ],
        ],
    ];

    public function testValidJson()
    {
        $json = json_encode($this->data);
        $bag = new JsonBag($json);

        $actual = json_encode($bag);
        $this->assertSame($json, $actual);
    }

    public function testInvalidJson()
    {
        $this->expectException(RuntimeException::CLASS);
        $bag = new JsonBag("bad-data");
    }
}
