<?php

namespace Module;

class LegacyModuleWrapper implements Module
{
    /** @var LegacyModule */
    protected $module;

    public function __construct(LegacyModule $module)
    {
        $this->module = $module;
    }

    public function init(Di $di): void
    {
        /** @var ModuleManager $manager */
        $manager = $di->get('moduleManager');
        $manager->register($this->module);
        $name = $this->module->getName();
        $di->set($name, function () use ($manager, $name) {
            return $manager->get($name);
        });
    }
}
