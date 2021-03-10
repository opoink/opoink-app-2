<?php
/*
* Copyright 2021 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/

defined('DS') ? null : define("DS", DIRECTORY_SEPARATOR);
defined('ROOT') ? null : define("ROOT",  dirname(dirname(dirname(dirname(__DIR__)))) );


$cssFiles = [];
$targetDir = ROOT.DS.'App'.DS.'Ext';

$config = ROOT.DS.'etc'.DS.'Config.php';

if(!file_exists($config)){
    throw new Exception('System config not found.');
}

$config = include($config);

function getCssFiles($dirPath){
    global $cssFiles;
    if(is_dir($dirPath)){
        $handle = opendir($dirPath);
        if ($handle) {
            $files = scandir($dirPath);
            foreach ($files as $key => $file) {
                if ($file != "." && $file != "..") {
                    $filePath = $dirPath.DS.$file;
                    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                    if(is_dir($filePath)){
                        getCssFiles($filePath);
                    } else {
                        if($ext == 'scss' || $ext == 'css'){
                            $cssFiles[] = str_replace(DS, '/', $filePath);
                        }
                    }
                }
            }
        }
    }
}

if(isset($config['modules'])){
    foreach ($config['modules'] as $vendor => $modules) {
        $vDir = $targetDir.DS.$vendor;
        foreach ($modules as $key => $mod) {
            $modDir = $vDir.DS.$mod;
            $cssDir = $modDir.DS.'View'.DS.'css';
    
            getCssFiles($cssDir);
        }
    }
}

echo json_encode($cssFiles);
?>