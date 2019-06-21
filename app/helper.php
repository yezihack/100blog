<?php

use App\Models\Configs;
use Illuminate\Support\Facades\Session;

/**
 * 时光日志
 * User: sgfoot
 * Date: 2017/11/13
 * Time: 13:45
 */
if(!function_exists('sglogs')) {
    /**
     * @param $data mixed 任意数据类型
     * @param $flag string 标识
     * @param $isClear bool/string 清除数据,true/false/all
     */
    function sglogs($data, $flag, $isClear = false) {
        \App\Plus\SgLogs::write($data, $flag, $isClear);
    }
}
if(!function_exists('get_host')) {
    function get_host(){
        $scheme = empty($_SERVER['HTTPS']) ? 'http://' : 'https://';
        $url = $scheme.$_SERVER['HTTP_HOST'];
        return $url;
    }
}

/**
 * 显示错误
 * @param $msg
 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
 */
function errors($msg)
{
    return view('errors.msg', compact('msg'));
}

if (!function_exists('set_result')) {
    /**
     * 设置返回结果
     * @param int $status
     * @param string $msg
     * @param null $data
     * @return array
     */
    function set_result($status, $msg, $data = null) {
        $result = [
            'status' =>$status,
            'msg' =>$msg,
        ];
        if ($data != null) {
            $result['data'] = $data;
        }
        $result['_'] = time();
        return $result;
    }
}
/**
 * 获取用户自定过的配置信息
 * @param $key string 键名
 * @param string $default 默认值
 * @param bool $isReplace 默认值
 * @return string
 */
function config_value($key, $default = null, $isReplace = false)
{
    $value = Configs::getValue($key, $default);
    if($isReplace && strtolower($key) == 'site_name') {
        $value = preg_replace('/[\|\_\-\s+]+/', '', $value, -1);//去掉空格,-,|,_
    }
    return $value;
}

/**
 * 获取对应扩展名的小图标
 * @param $ext
 * @return string|null
 */
function resolve_attachicons($ext)
{
    $ext    = strtolower($ext);
    $config = config('attachicons');
    if (is_array($config) && isset($config[$ext])) {
        return $config[$ext];
    }
    if (is_array($config) && isset($config['default'])) {
        return $config['default'];
    }
    return null;
}

/**
 * 判断session键是否存在
 * @param $key
 * @return mixed
 */
function session_has($key)
{
    return Session::has($key);
}

/**
 * 写日志
 * @param $data
 * @param string $flag
 * @param bool $is
 */
function mylog($data, $flag = 'None', $is = false)
{
    \App\Plus\SgLogs::write($data, $flag, $is);
}

/**
 * 按utf8编辑截取字符串
 * @param $str
 * @param $maxLength
 * @param string $encoding
 * @return string
 */
function sub_string($str, $maxLength, $encoding = 'utf-8')
{
    $len = mb_strlen($str, $encoding);
    if ($len > $maxLength) {
        return mb_substr($str, 0, $maxLength - 1) . '...';
    }
    return $str;
}

/**
 * 判断url的最后一个元素
 * @param $key
 * @return bool
 */
function checkUrlBaseName($key)
{
    $url  = URL::current();
    $a    = parse_url($url, PHP_URL_PATH);
    $keys = explode('|', $key);
    foreach ($keys as $k) {
        if (stripos($a, $k) !== false) {
            return true;
        }
    }
    return false;
}

/**
 * 打印数据
 * @param $data
 */
function mydump($data)
{
    ob_start();
    $content = func_get_args();
    if (func_num_args() > 1) {
        foreach ($content as $row) {
            if (is_array($row)) {
                var_dump($row);
            } else {
                print_r($row);
            }
            echo '<br/>';
        }
    } else {
        var_dump($data);
    }
    $result = ob_get_contents();
    ob_end_clean();
    echo '<pre><xml>';
    echo $result;
    echo '</xml></pre>';
}

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/16
 * Time: 21:01
 */
if (!function_exists('session_users')) {
    /**
     * 获取或设置登录用户 Session
     * @param object $user
     * @return mixed
     */
    function session_users($user = null)
    {
        if ($user == null) {
            $user = session('user', null);
        } else {
            session(['user' => [
                'id'   => $user->id,
                'name' => $user->name,
            ]]);
        }
        return $user;
    }
}
if (!function_exists('replace_editor')) {
    /**
     * 替换edtormd标签
     * @param $content
     * @param null $search
     * @return mixed
     */
    function replace_editor($content, $search = null)
    {
        $content = str_replace('**目录导航**', '', $content);
        $content = str_replace('[TOC]', '', $content);
        $content = str_replace('#', '', $content);
        $content = str_replace('[', '', $content);
        $content = preg_replace('/\(.*\)/', '', $content);
        $content = preg_replace('/\s+/', '', $content);
        $content = str_replace(']', '', $content);
        $content = str_replace('```', '', $content);
        if (!is_null($search)) {
            $content = str_replace($search, '', $content);
        }
        $content = strip_tags($content);
        return $content;
    }
}
/**
 * 加密密码规则
 * @param $pass string 输入的密码
 * @param $salt string 盐值
 * @return string
 */
function makePass($pass, $salt)
{
    return md5(md5($pass . $salt) . $salt);
}

/**
 * 生成随机数
 * @param int $length 默认长度为32
 * @param int $mode 1小写，2大写，3数字　4混合
 * @return string
 */
function getNonce($length = 32, $mode = 3)
{

    switch ($mode) {
        case 1:
            $chars = '0123456789';
            break;
        case 2:
            $chars = 'abcdefghijklmnopqrstuvwxyz';
            break;
        default:
            $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            break;
    }
    $str = '';
    for ($i = 0; $i < $length; $i++) {
        $str .= $chars[mt_rand(0, strlen($chars) - 1)];
    }
    return $str;
}

/**
 * curl post
 * @param $url
 * @param null $data
 * @param null $cookie
 * @return mixed
 */
function curlPost($url, $data = null, $cookie = null)
{
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
    curl_setopt($curl, CURLOPT_COOKIE, $cookie);
    curl_setopt($curl, CURLOPT_REFERER, '');// 设置Referer
    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    list($content, $status) = array(curl_exec($curl), curl_getinfo($curl), curl_close($curl));
    return (intval($status["http_code"]) === 200) ? $content : false;
}