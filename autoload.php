<?php
/**
 * Loads the classes as needed using the php autoload feature.
 * Every class is prefixed according to the folder it's in, which allows us to automate class loading,
 * which saves a lot of includes.
 *
 * @param string $className
 */
function dotsub_api_autoload($className){
	$tmp = explode('\\', $className);
	$classPath = explode('_', array_pop($tmp));

	if(count($classPath) > 3) {
		$classPath = array_slice($classPath, 0, 3);
	}

	$filePath = dirname(__FILE__) . '/src/' . implode('/', $classPath) . '.php';

	if(file_exists($filePath)) {
		require_once ($filePath);
	}
}

spl_autoload_register('dotsub_api_autoload');
