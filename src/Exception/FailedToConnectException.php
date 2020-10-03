<?php

declare(strict_types=1);

namespace Nyholm\Dsn\Exception;

/**
 * When we cannot connect to Redis, Memcached etc.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class FailedToConnectException extends InvalidArgumentException
{
}
