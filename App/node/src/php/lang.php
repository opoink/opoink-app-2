<?php
/*
* Copyright 2021 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/

defined('DS') ? null : define("DS", DIRECTORY_SEPARATOR);
defined('ROOT') ? null : define("ROOT",  dirname(dirname(dirname(dirname(__DIR__)))) );


$Files = [];
$targetDir = ROOT.DS.'App'.DS.'Ext';

$config = ROOT.DS.'etc'.DS.'Config.php';

if(!file_exists($config)){
    throw new Exception('System config not found.');
}

$config = include($config);

if(isset($config['modules'])){
    foreach ($config['modules'] as $vendor => $modules) {
        $vDir = $targetDir.DS.$vendor;
        foreach ($modules as $key => $mod) {
            $modDir = $vDir.DS.$mod;
            $targetDir = $modDir.DS.'languages';

			if(is_dir($targetDir)){
				$files = scandir($targetDir);
				foreach ($files as $key => $file) {
					if ($file != "." && $file != "..") {
						$filePath = $targetDir.DS.$file;
						$ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
						if($ext == 'json'){
							$fInfo = pathinfo($filePath);
							$fName = strtolower($fInfo['filename']);
	
							$fileContents = file_get_contents($filePath);
							$fileContents = json_decode($fileContents, true);
	
							if(array_key_exists($fName, $Files)){
								$Files[$fName] = array_merge($Files[$fName], $fileContents);
							} else {
								$Files[$fName] = $fileContents;
							}
						}
					}
				}
			}
        }
    }
}

echo json_encode($Files);
?>