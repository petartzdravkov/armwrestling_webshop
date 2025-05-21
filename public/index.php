<?php

// define the application root path (/var/www/phpstudy/GIT/armwrestling_webshop)
define('ROOT_PATH', dirname(__DIR__));

// autoload classes
spl_autoload_register(function ($class) {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php' ;
    require_once ROOT_PATH . DIRECTORY_SEPARATOR .'src' . DIRECTORY_SEPARATOR . $class;
});

// load configuration
require_once ROOT_PATH . '/src/Config/config.php';

// UTF-8 encoding
header('Content-Type: text/html; charset=UTF-8');

// start session
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$fileNotFound = false;
//request?target=user&action=register
//would lead to invoking register method in /Controller/UserController
$controllerName = isset($_GET['target']) ? $_GET['target'] : 'home';
$methodName = isset($_GET['action']) ? $_GET['action'] : 'index';

$controllerClassName = '\\Controller\\' . ucfirst($controllerName) . 'Controller';

if (file_exists(ROOT_PATH . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . "Controller". DIRECTORY_SEPARATOR . ucfirst($controllerName) . "Controller.php")){
    $controller = new $controllerClassName;
    if (method_exists($controller, $methodName)) {
       //if request is not for login or register, check for login
       // if(!($controllerName == "user" && $methodName == "login")){
       //     if(!isset($_SESSION["email"])){
       //         header("HTTP/1.1 401 Unauthorized");
       //         die();
       //     }
       // }
        try{
            $controller->$methodName();
        }
        catch(\PDOException $e){
            header("HTTP/1.1 500"); echo $e->getMessage();
            die();
        }
    } else {
        $fileNotFound = true;
    }
    } else {
    $fileNotFound = true;
}


if ($fileNotFound) {
    //return header 404
    http_response_code(404);
    require_once ROOT_PATH . '/src/View/errors/404.php';
    die();
}