<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-6-11
 * Time: 21:24
 */

namespace App\Controller;
use Common\Library\TestBuilder;
use Monolog\Logger;
use Respect\Validation\Validator as v;

class TestController
{
    public function test_get( $data )
    {

        //1.0 模拟第三方调用
        //$testBuilder = new TestBuilder();

        //2.0 验证测试
        //$a = v::intVal()->between(10, 20)->validate("22"); // true

        //3.0 模拟使用TP的快捷方法
//        G('begin');
//        $a = C("DB_TYPE");
//        $b = C("DB_HOST");
//        $c = C("DB_NAME");
//        $d = C("DB_USER");
//
//        for ($i = 0 ;$i<10000;$i++)
//        {
//            $a = new  TestBuilder();
//            $i++;
//        }
//
//        G('end');
//
//        echo G('begin','end',6);
//        echo "==================================";
//        echo G('begin','end','m');
//        print_r([$a,$b,$c,$d]);
         //4.0 增加日志
//        logger("我是错误",[132],Logger::ERROR);
//        logger("我是警告",[444],Logger::WARNING);
//        logger("我是调试",[444]);

        //5.0 异常的抛出
//        echo wonlonThrowExp(110,'sql查询出错');
        echo time();
        //6.0 session 处理
//        echo  session('liming',null);
    }
    public function test_post( $data )
    {
        echo session('liming');
    }
}