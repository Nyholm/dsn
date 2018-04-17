<?php

namespace Nyholm\Dsn\Test;

use Nyholm\DSN;
use PHPUnit\Framework\TestCase;

class DsnTest extends TestCase
{
    public function testMySql()
    {
        $inputString = 'mysql://root:root_pass@127.0.0.1:3306/test_db';
        $dsn = new DSN($inputString);

        $this->assertTrue($dsn->isValid());
        $this->assertEquals('mysql', $dsn->getProtocol());
        $this->assertEquals('root', $dsn->getUsername());
        $this->assertEquals('root_pass', $dsn->getPassword());
        $this->assertEquals('127.0.0.1', $dsn->getFirstHost());
        $this->assertEquals('3306', $dsn->getFirstPort());
        $this->assertEquals('test_db', $dsn->getDatabase());
        $this->assertEquals($inputString, $dsn->getDsn());

        $auth = $dsn->getAuthentication();
        $this->assertArrayHasKey('username', $auth);
        $this->assertArrayHasKey('password', $auth);

        $this->assertEquals('root', $auth['username']);
        $this->assertEquals('root_pass', $auth['password']);
    }

    public function testParameters()
    {
        $dsn = new DSN('mysql://127.0.0.1/test_db?foo=bar&baz=biz&aa');

        $this->assertTrue($dsn->isValid());
        $parameters = $dsn->getParameters();

        $this->assertArrayHasKey('foo', $parameters);
        $this->assertArrayHasKey('baz', $parameters);
        $this->assertArrayHasKey('aa', $parameters);

        $this->assertEquals('bar', $parameters['foo']);
        $this->assertEquals('biz', $parameters['baz']);
        $this->assertNull($parameters['aa']);
    }

    public function testParametersOnWithoutDatabase()
    {
        $dsn = new DSN('mysql://127.0.0.1?foo=bar');
        $this->assertTrue($dsn->isValid());
        $parameters = $dsn->getParameters();
        $this->assertArrayHasKey('foo', $parameters);
        $this->assertEquals('bar', $parameters['foo']);
    }

    public function authenticationProvider()
    {
        return [
            ['mysql://root:root_pass@127.0.0.1:3306/test_db', 'root', 'root_pass', 'username', 'password'],
            ['mysql://127.0.0.1:3306/test_db', null, null, 'username', 'password'],
        ];
    }

    /**
     * @dataProvider authenticationProvider
     */
    public function testAuthentication($dsnString, $expectedUserName, $expectedPassword, $expectedAuthUserName, $expectedAuthPassword)
    {
        $dsn = new DSN($dsnString);
        $this->assertTrue($dsn->isValid());
        $this->assertEquals($expectedUserName, $dsn->getUsername());
        $this->assertEquals($expectedPassword, $dsn->getPassword());

        $auth = $dsn->getAuthentication();
        $this->assertArrayHasKey($expectedAuthUserName, $auth);
        $this->assertArrayHasKey($expectedAuthPassword, $auth);

        $this->assertEquals($expectedUserName, $auth['username']);
        $this->assertEquals($expectedPassword, $auth['password']);
    }

    public function testPartlyMissing()
    {
        $dsn = new DSN('mysql://127.0.0.1/test_db');

        $this->assertTrue($dsn->isValid());
        $this->assertEquals('mysql', $dsn->getProtocol());
        $this->assertEquals('127.0.0.1', $dsn->getFirstHost());
        $this->assertEquals('test_db', $dsn->getDatabase());
        $this->assertNull($dsn->getUsername());
        $this->assertNull($dsn->getPassword());
        $this->assertNull($dsn->getFirstPort());
    }

    public function testMissingPassword()
    {
        $dsn = new DSN('mysql://root@127.0.0.1:3306/test_db');

        $this->assertTrue($dsn->isValid());
        $this->assertEquals('mysql', $dsn->getProtocol());
        $this->assertEquals('127.0.0.1', $dsn->getFirstHost());
        $this->assertEquals('test_db', $dsn->getDatabase());
        $this->assertEquals('root', $dsn->getUsername());
        $this->assertNull($dsn->getPassword());
    }

    public function invalidDsnProvider()
    {
        return [
            ['myql:127.0.0.1/test_db'],
            ['myql://'],
        ];
    }

    /**
     * @dataProvider invalidDsnProvider
     */
    public function testInvalid($dsnString)
    {
        $dsn = new DSN($dsnString);
        $this->assertFalse($dsn->isValid());
    }
}
