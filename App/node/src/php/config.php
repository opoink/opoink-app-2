<?php
/*
* Copyright 2021 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/

    function config(){
        $configPath = './../../../../etc/Config.php';
        if(file_exists($configPath)){
            return include($configPath);
        } else {
            throw new Exception('System config not found.');
        }
    }
?>