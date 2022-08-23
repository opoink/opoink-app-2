<?php
chdir(dirname(__DIR__));

defined('DS') ? null : define("DS", DIRECTORY_SEPARATOR);
defined('ROOT') ? null : define("ROOT", __DIR__);

// Composer autoloading
include __DIR__ . '/vendor/autoload.php';

// Global functions
include __DIR__ . '/vendor/opoink/framework/Functions/Functions.php';

\Of\Application::create()->run();