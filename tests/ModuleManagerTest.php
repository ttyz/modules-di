<?php
namespace Module;
use Module\LegacyModule as Module;
use PHPUnit\Framework\TestCase;

class ModuleManagerTest extends TestCase
{

    public function testRegisterModule()
    {
        $module = $this->createMock(Module::class);
        $module->method('getName')->willReturn('testmodule');
        $module->expects($this->once())->method('init');

        $manager = new ModuleManager();
        $manager->register($module);

        $this->assertSame($module, $manager->get('testmodule'));
    }

    public function testNullModule()
    {
        $manager = new ModuleManager();
        $this->assertInstanceOf(NullModule::class, $manager->get('nothing'));
    }

    public function testRequires()
    {
        $manager = new ModuleManager();
        $module = $this->createMock(Module::class);
        $module->method('getName')->willReturn('testmodule');
        $module->method('getRequirements')->willReturn(['dependency']);
        $dependency = $this->createMock(Module::class);
        $dependency->method('getName')->willReturn('dependency');
        $dependency->expects($this->once())->method('init');
        $module->method('init')
            ->with($this->identicalTo($dependency));

        $manager->register($module, $dependency);

        $manager->get('testmodule');
    }

    public function testCyclicDependency()
    {
        $manager = new ModuleManager();
        $module1 = $this->createMock(Module::class);
        $module2 = $this->createMock(Module::class);
        $module3 = $this->createMock(Module::class);

        $module1->method('getName')->willReturn('module1');
        $module2->method('getName')->willReturn('module2');
        $module3->method('getName')->willReturn('module3');

        $module1->method('getRequirements')->willReturn(['module2']);
        $module2->method('getRequirements')->willReturn(['module3']);
        $module3->method('getRequirements')->willReturn(['module1']);

        $manager->register($module1, $module2, $module3);
        $module1->expects($this->once())->method('init')
            ->with($this->identicalTo($module2));

        $manager->get('module1');
    }

    public function testGetAll()
    {
        $manager = new ModuleManager();
        $module1 = $this->createMock(Module::class);
        $module2 = $this->createMock(Module::class);

        $module1->method('getName')->willReturn('module1');
        $module2->method('getName')->willReturn('module2');

        $manager->register($module1, $module2);

        $manager->get('module2');
        $all = $manager->getAll();
        $this->assertContainsOnlyInstancesOf(Module::class, $all);
        $this->assertContains($module1, $all, "module1 is not in reurned array");
        $this->assertContains($module2, $all, "module2 is not in reurned array");
    }

    public function testSetUp()
    {
        $manager = new ModuleManager();
        $module = $this->createMock(Module::class);
        $module->method('getName')->willReturn('module');
        $module->expects($this->once())->method('setUp')
            ->with($this->identicalTo($manager));

        $manager->register($module);
        $manager->get('module');
    }

    public function testDisable()
    {
        $manager = new ModuleManager();
        $module = $this->createMock(Module::class);
        $module->method('getName')->willReturn('module');

        $manager->register($module);
        $manager->disable('module');

        $this->assertInstanceOf(NullModule::class, $manager->get('module'));
    }

    public function testFilterGetAll()
    {
        $manager = new ModuleManager();
        $module1 = $this->createMock(Module::class);
        $module2 = $this->createMock(Module::class);

        $module1->method('getName')->willReturn('module1');
        $module2->method('getName')->willReturn('module2');

        $manager->register($module1, $module2);

        $filter = $manager->getAll(function (Module $module) {
            return $module->getName() === 'module1';
        });

        $this->assertContains($module1, $filter, "module1 is not in reurned array");
        $this->assertCount(1, $filter, "extra modules in returned array");
    }
}
