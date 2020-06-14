<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-6-12
 * Time: 18:51
 */
return array(
    array('POST', '/test', 'App\Controller\TestController::test_post'),
    array('GET', '/test/{id}', 'App\Controller\TestController::test_get'),
    array('GET', '/tests/{id}', 'App\Controller\TestsController::aa'),
);