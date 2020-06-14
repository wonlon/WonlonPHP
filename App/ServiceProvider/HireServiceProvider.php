<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-6-11
 * Time: 21:04
 */

namespace App\ServiceProvider;


use League\Container\ServiceProvider\AbstractServiceProvider;
use Psr\Log\LoggerInterface;

class HireServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'Routers\router',
    ];

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    public function register()
    {
        //新增加的控制器，需要在这里进行注册
        $this->getContainer()->add('Routers\router');
        $this->getContainer()->add('App\Controller\TestController');
    }
}