<?php

session_start();

$rootPath = basename(__DIR__) === 'public'
    ? dirname(__DIR__)
    : __DIR__;

require_once $rootPath . '/src/Core/Autoload.php';
require_once $rootPath . '/config/app.php';

use App\Core\Router;

$url = $_GET['url'] ?? 'accueil';

$router = new Router();
$router->renderPage($url);