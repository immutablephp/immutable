<?php
declare(strict_types=1);

namespace Immutable\ValueObject\Uri;

use Immutable\Exception\InvalidValueException;

class HttpUriTest extends \PHPUnit\Framework\TestCase
{
    public function test()
    {
        $uri = new HttpUri('http://username:password@example.com:8080/foo/bar?baz=dib#zim');
        $this->assertSame('http', $uri->getScheme());
        $this->assertSame('username', $uri->getUser());
        $this->assertSame('password', $uri->getPass());
        $this->assertSame('example.com', $uri->getHost());
        $this->assertSame(8080, $uri->getPort());
        $this->assertSame('/foo/bar', $uri->getPath());
        $this->assertSame('baz=dib', $uri->getQuery());
        $this->assertSame('zim', $uri->getFragment());
    }

    public function testInvalidScheme()
    {
        $this->expectException(InvalidValueException::CLASS);
        $uri = new HttpUri('ftp://example.com');
    }

    public function testInvalidWithPassWithoutUser()
    {
        $uri = new HttpUri('http://example.com');

        $this->expectException(InvalidValueException::CLASS);
        $uri->withPass('no-good');
    }

    public function testWith()
    {
        $uri = new HttpUri('http://username:password@example.com:8080/foo/bar?baz=dib#zim');

        $uri = $uri
            ->withScheme('https')
            ->withHost('example.net')
            ->withPort(null)
            ->withPass(null)
            ->withUser(null)
            ->withPath(null)
            ->withQuery(null)
            ->withFragment(null);

        $this->assertSame('https://example.net', $uri->get());
    }
}
