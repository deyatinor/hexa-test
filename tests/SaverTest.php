<?php

require_once "vendor/autoload.php";

class SaverTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $conn = new Rkryvako\Package\Saver("12345");
        $this->assertInstanceOf('Rkryvako\Package\Saver', $conn);
    }
    public function testConstructNotEnoughParams()
    {
        $conn = new Rkryvako\Package\Saver();
        $this->assertInstanceOf('Rkryvako\Package\Saver', $conn);
    }
    public function testSearchPictures()
    {
        $this->assertTrue(true);
    }
    public function testSavePictures()
    {
        $this->assertTrue(true);
    }
}
