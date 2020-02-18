<?php

namespace Module;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Container\ContainerInterface;

class ApplicationTest extends TestCase
{
    public function testContainer()
    {
        $app = new Application();
        $this->assertInstanceOf(ContainerInterface::class, $app);
    }

    public function testInit()
    {
        $foo = $this->prophesize(Module::class);
        $bar = $this->prophesize(Module::class);

        $foo->init(Argument::type(Di::class))->shouldBeCalled();
        $bar->init(Argument::type(Di::class))->shouldBeCalled();

        $app = new Application($foo->reveal(), $bar->reveal());
    }

    public function testGet()
    {
        $module = new class implements Module {

            public function init(Di $di): void
            {
                $di->set('foo', function () {
                    return 'bar';
                });
            }
        };

        $app = new Application($module);
        $this->assertSame('bar', $app->get('foo'));
    }

    public function testHas()
    {
        $module = new class implements Module {

            public function init(Di $di): void
            {
                $di->set('foo', function () {
                    return 'bar';
                });
            }
        };

        $app = new Application($module);
        $this->assertTrue($app->has('foo'));
        $this->assertFalse($app->has('bar'));
    }
}
