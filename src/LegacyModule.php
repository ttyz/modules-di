<?php
declare(strict_types=1);
namespace Module;

class LegacyModule
{

    protected $props = [];
    protected $name;
    protected $init = null;
    protected $setup = null;
    protected $requirements = [];
    protected $actions;
    protected $config;

    public function __construct(string $name, array $config = [], array $actions = [])
    {
        $this->name = $name;
        $this->config = $config;

        if (isset($config['init'])) {
            $this->init = $config['init']->bindTo($this);
            $ref = new \ReflectionFunction($this->init);
            $params = array_filter(array_map(
                function (\ReflectionParameter $param) {
                    if (!$param->isVariadic()) {
                        return $param->getName();
                    }
                },
                $ref->getParameters()
            ));

            $this->requirements = $params;
        }

        if (isset($config['requires'])) {
            $this->requirements = $config['requires'];
        }

        if (isset($config['setup'])) {
            $this->setup = $config['setup']->bindTo($this);
        }

        $this->actions = $actions;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function init(self ...$dependencies)
    {
        if ($this->init) {
            $this->props = call_user_func_array($this->init, $dependencies);
        }
    }

    public function getRequirements() : array
    {
        return $this->requirements;
    }

    public function setUp(ModuleManager $manager)
    {
        if ($this->setup) {
            $this->requirements = call_user_func($this->setup, $manager);
        }
    }

    public function __get(string $prop)
    {
        return $this->props[$prop] ?? null;
    }

    public function __call(string $action, array $params)
    {
        $closure = null;
        if (isset($this->props[$action]) && is_callable($this->props[$action])) {
            $closure = $this->props[$action];
        } elseif (isset($this->actions[$action])) {
            $closure = $this->actions[$action];
        }

        if ($closure) {
            return $closure->call($this, ...$params);
        }
    }

    public function get(string $value)
    {
        return $this->config[$value] ?? null;
    }

    public function has(string $value)
    {
        return isset($this->config[$value]);
    }
}
