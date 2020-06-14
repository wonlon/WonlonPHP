<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-6-12
 * Time: 11:42
 */

/**
 * 获取和设置配置参数 支持批量定义
 * @param string|array $name 配置变量
 * @param mixed $value 配置值
 * @param mixed $default 默认值
 * @return mixed
 */
function C($name=null, $value=null,$default=null) {
    static $_config = array();
    // 无参数时获取所有
    if (empty($name)) {
        return $_config;
    }
    // 优先执行设置获取或赋值
    if (is_string($name)) {
        if (!strpos($name, '.')) {
            $name = strtoupper($name);
            if (is_null($value))
                return isset($_config[$name]) ? $_config[$name] : $default;
            $_config[$name] = $value;
            return null;
        }
        // 二维数组设置和获取支持
        $name = explode('.', $name);
        $name[0]   =  strtoupper($name[0]);
        if (is_null($value))
            return isset($_config[$name[0]][$name[1]]) ? $_config[$name[0]][$name[1]] : $default;
        $_config[$name[0]][$name[1]] = $value;
        return null;
    }
    // 批量设置
    if (is_array($name)){
        $_config = array_replace_recursive($_config, array_change_key_case($name,CASE_UPPER));
        return null;
    }
    return null; // 避免非法参数
}


/**
 * 加载配置文件 支持格式转换 仅支持一级配置
 * @param string $file 配置文件名
 * @param string $parse 配置解析方法 有些格式需要用户自己解析
 * @return array
 */
function load_config($file,$parse=CONF_PARSE){
    $ext  = pathinfo($file,PATHINFO_EXTENSION);
    switch($ext){
        case 'php':
            return include $file;
        case 'ini':
            return parse_ini_file($file);
        case 'yaml':
            return yaml_parse_file($file);
        case 'xml':
            return (array)simplexml_load_file($file);
        case 'json':
            return json_decode(file_get_contents($file), true);
    }
}

/**
 * 记录和统计时间（微秒）和内存使用情况
 * 使用方法:
 * <code>
 * G('begin'); // 记录开始标记位
 * // ... 区间运行代码
 * G('end'); // 记录结束标签位
 * echo G('begin','end',6); // 统计区间运行时间 精确到小数后6位
 * echo G('begin','end','m'); // 统计区间内存使用情况
 * 如果end标记位没有定义，则会自动以当前作为标记位
 * 其中统计内存使用需要 MEMORY_LIMIT_ON 常量为true才有效
 * </code>
 * @param string $start 开始标签
 * @param string $end 结束标签
 * @param integer|string $dec 小数位或者m
 * @return mixed
 */
function G($start,$end='',$dec=4) {
    static $_info       =   array();
    static $_mem        =   array();
    if(is_float($end)) { // 记录时间
        $_info[$start]  =   $end;
    }elseif(!empty($end)){ // 统计时间和内存使用
        if(!isset($_info[$end])) $_info[$end]       =  microtime(TRUE);
        if(MEMORY_LIMIT_ON && $dec=='m'){
            if(!isset($_mem[$end])) $_mem[$end]     =  memory_get_usage();
            return number_format(($_mem[$end]-$_mem[$start])/1024);
        }else{
            return number_format(($_info[$end]-$_info[$start]),$dec);
        }

    }else{ // 记录时间和内存使用
        $_info[$start]  =  microtime(TRUE);
        if(MEMORY_LIMIT_ON) $_mem[$start]           =  memory_get_usage();
    }
    return null;
}

/**
 * 记录日志
 * @param $message
 * @param $context
 * @param string $level
 */
function logger($message, $context, $level ='')
{
    $loger = new \Monolog\Logger('WonlonFramework');

    switch ($level)
    {
        case \Monolog\Logger::WARNING:
            $loger->pushHandler(new \Monolog\Handler\StreamHandler(COMMON_PATH.'/'.\Carbon\Carbon::now()->toDateString().'-wonlon-warning.log',\Monolog\Logger::WARNING));
            $loger->warning($message,$context);
            break;
        case \Monolog\Logger::ERROR:
            $loger->pushHandler(new \Monolog\Handler\StreamHandler(COMMON_PATH.'/'.\Carbon\Carbon::now()->toDateString().'-wonlon-err.log',\Monolog\Logger::ERROR));
            $loger->error($message,$context);
            break;
        default:
            $loger->pushHandler(new \Monolog\Handler\StreamHandler(COMMON_PATH.'/wonlon.log',\Monolog\Logger::DEBUG));
            $loger->debug($message,$context);
            break;
    }
}

/**
 * 系统抛异常
 * @param $code
 * @param null $msg
 * @param null $field
 * @throws Exception
 */
function wonlonThrowExp($code,$msg=null,$field=null){
    $result["code"] = $code;
    $result["msg"] = $msg;
    echo json_encode($result);
    die;
}

/**
 * session管理函数
 * @param string|array $name session名称 如果为数组则表示进行session设置
 * @param mixed $value session值
 * @return mixed
 */
function session($name='',$value='') {
    session_start();
    if(!empty($name) && !empty($value))
    {
        $_SESSION[$name] = $value;
    }
    if(!empty($name) && is_null($value))
    {
        $_SESSION[$name] = '';
    }
    else if(!empty($name) && empty($value))
    {
        return $_SESSION[$name];
    }
    return true;
}


