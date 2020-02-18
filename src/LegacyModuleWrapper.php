<?php

namespace Module;

class LegacyModuleWrapper implements Module
{
    /** @var ModuleManager */
    protected $manager;

    /** @var string */
    protected $name;

    public function __construct(LegacyModule $module, ModuleManager $manager)
    {
        $this->manager = $manager;
        $this->manager->register($module);
        $this->name = $module->getName();
    }

    public function init(Di $di): void
    {
        $di->set($this->name, function () {
            return $this->manager->get($this->name);
        });
    }
}
