<?php

declare(strict_types=1);

namespace Nyholm\Dsn\Exception;

/**
 * Thrown when you cannot use functions in a DSN.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class FunctionsNotAllowedException extends InvalidDsnException
{
    public function __construct(string $dsn)
    {
        parent::__construct($dsn, 'Function are not allowed in this DSN');
    }
}
