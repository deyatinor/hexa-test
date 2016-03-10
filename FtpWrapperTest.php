<?php

require_once "vendor/autoload.php";

class FtpWrapperTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $conn = null;
        $wrapper = new Rkryvako\Package\FtpWrapper($conn);
        $this->assertInstanceOf('Rkryvako\Package\FtpWrapper', $wrapper);
    }
    public function testCall()
    {
        $conn = null;
        $wrapper = new Rkryvako\Package\FtpWrapper($conn);
        $this->assertInstanceOf('Rkryvako\Package\FtpWrapper', $wrapper->connect("ftp://ftp.univ-rennes1.frpub/"));
    }
    public function testException()
    {
        $conn = null;
        $wrapper = new Rkryvako\Package\FtpWrapper($conn);
        $this->assertInstanceOf('Rkryvako\Package\FtpWrapper', $wrapper->doNotExist());
        //$this->expectException($wrapper->doNotExist());
    }
    public function testConnect()
    {
        $conn = null;
        $wrapper = new Rkryvako\Package\FtpWrapper($conn);
        $this->assertInstanceOf('Rkryvako\Package\FtpWrapper', $wrapper->connect("ftp://ftp.univ-rennes1.frpub/"));
    }
    public function testSslConnect()
    {
        $conn = null;
        $wrapper = new Rkryvako\Package\FtpWrapper($conn);
        $this->assertInstanceOf('Rkryvako\Package\FtpWrapper', $wrapper->connect("sftp://ftp.univ-rennes1.frpub/"));
    }
}
