<?php

namespace Nyholm;

/**
 * Parse a DSN string to get its parts.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class DSN
{
    /**
     * @var string
     */
    private $dsn;

    /**
     * @var string
     */
    private $protocol;

    /**
     * @var array
     */
    private $authentication;

    /**
     * @var array
     */
    private $hosts;

    /**
     * @var int
     */
    private $database;

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * Constructor.
     *
     * @param string $dsn
     */
    public function __construct($dsn)
    {
        $this->dsn = $dsn;
        $this->parseDsn($dsn);
    }

    /**
     * @return string
     */
    public function getDsn()
    {
        return $this->dsn;
    }

    /**
     * @return string
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * @return int|null
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @return array
     */
    public function getHosts()
    {
        return $this->hosts;
    }

    /**
     * @return null|string
     */
    public function getFirstHost()
    {
        return $this->hosts[0]['host'];
    }

    /**
     * @return null|int
     */
    public function getFirstPort()
    {
        return $this->hosts[0]['port'];
    }

    /**
     * @return array
     */
    public function getAuthentication()
    {
        return $this->authentication;
    }

    public function getUsername()
    {
        return $this->authentication['username'];
    }

    public function getPassword()
    {
        return $this->authentication['password'];
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        if (null === $this->getProtocol()) {
            return false;
        }

        if (empty($this->getHosts())) {
            return false;
        }

        return true;
    }

    private function parseProtocol($dsn)
    {
        $regex = '/^(\w+):\/\//i';

        preg_match($regex, $dsn, $matches);

        if (isset($matches[1])) {
            $protocol = $matches[1];
            $this->protocol = $protocol;
        }
    }

    /**
     * @param string $dsn
     */
    private function parseDsn($dsn)
    {
        $this->parseProtocol($dsn);
        if (null === $this->getProtocol()) {
            return;
        }

        // Remove the protocol
        $dsn = str_replace($this->protocol.'://', '', $dsn);

        // Parse and remove auth if they exist
        if (false === $pos = strrpos($dsn, '@')) {
            $this->authentication = ['username' => null, 'password' => null];
        } else {
            $temp = explode(':', str_replace('\@', '@', substr($dsn, 0, $pos)));
            $dsn = substr($dsn, $pos + 1);

            $auth = [];
            if (2 === count($temp)) {
                $auth['username'] = $temp[0];
                $auth['password'] = $temp[1];
            } else {
                $auth['username'] = $temp[0];
                $auth['password'] = null;
            }

            $this->authentication = $auth;
        }

        if (false !== strpos($dsn, '?')) {
            if (false === strpos($dsn, '/')) {
                $dsn = str_replace('?', '/?', $dsn);
            }
        }

        $temp = explode('/', $dsn);
        $this->parseHosts($temp[0]);

        if (isset($temp[1])) {
            $params = $temp[1];
            $temp = explode('?', $params);
            $this->database = empty($temp[0]) ? null : $temp[0];
            if (isset($temp[1])) {
                $this->parseParameters($temp[1]);
            }
        }
    }

    private function parseHosts($hostString)
    {
        preg_match_all('/(?P<host>[\w-._]+)(?::(?P<port>\d+))?/mi', $hostString, $matches);

        $hosts = [];
        foreach ($matches['host'] as $index => $match) {
            $port = !empty($matches['port'][$index])
                ? (int) $matches['port'][$index]
                : null;
            $hosts[] = ['host' => $match, 'port' => $port];
        }

        $this->hosts = $hosts;
    }

    /**
     * @param string $params
     */
    private function parseParameters($params)
    {
        $parameters = explode('&', $params);

        foreach ($parameters as $parameter) {
            $kv = explode('=', $parameter, 2);
            $this->parameters[$kv[0]] = isset($kv[1]) ? $kv[1] : null;
        }
    }
}
