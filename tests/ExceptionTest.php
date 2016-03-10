<?php

require_once "vendor/autoload.php";

class ExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $exc = new Rkryvako\Package\Exception();
        $this->assertInstanceOf('Rkryvako\Package\Exception', $exc);
        $this->assertInstanceOf('\Exception', $exc);
    }
}
