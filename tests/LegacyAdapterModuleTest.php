<?php

namespace Module;

use PHPUnit\Framework\TestCase;

class LegacyAdapterModuleTest extends TestCase
{

    public function testInjectsModuleManager()
    {
        $manager = new ModuleManager();
        $app = new Application(
            new LegacyAdapterModule($manager)
        );

        $this->assertSame($manager, $app->get('moduleManager'));
    }
}
