<?php
/*
* Copyright 2021 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/

defined('DS') ? null : define("DS", DIRECTORY_SEPARATOR);
defined('ROOT') ? null : define("ROOT",  dirname(dirname(dirname(dirname(__DIR__)))) );

$configPath = ROOT.DS.'etc'.DS.'Config.php';

if(file_exists($configPath)){
	$cfg =  include($configPath);
	echo json_encode($cfg);
} else {
	throw new Exception('System config not found.');
}
?>