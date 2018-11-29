<?php
/**
 * Created by PhpStorm.
 * User: jzy1036
 * Date: 2018/11/21
 * Time: 下午3:51
 */

/**
 * 元转分
 * @param $money
 * @return int
 */
function yuanToFen($money) {

    return intval(bcmul(number_format($money, 2, '.', ''), 100, 2));

}

/**
 * 分转元
 * @param $money
 * @return string
 */
function fenToYuan($money) {

    return number_format(bcdiv(intval($money), 100, 2), 2, '.', '');

}
