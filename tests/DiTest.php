<?php

namespace Module;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

class DiTest extends TestCase
{

    public function testInterface()
    {
        $di = new Di();
        $this->assertInstanceOf(ContainerInterface::class, $di);
    }

    public function testGet()
    {
        $di = new Di();

        $obj = $this->prophesize(\stdClass::class)->reveal();

        $di->set('test', function () use ($obj) {
            return $obj;
        });

        $this->assertSame($obj, $di->get('test'));
    }

    public function testGetDependency()
    {
        $di = new Di();
        $obj = $this->prophesize(\stdClass::class)->reveal();

        $di->set('foo', function (Di $di) {
            return $di->get('bar');
        });
        $di->set('bar', function () use ($obj) {
            return $obj;
        });

        $this->assertSame($obj, $di->get('foo'));
    }

    public function testGetShared()
    {
        $di = new Di();

        $di->set('test', function () {
            $obj = $this->prophesize(\stdClass::class)->reveal();
            return $obj;
        });

        $this->assertSame($di->get('test'), $di->get('test'));
    }

    public function testHas()
    {
        $di = new Di();
        $di->set('foo', function () {
            return 'foo';
        });

        $this->assertTrue($di->has('foo'));
        $this->assertFalse($di->has('bar'));
    }

    public function testGetException()
    {
        $di = new Di();
        $this->expectException(ContainerExceptionInterface::class);

        $di->get('test');
    }
}
