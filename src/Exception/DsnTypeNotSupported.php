<?php



declare(strict_types=1);

namespace Nyholm\Dsn\Exception;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class DsnTypeNotSupported extends InvalidDsnException
{
    public static function onlyUrl($dsn): self
    {
        return new self($dsn, 'Only DSNs of type "URL" is supported.');
    }

    public static function onlyPath($dsn): self
    {
        return new self($dsn, 'Only DSNs of type "path" is supported.');
    }
}
