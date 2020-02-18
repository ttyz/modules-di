<?php
namespace Module;
use PHPUnit\Framework\TestCase;

class NullModuleTest extends TestCase
{

    protected $module;
    protected function setUp() : void
    {
        $this->module = new NullModule();
    }

    public function testNullName()
    {
        $this->assertSame('null', $this->module->getName());
    }

    public function testRequires()
    {
        $this->assertEmpty($this->module->getRequirements());
    }
}
