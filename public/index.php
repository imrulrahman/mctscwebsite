<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../mctscwebsite/storage/framework/maintenance.php')) {
    require $maintenance;
} elseif (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader & Bootstrap Laravel...
if (file_exists(__DIR__.'/../mctscwebsite/vendor/autoload.php')) {
    require __DIR__.'/../mctscwebsite/vendor/autoload.php';
    $app = require_once __DIR__.'/../mctscwebsite/bootstrap/app.php';
} else {
    require __DIR__.'/../vendor/autoload.php';
    $app = require_once __DIR__.'/../bootstrap/app.php';
}

$app->handleRequest(Request::capture());
