<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-6-11
 * Time: 20:42
 */
require '../vendor/autoload.php';

//定义配置文件
define("COMMON_PATH",dirname(__DIR__).'/Common');
define("CONF_PATH",dirname(__DIR__).'/Config');
define("ROUTER_PATH",dirname(__DIR__).'/Routers');

require COMMON_PATH.'/Wonlon.php';