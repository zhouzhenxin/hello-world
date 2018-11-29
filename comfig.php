<?php
/**
 * Created by PhpStorm.
 * User: jzy1036
 * Date: 2018/11/21
 * Time: 下午3:51
 */

//元转分
function yuanToFen($money) {

    return intval(bcmul(number_format($money, 2, '.', ''), 100, 2));

}
    //分转元
function fenToYuan($money) {

    return number_format(bcdiv(intval($money), 100, 2), 2, '.', '');

}

/**
 * 将15位身份证号转成18位
 * @param $idCard
 * @return string
 */
function parseIDCard($idCard) {
    // 若是15位，则转换成18位；否则直接返回ID
    if (15 == strlen ( $idCard )) {
        $W = array (7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2,1 );
        $A = array ("1","0","X","9","8","7","6","5","4","3","2" );
        $s = 0;
        $idCard18 = substr ( $idCard, 0, 6 ) . "19" . substr ( $idCard, 6 );
        $idCard18_len = strlen ( $idCard18 );
        for($i = 0; $i < $idCard18_len; $i ++) {
            $s = $s + substr ( $idCard18, $i, 1 ) * $W [$i];
        }
        $idCard18 .= $A [$s % 11];
        return $idCard18;
    } else {
        return $idCard;
    }
}


/**
 * 根据身份证号获得出生日期
 * @param $id_card
 * @return string
 */
function getBirthDayByIdCard($id_card) {
    $id_card = parseIDCard($id_card);
    $birth_day = substr($id_card,6,8);
    return $birth_day;
}

//var_dump(checkIdCard('52242619811105565x'));

/**
 * 验证身份证号
 * @param $id_card
 * @return bool
 */
function checkIdCard($id_card) { // 检查是否是身份证号
    if (strlen($id_card) != 15 && strlen($id_card) != 18) {
        return false;
    }
    $id_card = parseIDCard($id_card);
    // 转化为大写，如出现x
    $number = strtoupper($id_card);
    //加权因子
    $wi = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
    //校验码串
    $ai = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
    //按顺序循环处理前17位
    $sigma = 0;
    for ($i = 0;$i < 17;$i++) {
        //提取前17位的其中一位，并将变量类型转为实数
        $b = (int) $number{$i};
        //提取相应的加权因子
        $w = $wi[$i];
        //把从身份证号码中提取的一位数字和加权因子相乘，并累加
        $sigma += $b * $w;
    }
    //计算序号
    $snumber = $sigma % 11;
    //按照序号从校验码串中提取相应的字符。
    $check_number = $ai[$snumber];
    if ($number[17] == $check_number) {
        return true;
    } else {
        return false;
    }
}

/**
 * U-懒惰匹配
 * i-忽略英文字母大小写
 * x-忽略空白
 * s-让元字符' . '匹配包括换行符内所有字符  在最后面加上即可，不过忽略的是正则表达式中的空格
 *
 * 判断是否是手机号
 *
 * @param mobile
 * @return
 */

function isMobile($mobile='') {
    $regex = '/^((13 [0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))[0-9]{8}$/';
	$p = preg_match($regex, $mobile);
	return $p;
}

//var_dump(isMobile('13762559349')); //返回匹配到了几次，1

