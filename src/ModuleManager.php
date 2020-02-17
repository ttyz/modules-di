<?php
declare(strict_types=1);
namespace Module;
use Module\LegacyModule as Module;

class ModuleManager
{

    protected $config = [];
    protected $instances = [];

    public function __construct()
    {
        $this->register(new NullModule());
    }

    public function register(Module ...$modules)
    {
        foreach ($modules as $module) {
            $this->config[$module->getName()] = $module;
        }
    }

    public function get(string $name) : Module
    {
        if (!isset($this->instances[$name])) {
            // Module instantiation:
            // First get the module uninitialized object
            $module = $this->getConfig($name);

            // Remove it from the config array
            $this->disable($name);

            //Call the module setUp
            $module->setUp($this);

            // Initialize the module, based on the requested parameters
            $parameters = array_map([$this, 'get'], $module->getRequirements());
            $module->init(...$parameters);

            // Save the instance
            $this->instances[$name] = $module;
        }

        return $this->instances[$name];
    }

    public function getAll(callable $filter = null) : array
    {
        $filter = $filter ?? function () {
            return true;
        };

        return array_filter(
            array_merge(
                array_values($this->config),
                array_values($this->instances)
            ),
            $filter
        );
    }

    public function disable(string $name)
    {
        unset($this->config[$name]);
    }


    protected function getConfig(string $name): Module
    {
        return $this->config[$name] ?? $this->get('null');
    }
}
