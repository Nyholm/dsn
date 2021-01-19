<?php

declare(strict_types=1);

namespace Nyholm\Dsn\Exception;

class MissingRequiredParameterException extends InvalidDsnException
{
    public function __construct(string $option, string $dsn)
    {
        parent::__construct($dsn, sprintf('The parameter "%s" is required but missing.', $option));
    }
}
