<?php

namespace Nyholm\Dsn\Test\Configuration;

use Nyholm\Dsn\Configuration\Dsn;
use Nyholm\Dsn\DsnParser;
use Nyholm\Dsn\Exception\MissingRequiredParameterException;
use PHPUnit\Framework\TestCase;

class DsnTest extends TestCase
{
    public function testGetRequiredParameter()
    {
        $dsn = DsnParser::parse('amqp://localhost?iAmRequired=yes');

        $this->assertSame('yes', $dsn->getRequiredParameter('iAmRequired'));
    }

    public function testGetRequiredParameterThrowsWhenItDoesNotExist()
    {
        $dsn = DsnParser::parse('amqp://localhost');

        $this->expectException(MissingRequiredParameterException::class);

        $dsn->getRequiredParameter('iAmRequired');
    }

    public function testGetRequiredParameterThrowsWhenItsEmpty()
    {
        $dsn = DsnParser::parse('amqp://localhost?iAmRequired=');

        $this->expectException(MissingRequiredParameterException::class);

        $dsn->getRequiredParameter('iAmRequired');
    }
}
