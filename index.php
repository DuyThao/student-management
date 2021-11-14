<?php
session_start();

ini_set('display_errors',1);
require_once('dotEnv.php');
use DotEnvService\DotEnv;
(new DotEnv(__DIR__ . '/.env'))->load();


$GLOBALS['config'] = array (
    'mysql' => array (
        'username' => getenv('USERNAME'),
        'password' => getenv('PASSWORD'),
        'database' => getenv('DATABASE'),
        'host' => getenv('HOST')
    )
);

require_once('vendor/autoload.php');
require_once('Loader.php');

//Load Smarty
require_once('app/smarty/Smarty.class.php');

define('PATH_ROOT', __DIR__);

spl_autoload_register(function (string $class_name) {
    include_once PATH_ROOT . '/' . $class_name . '.php';
});

require 'core/http/Route.php';
use Core\Http\Route;

$router = new Route();

include_once PATH_ROOT . '/app/routes.php';


$request_url = !empty($_GET['url']) ? '/' . $_GET['url'] : '/';
$method_url = !empty($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';

$router->map($request_url, $method_url);


