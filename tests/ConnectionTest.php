<?php

require_once "vendor/autoload.php";

class ConnectionTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $conn = new Rkryvako\Package\Connection("12345");
        $this->assertInstanceOf('Rkryvako\Package\Connection', $conn);
    }
    public function testConstructNotEnoughParams()
    {
        $conn = new Rkryvako\Package\Connection();
        $this->assertInstanceOf('Rkryvako\Package\Connection', $conn);
    }
    public function testConnect()
    {
        $conn = new Rkryvako\Package\Connection("ftp.sunet.se");
        $this->assertInstanceOf('Rkryvako\Package\Connection', $conn->connect());
    }
    public function testNlist()
    {
        $this->assertTrue(true);
    }
}
