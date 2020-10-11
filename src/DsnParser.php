<?php

declare(strict_types=1);

namespace Nyholm\Dsn;

use Nyholm\Dsn\Configuration\Dsn;
use Nyholm\Dsn\Configuration\DsnFunction;
use Nyholm\Dsn\Configuration\Path;
use Nyholm\Dsn\Configuration\Url;
use Nyholm\Dsn\Exception\DsnTypeNotSupported;
use Nyholm\Dsn\Exception\FunctionsNotAllowedException;
use Nyholm\Dsn\Exception\SyntaxException;

/**
 * A factory class to parse a string and create a DsnFunction.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class DsnParser
{
    private const FUNCTION_REGEX = '#^([a-zA-Z0-9\+-]+):?\((.*)\)(?:\?(.*))?$#';
    private const ARGUMENTS_REGEX = '#([^\s,]+\(.+\)(?:\?.*)?|[^\s,]+)#';
    private const UNRESERVED = 'a-zA-Z0-9-\._~';
    private const SUB_DELIMS = '!\$&\'\(\}\*\+,;=';

    /**
     * Parse A DSN thay may contain functions. If no function is present in the
     * string, then a "dsn()" function will be added.
     *
     * @throws SyntaxException
     */
    public static function parseFunc(string $dsn): DsnFunction
    {
        // Detect a function or add default function
        $parameters = [];
        if (1 === preg_match(self::FUNCTION_REGEX, $dsn, $matches)) {
            $functionName = $matches[1];
            $arguments = $matches[2];
            parse_str($matches[3] ?? '', $parameters);
        } else {
            $functionName = 'dsn';
            $arguments = $dsn;
        }

        if (empty($arguments)) {
            throw new SyntaxException($dsn, 'dsn' === $functionName ? 'The DSN is empty' : 'A function must have arguments, an empty string was provided.');
        }

        // explode arguments and respect function parentheses
        if (preg_match_all(self::ARGUMENTS_REGEX, $arguments, $matches)) {
            $arguments = $matches[1];
        }

        return new DsnFunction($functionName, array_map(\Closure::fromCallable([self::class, 'parseArguments']), $arguments), $parameters);
    }

    /**
     * Parse a DSN without functions.
     *
     * @throws FunctionsNotAllowedException if the DSN contains a function
     * @throws SyntaxException
     */
    public static function parse(string $dsn): Dsn
    {
        if (1 === preg_match(self::FUNCTION_REGEX, $dsn, $matches)) {
            if ('dsn' === $matches[1]) {
                return self::parse($matches[2]);
            }
            throw new FunctionsNotAllowedException($dsn);
        }

        return self::getDsn($dsn);
    }

    public static function parseUrl(string $dsn): Url
    {
        $dsn = self::parse($dsn);
        if (!$dsn instanceof Url) {
            throw DsnTypeNotSupported::onlyUrl($dsn);
        }

        return $dsn;
    }

    public static function parsePath(string $dsn): Path
    {
        $dsn = self::parse($dsn);
        if (!$dsn instanceof Path) {
            throw DsnTypeNotSupported::onlyPath($dsn);
        }

        return $dsn;
    }

    /**
     * @return DsnFunction|Dsn
     */
    private static function parseArguments(string $dsn)
    {
        // Detect a function exists
        if (1 === preg_match(self::FUNCTION_REGEX, $dsn)) {
            return self::parseFunc($dsn);
        }

        // Assert: $dsn does not contain any functions.
        return self::getDsn($dsn);
    }

    /**
     * @throws SyntaxException
     */
    private static function getDsn(string $dsn): Dsn
    {
        // Find the scheme if it exists and trim the double slash.
        if (!preg_match('#^(?:(?<alt>['.self::UNRESERVED.self::SUB_DELIMS.'%]+:[0-9]+(?:[/?].*)?)|(?<scheme>[a-zA-Z0-9\+-\.]+):(?://)?(?<dsn>.*))$#', $dsn, $matches)) {
            throw new SyntaxException($dsn, 'A DSN must contain a scheme [a-zA-Z0-9\+-\.]+ and a colon.');
        }
        $scheme = null;
        $dsn = $matches['alt'];
        if (!empty($matches['scheme'])) {
            $scheme = $matches['scheme'];
            $dsn = $matches['dsn'];
        }

        if ('' === $dsn) {
            return new Dsn($scheme);
        }

        // Parse user info
        if (!preg_match('#^(?:(['.self::UNRESERVED.self::SUB_DELIMS.'%]+)?(?::(['.self::UNRESERVED.self::SUB_DELIMS.'%]*))?@)?([^\s@]+)$#', $dsn, $matches)) {
            throw new SyntaxException($dsn, 'The provided DSN is not valid. Maybe you need to url-encode the user/password?');
        }

        $authentication = [
            'user' => empty($matches[1]) ? null : urldecode($matches[1]),
            'password' => empty($matches[2]) ? null : urldecode($matches[2]),
        ];

        if ('?' === $matches[3][0]) {
            $parts = self::explodeUrl('http://localhost'.$matches[3], $dsn);

            return new Dsn($scheme, self::getQuery($parts));
        }

        if ('/' === $matches[3][0]) {
            $parts = self::explodeUrl($matches[3], $dsn);

            return new Path($scheme, $parts['path'], self::getQuery($parts), $authentication);
        }

        $parts = self::explodeUrl('http://'.$matches[3], $dsn);

        return new Url($scheme, $parts['host'], $parts['port'] ?? null, $parts['path'] ?? null, self::getQuery($parts), $authentication);
    }

    /**
     * Parse URL and throw exception if the URL is not valid.
     *
     * @throws SyntaxException
     */
    private static function explodeUrl(string $url, string $dsn): array
    {
        $url = parse_url($url);
        if (false === $url) {
            throw new SyntaxException($dsn, 'The provided DSN is not valid.');
        }

        return $url;
    }

    /**
     * Parse query params into an array.
     */
    private static function getQuery(array $parts): array
    {
        $query = [];
        if (isset($parts['query'])) {
            parse_str($parts['query'], $query);
        }

        return $query;
    }
}
