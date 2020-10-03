<?php

declare(strict_types=1);

namespace Nyholm\Dsn\Configuration;

/**
 * A function with one or more arguments. The default function is called "dsn".
 * Other function may be "failover" or "roundrobin".
 *
 * Examples:
 * - failover(redis://localhost memcached://example.com)
 * - dsn(amqp://guest:password@localhost:1234)
 * - foobar(amqp://guest:password@localhost:1234 amqp://localhost)?delay=10
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class DsnFunction
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $arguments;

    /**
     * @var array
     */
    private $parameters;

    public function __construct(string $name, array $arguments, array $parameters = [])
    {
        $this->name = $name;
        $this->arguments = $arguments;
        $this->parameters = $parameters;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<DsnFunction|Dsn>
     */
    public function getArguments(): array
    {
        return $this->arguments;
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
     *
     * @return static
     */
    public function withParameter(string $key, $value)
    {
        $new = clone $this;
        $new->parameters[$key] = $value;

        return $new;
    }

    /**
     * @return static
     */
    public function withoutParameter(string $key)
    {
        $new = clone $this;
        unset($new->parameters[$key]);

        return $new;
    }

    /**
     * @return DsnFunction|Dsn
     */
    public function first()
    {
        return reset($this->arguments);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s(%s)%s', $this->getName(), implode(' ', $this->getArguments()), empty($this->parameters) ? '' : '?'.http_build_query($this->parameters));
    }
}
