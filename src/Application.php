<?php

namespace Module;

use Psr\Container\ContainerInterface;

class Application implements ContainerInterface
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

    public function has($id)
    {
        return $this->di->has($id);
    }
}
