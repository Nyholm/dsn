<?php

declare(strict_types=1);

namespace Nyholm\Dsn\Configuration;

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

    public function getPath(): string
    {
        return $this->path;
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
