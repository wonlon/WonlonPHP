<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-6-11
 * Time: 21:06
 */

namespace Routers;


use FastRoute\Dispatcher;
use FastRoute\RouteCollector;

class router
{

    /**
     * 运行路由
     */
    public function run()
    {
        $routerApi = include_once ROUTER_PATH.'/api.php';
        $dispatcher = \FastRoute\simpleDispatcher(function(RouteCollector $r) use ($routerApi) {
            foreach ($routerApi as $key=>$api)
            {
                $r->addRoute($api[0],$api[1],$api[2]);
            }
        });
        // Fetch method and URI from somewhere
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        $this->handle($routeInfo);
    }

    /**
     * 处理请求
     * @param $routeInfo
     */
    public function handle($routeInfo)
    {
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                wonlonThrowExp(404,'Not Found');
                // ... 404 Not Found
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                // ... 405 Method Not Allowed
                wonlonThrowExp(405,'Method Not Allowed');
                break;

            /**
             * 找到正确的路由
             * 使用$container容器获取相应
             * 的handler实例进行处理
             */
            case Dispatcher::FOUND:

                global $container;

                //handler name of container alias
                $handlerInfo = $routeInfo[1];
                $vars = $routeInfo[2];

                $handlerArr = explode("::", $handlerInfo);
                $handler = $handlerArr[0];
                $action = $handlerArr[1];

                //$input = json_decode(file_get_contents('php://input'),true)
                //$_SERVER['REQUEST_METHOD']
                // $vars = $routeInfo[2]; // url $vars
                $data = $this->get_data($_SERVER['REQUEST_METHOD'], $vars);
                // ... call $handler with $vars
                $handler = $container->get($handler);
                $handler->$action($data);
                break;
        }
    }

    /**
     * 组织前端传送的数据
     * @param $method
     * @param $vars
     * @return array|mixed
     */
    public function get_data( $method, $vars)
    {
        switch(strtolower($method))
        {
            case 'get':
                $input = $vars;
                $input = array_merge($_GET, $input);
                break;
            case 'post':
                $input = json_decode(file_get_contents('php://input'),true);
        }
        return $input;
    }
}