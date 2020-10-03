<?php

declare(strict_types=1);

namespace Nyholm\Dsn\Configuration;

/**
 * A "URL like" DSN string.
 *
 * Example:
 * - memcached://user:password@127.0.0.1?weight=50
 * - 127.0.0.1:80
 * - amqp://127.0.0.1/%2f/messages
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Url extends Dsn
{
    use UserPasswordTrait;

    /**
     * @var string
     */
    private $host;

    /**
     * @var int|null
     */
    private $port;

    /**
     * @var string|null
     */
    private $path;

    /**
     * @param array{ user?: string|null, password?: string|null, } $authentication
     */
    public function __construct(?string $scheme, string $host, ?int $port = null, ?string $path = null, array $parameters = [], array $authentication = [])
    {
        $this->host = $host;
        $this->port = $port;
        $this->path = $path;
        $this->setAuthentication($authentication);
        parent::__construct($scheme, $parameters);
    }

    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return static
     */
    public function withHost(string $host)
    {
        $new = clone $this;
        $new->host = $host;

        return $new;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * @return static
     */
    public function withPort(?int $port)
    {
        $new = clone $this;
        $new->port = $port;

        return $new;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @return static
     */
    public function withPath(?string $path)
    {
        $new = clone $this;
        $new->path = $path;

        return $new;
    }

    /**
     * @var string
     */
    public function __toString()
    {
        $parameters = $this->getParameters();
        $scheme = $this->getScheme();

        return
            (empty($scheme) ? '' : $scheme.'://').
            $this->getUserInfoString().
            $this->getHost().
            (empty($this->port) ? '' : ':'.$this->port).
            ($this->getPath() ?? '').
            (empty($parameters) ? '' : '?'.http_build_query($parameters));
    }
}
