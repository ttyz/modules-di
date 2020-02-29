<?php

namespace Module;

use PHPUnit\Framework\TestCase;

class LegacyModuleWrapperTest extends TestCase
{

    public function testWrap()
    {
        $manager = new ModuleManager();
        $module = new LegacyModule('test', [
            'init' => function () {
                return [ 'foo' => 'bar' ];
            }
        ]);

        $app = new Application(
            new LegacyAdapterModule($manager),
            new LegacyModuleWrapper($module)
        );

        $this->assertSame($module, $app->get('test'));
    }

    public function testValue()
    {
        $manager = new ModuleManager();
        $module = new LegacyModule('test', [
            'init' => function () {
                return [ 'foo' => 'bar' ];
            }
        ]);

        $app = new Application(
            new LegacyAdapterModule($manager),
            new LegacyModuleWrapper($module)
        );

        $this->assertSame('bar', $app->get('test')->foo);
    }

    public function testCanGetManagerFromDI()
    {
        $manager = new ModuleManager();
        $module = new LegacyModule('test', [
            'init' => function() {
                return [ 'foo' => 'bar' ];
            }
        ]);

        $app = new Application(
            new LegacyAdapterModule($manager),
            new LegacyModuleWrapper($module)
        );

        $this->assertSame($manager->get('test'), $app->get('test'));
    }
}
