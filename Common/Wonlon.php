<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-6-12
 * Time: 12:04
 */

// 记录开始运行时间
$GLOBALS['_beginTime'] = microtime(TRUE);
// 记录内存初始使用
define('MEMORY_LIMIT_ON',function_exists('memory_get_usage'));
if(MEMORY_LIMIT_ON) $GLOBALS['_startUseMems'] = memory_get_usage();

//应用程序的初始化
$mode = include COMMON_PATH.'/Mode/common.php';

//引入核心文件
foreach ($mode['core'] as $file){
    if(is_file($file)) {
        include $file;
    }
}

// 加载应用模式配置文件
foreach ($mode['config'] as $key=>$file){
    is_numeric($key)?C(load_config($file)):C($key,load_config($file));
}

//通过路由定位方法
$container = new \League\Container\Container();
$container->addServiceProvider( new \App\ServiceProvider\HireServiceProvider());
$router = $container->get('Routers\router');
$router->run();

