<?php
namespace Module;
use Module\LegacyModule as Module;
use PHPUnit\Framework\TestCase;

class ModuleTest extends TestCase
{

    public function testName()
    {
        $module = new Module('test');
        $this->assertSame('test', $module->getName());
    }

    public function testInit()
    {
        $module = new Module(
            'test',
            [
                'init' => function () {
                    return [
                        'value' => 'testval'
                    ];
                },
            ]
        );

        $module->init();
        $this->assertSame('testval', $module->value);
    }

    public function testRequire()
    {
        $module = new Module(
            'test',
            [
                'requires' => [ 'module1', 'module2' ],
                'init' => function (Module $dependency) {
                    return $dependency;
                },
            ]
        );

        $this->assertEquals(['module1', 'module2'], $module->getRequirements());
    }

    public function testRequireReflection()
    {
        $module = new Module(
            'test',
            [
                'init' => function (Module $dependency) {
                    return $dependency;
                },
            ]
        );

        $this->assertEquals([ 'dependency' ], $module->getRequirements());
    }

    public function testIgnoreVariadic()
    {
        $module = new Module(
            'test',
            [
                'init' => function (Module ...$variadic) {
                    return $variadic;
                },
            ]
        );

        
        $this->assertEquals([], $module->getRequirements());
    }

    public function testDynamicRequire()
    {
        $module = new Module(
            'test',
            [
                'requires' => [ 'module1' ],
                'setup' => function (ModuleManager $manager) {
                    $manager->getAll();
                    return [ 'module2' ];
                }
            ]
        );

        $manager = $this->createMock(ModuleManager::class);
        $manager->expects($this->once())->method('getAll');
        $module->setUp($manager);

        $this->assertEquals(['module2'], $module->getRequirements());
    }

    public function testRequireInit()
    {
        $module = new Module(
            'test',
            [
                'requires' => [ 'module1' ],
                'init' => function (Module $module1) {
                    return [
                        'value' => $module1->getName()
                    ];
                }
            ]
        );

        $module->init(new Module('module1'));
        $this->assertEquals('module1', $module->value);
    }

    public function testNoValue()
    {
        $module = new Module('test');
        $this->assertNull($module->novalue);
    }

    public function testActions()
    {
        $module = new Module(
            'test',
            [
                'init' => function () {
                    return [
                        'parameter' => 'value'
                    ];
                },
            ],
            [
                'action' => function ($arg) {
                    return $this->parameter . $arg;
                },
            ]
        );

        $module->init();
        $this->assertSame('value=0', $module->action('=0'));
    }

    public function testGetConfig()
    {
        $module = new Module(
            'test',
            [
                'commands' => ['cmd'],
            ]
        );

        $this->assertEquals([ 'cmd' ], $module->get('commands'));
        $this->assertNull($module->get('nothing'));
    }

    public function testCallPropAction()
    {
        $module = new Module(
            'test',
            [
                'init' => function () {
                    return [
                        'action' => factory(\stdClass::class),
                    ];
                },
            ],
            [
                'action' => 'action',
            ]
        );

        $module->init();
        $this->assertInstanceOf(\stdClass::class, $module->action());
    }
}
