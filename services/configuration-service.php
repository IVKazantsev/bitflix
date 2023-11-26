<?php

function option(string $name, $defaultValue = null)
{
	/**
	 * @throws Exception
	 * @var array $config
	 */

	static $config = null;

	if ($config === null)
	{
		$masterConfig = require ROOT . '/config.php';
		if(file_exists(ROOT . '/config.local.php'))
		{
			$localConfig = require ROOT . '/config.local.php';
		}
		else
		{
			$localConfig = [];
		}

		$config = array_merge($masterConfig, $localConfig);
	}

	if (array_key_exists($name, $config))
	{
		return $config[$name];
	}

	if ($defaultValue !== null)
	{
		return $defaultValue;
	}

	throw new \RuntimeException("Configuration option {$name} not found");
}