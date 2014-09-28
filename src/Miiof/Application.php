<?php

namespace Miiof;

/**
 * @package Miiof
 */

class Application extends \Flint\Application
{
	public function __construct($rootDir, $debug=true, array $parameters = array())
	{
		$parameters += ['config.cache_dir' => $rootDir . '/app/cache/config'];

		parent::__construct($rootDir, $debug, $parameters);
	}
}
