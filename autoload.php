<?php
/**
 * Loads the classes as needed using the php autload feature.
 * The source folder structure and naming scheme is set up so this feature works,
 * which saves a lot of includes.
 * 
 * @param string $className
 */
function dotsub_api_autoload($className){
	$classPath = explode('_', $className);
	if($classPath[0] != 'DotSUB') {
		return;
	}
	
	if(count($classPath) > 3) {
		$classPath = array_slice($classPath, 0, 3);
	}
	$filePath = dirname(__FILE__) . '/src/' . implode('/', $classPath) . '.php';
	
	if(file_exists($filePath)) {
		require_once ($filePath);
	}
}

spl_autoload_register('dotsub_api_autoload');
