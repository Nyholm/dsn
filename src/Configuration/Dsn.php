<?php

namespace Nyholm\Dsn\Configuration;

/**
 * Base DSN object.
 *
 * Example:
 * - null://
 * - redis:?host[h1]&host[h2]&host[/foo:]
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Dsn
{
    /**
     * @var string|null
     */
    private $scheme;

    /**
     * @var array
     */
    private $parameters = [];

    public function __construct(?string $scheme, array $parameters = [])
    {
        $this->scheme = $scheme;
        $this->parameters = $parameters;
    }

    public function getScheme(): ?string
    {
        return $this->scheme;
    }

    public function withScheme(?string $scheme): self
    {
        $new = clone $this;
        $new->scheme = $scheme;

        return $new;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function getParameter(string $key, $default = null)
    {
        return \array_key_exists($key, $this->parameters) ? $this->parameters[$key] : $default;
    }

    /**
     * @param mixed $value
     */
    public function withParameter(string $key, $value): self
    {
        $new = clone $this;
        $new->parameters[$key] = $value;

        return $new;
    }

    public function withoutParameter(string $key): self
    {
        $new = clone $this;
        unset($new->parameters[$key]);

        return $new;
    }

    public function getHost(): ?string
    {
        return null;
    }

    public function getPort(): ?int
    {
        return null;
    }

    public function getPath(): ?string
    {
        return null;
    }

    public function getUser(): ?string
    {
        return null;
    }

    public function getPassword(): ?string
    {
        return null;
    }

    /**
     * @var string
     */
    public function __toString()
    {
        return sprintf('%s://%s', $this->getScheme(), empty($this->parameters) ? '' : '?'.http_build_query($this->parameters));
    }
}
