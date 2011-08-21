<?php
/**
 * MyBike - framework
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @author		Andrej Sevastianov
 * @copyright	Copyright (c) 2011
 * @since		Version 1.0
 * @filesource
 */
error_reporting(E_ALL);
ob_start();
session_start();

// Initial base constants and configs
define('BASEPATH', $_SERVER['DOCUMENT_ROOT'].'/app/');
define('BASEURL', 'http://'. $_SERVER['SERVER_NAME'].'/');

// В конфиге находятся данные для маршрутизации
// которая использовалась до инициализации контролера.
$_cfg = include(BASEPATH."config.php");

// Get parent class
require_once BASEPATH.'functions.php';
require_once BASEPATH.'controller.php';

// Router
require_once BASEPATH.'router.php';
$router = new Router();

// Check and initial need controller
if ( ! file_exists(BASEPATH.'controllers/'.$router->controller.'.php') ) e404();
require_once BASEPATH.'controllers/'.$router->controller.'.php';

if ( ! class_exists($router->controller) ) e404();
$app = new $router->controller;

// Check and run need method
if ( ! method_exists($app, $router->method) ) e404();
call_user_func_array(array($app, $router->method), $router->args);

/* End of file index.php */
/* Location: ./index.php */