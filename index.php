<?php
require('controllers/system.controller.php');

$uri = $_SERVER['REQUEST_URI'];
$path = explode('/', $uri);
$db = new system_model;
$system = new system($db->database);
array_shift($path);

if ($uri == '/') {
	require('controllers/themes.controller.php');
	$class = new themes($db->database);
	$class->main();
}
elseif (file_exists('controllers/'.$path[0].'.controller.php')) {
	include('controllers/'.$path[0].'.controller.php');
	$class_name = $path[0];
	$class = new $class_name;
	$method = $path[1];
	
	unset($path[0]);
	unset($path[1]);
	$path = array_values($path);
	
	if (isset($method) AND !is_numeric($method))
		call_user_func_array(array($class, $method),$path);
	else
		$class->main($method);
}
else
	echo '404';
