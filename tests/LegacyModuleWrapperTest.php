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

        $app = new Application(new LegacyModuleWrapper($module, $manager));

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

        $app = new Application(new LegacyModuleWrapper($module, $manager));

        $this->assertSame('bar', $app->get('test')->foo);
    }
}
