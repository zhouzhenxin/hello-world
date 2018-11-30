<?php
/**
 * Created by PhpStorm.
 * User: jzy1036
 * Date: 2018/11/30
 * Time: 下午2:03
 */

/**
 * 模拟请求 http get
 * @param $url
 * @param int $httpCode
 * @return mixed 最后一个收到的HTTP代码
 */
function curl_get($url, &$httpCode = 0)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    //不做证书校验,部署在linux环境下请改为true
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $files_contents = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $files_contents;

}

/**
 * 模拟请求 http post
 * @param $url
 * @param int $httpCode
 * @param array $post_data
 * @param array $headers
 * @return mixed 最后一个收到的HTTP代码
 */
function curl_post($url, &$httpCode = 0,$post_data = array(), $headers = array())
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);//设置请求头
    //不做证书校验,部署在linux环境下请改为true
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    //添加配置，告诉curl我要用POST方式请求，因为curl发送请求的方式默认是get
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$post_data);//设置POST需要传递的值

    $files_contents = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $files_contents;

}