<?php

namespace Module;

class LegacyAdapterModule implements Module
{
    /** @var ModuleManager */
    protected $manager;

    public function __construct(ModuleManager $manager)
    {
       $this->manager = $manager; 
    }

    public function init(Di $di): void
    {
        $di->set('moduleManager', function() {
            return $this->manager;
        });
    }
}
