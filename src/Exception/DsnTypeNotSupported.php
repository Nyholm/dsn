<?php

declare(strict_types=1);

namespace Nyholm\Dsn\Exception;

use Nyholm\Dsn\Configuration\Dsn;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class DsnTypeNotSupported extends InvalidDsnException
{
    /**
     * @param Dsn|string $dsn
     */
    public static function onlyUrl($dsn): self
    {
        return new self((string) $dsn, 'Only DSNs of type "URL" is supported.');
    }

    /**
     * @param Dsn|string $dsn
     */
    public static function onlyPath($dsn): self
    {
        return new self((string) $dsn, 'Only DSNs of type "path" is supported.');
    }
}
