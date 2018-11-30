<?php
/**
 * Created by PhpStorm.
 * User: jzy1036
 * Date: 2018/11/30
 * Time: 下午12:11
 */

/**
 * 获取毫秒的时间戳
 * @return array|string
 */
function get_millisecond()
{
    //获取毫秒的时间戳
    $time = explode(" ", microtime());
    $time = $time[1] . substr($time[0], 2, 3);
    return $time;
}