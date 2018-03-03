<?php

namespace Nyholm\Dsn\Test;

use Nyholm\DSN;
use PHPUnit\Framework\TestCase;

class DsnTest extends TestCase
{
    public function testMySql()
    {
        $dsn = new DSN('mysql://root:root_pass@127.0.0.1:3306/test_db');

        $this->assertTrue($dsn->isValid());
        $this->assertEquals('mysql', $dsn->getProtocol());
        $this->assertEquals('root', $dsn->getUsername());
        $this->assertEquals('root_pass', $dsn->getPassword());
        $this->assertEquals('127.0.0.1', $dsn->getFirstHost());
        $this->assertEquals('3306', $dsn->getFirstPort());
        $this->assertEquals('test_db', $dsn->getDatabase());
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
}
