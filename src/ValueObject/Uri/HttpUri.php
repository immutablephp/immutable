<?php
/**
 * @package immutablephp/immutable
 * @license http://opensource.org/licenses/MIT MIT
 */
declare(strict_types=1);

namespace Immutable\ValueObject\Uri;

class HttpUri extends Uri
{
    protected $user = null;
    protected $pass = null;
    protected $host = null;
    protected $port = null;
    protected $path = null;
    protected $query = null;
    protected $fragment = null;

    public function __construct(string $identifier)
    {
        $parts = parse_url($identifier);
        $this->withParts($parts);
        parent::__construct();
    }

    public function getHost() : string
    {
        return $this->host;
    }

    public function getPort() : ?int
    {
        return $this->port;
    }

    public function getUser() : ?string
    {
        return $this->user;
    }

    public function getPass() : ?string
    {
        return $this->pass;
    }

    public function getPath() : ?string
    {
        return $this->path;
    }

    public function getQuery() : ?string
    {
        return $this->query;
    }

    public function getFragment() : ?string
    {
        return $this->fragment;
    }

    public function withScheme(string $scheme) : Uri
    {
        return $this->withParts(['scheme' => $scheme]);
    }

    public function withHost(string $host) : HttpUri
    {
        return $this->withParts(['host' => $host]);
    }

    public function withPort(?int $port) : HttpUri
    {
        return $this->withParts(['port' => $port]);
    }

    public function withUser(?string $user) : HttpUri
    {
        return $this->withParts(['user' => $user]);
    }

    public function withPass(?string $pass) : HttpUri
    {
        return $this->withParts(['pass' => $pass]);
    }

    public function withPath(?string $path) : HttpUri
    {
        return $this->withParts(['path' => $path]);
    }

    public function withQuery(?string $query) : HttpUri
    {
        return $this->withParts(['query' => $query]);
    }

    public function withFragment(?string $fragment) : HttpUri
    {
        return $this->withParts(['fragment' => $fragment]);
    }

    protected function assertValid() : void
    {
        if ($this->scheme != 'http' && $this->scheme != 'https') {
            $this->throwInvalidValueException(
                '$scheme',
                "'http' or 'https'",
                $this->scheme
            );
        }

        // can user $user and no $pass,
        // but cannot have $pass and no $user
        if ($this->pass !== null && $this->user === null) {
            $this->throwInvalidValueException(
                '$user',
                'a non-empty $user with non-empty $pass',
                $this->user
            );
        }
    }

    protected function setIdentifier() : void
    {
        $pass     = isset($this->pass)     ? ':' . $this->pass        : '';
        $pass     = ($this->user || $pass) ? "$pass@"                 : '';
        $port     = isset($this->port)     ? ':' . $this->port        : '';
        $query    = isset($this->query)    ? '?' . $this->query       : '';
        $fragment = isset($this->fragment) ? '#' . $this->fragment    : '';
        $this->identifier = $this->scheme . '://'
            . $this->user . $pass
            . $this->host . $port
            . $this->path
            . $query
            . $fragment;
    }
}
