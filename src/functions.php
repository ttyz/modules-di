<?php
declare(strict_types=1);
namespace Module;

/* use \DirectoryIterator; */

/**
 * Read the config file and return module configuration
 */
/* function getModuleConfig() : array { */
	/* static $config = []; */
	/* $directory = new DirectoryIterator(MODULES_DIR); */

	/* if (empty($config)) { */
	/* 	$config = include CONFIG_DIR . '/core.inc'; */
	/* 	foreach ($directory as $item) { */
	/* 		if (!$item->isDot() && */
	/* 			$item->isDir() && */
	/* 			file_exists($item->getPathName() . '/config.inc') */
	/* 		) { */
	/* 			$config[] = include $item->getPathName() . '/config.inc'; */
	/* 		} */
	/* 	} */
	/* } */

	/* return $config; */
/* } */

/**
 * Instantiate a new ModuleManager
 */
/* function getModuleManager() : ModuleManager { */
/* 	static $manager = null; */

	/* if (!isset($manager)) { */
	/* 	$manager = new ModuleManager; */
	/* 	$modules = getModuleConfig(); */
	/* 	$manager->register(...$modules); */
	/* } */

	/* return $manager; */
/* } */

/**
 * Create a simple factory
 */
function factory(string $class) : \Closure {
	return function(...$args) use ($class) {
		return new $class(...$args);
	};
}
