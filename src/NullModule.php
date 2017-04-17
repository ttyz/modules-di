<?php
declare(strict_types=1);
namespace Module;

class NullModule extends Module {

	public function __construct() {

	}

	public function getName() : string {
		return 'null';
	}

	public function getRequirements() : array {
		return [];
	}
}
