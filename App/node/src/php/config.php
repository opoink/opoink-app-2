<?php
    function config(){
        $configPath = './../../../../etc/Config.php';
        if(file_exists($configPath)){
            return include($configPath);
        } else {
            throw new Exception('System config not found.');
        }
    }
?>