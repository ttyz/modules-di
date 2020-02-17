<?php

namespace Module;

use Psr\Container\ContainerInterface;

class Di implements ContainerInterface
{
    protected $factories = [];
    protected $instances = [];

    public function get($id)
    {
        if (!$this->has($id)) {
            throw new DiException("service '$id' was not found");
        }

        if (!isset($this->instances[$id])) {
            $this->instances[$id] = call_user_func($this->factories[$id], $this);
        }

        return $this->instances[$id];
    }

    public function has($id)
    {
        return isset($this->factories[$id]);
    }

    public function set($id, callable $factory)
    {
        $this->factories[$id] = $factory;
    }
}
