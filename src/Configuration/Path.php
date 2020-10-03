<?php

declare(strict_types=1);

namespace Nyholm\Dsn\Configuration;

use Nyholm\Dsn\Exception\InvalidArgumentException;

/**
 * A "path like" DSN string.
 *
 * Example:
 * - redis:///var/run/redis/redis.sock
 * - memcached://user:password@/var/local/run/memcached.socket?weight=25
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Path extends Dsn
{
    use UserPasswordTrait;
    /**
     * @var string
     */
    private $path;

    /**
     * @param array{ user?: string|null, password?: string|null, } $authentication
     */
    public function __construct(string $scheme, string $path, array $parameters = [], array $authentication = [])
    {
        $this->path = $path;
        $this->setAuthentication($authentication);
        parent::__construct($scheme, $parameters);
    }

    public function getScheme(): string
    {
        return parent::getScheme();
    }

    /**
     * @return static
     */
    public function withScheme(?string $scheme)
    {
        if (null === $scheme || '' === $scheme) {
            throw new InvalidArgumentException('A Path must have a schema');
        }

        return parent::withScheme($scheme);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return static
     */
    public function withPath(string $path)
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

        return
            $this->getScheme().'://'.
            $this->getUserInfoString().
            $this->getPath().
            (empty($parameters) ? '' : '?'.http_build_query($parameters));
    }
}
