<?php
/**
 * Created by PhpStorm.
 * User: jzy1036
 * Date: 2018/11/29
 * Time: 下午2:44
 */
class IdCard{
    /**
     * 将15位身份证号转成18位
     * @param $idCard
     * @return string
     */
    public function parseIDCard($idCard) {
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
    public  function getBirthDayByIdCard($id_card) {
        $id_card = parseIDCard($id_card);
        $birth_day = substr($id_card,6,8);
        return $birth_day;
    }
}