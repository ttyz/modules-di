<?php

namespace Module;

class Application
{
    /** @var Di */
    protected $di;

    public function __construct(Module ...$modules)
    {
        $this->di = new Di();
        foreach ($modules as $module) {
            $module->init($this->di);
        }
    }

    public function get($id)
    {
        return $this->di->get($id);
    }
}
