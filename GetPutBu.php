<?php
/**
 * Created by PhpStorm.
 * User: jzy1036
 * Date: 2018/12/3
 * Time: 上午10:06
 */
date_default_timezone_set('PRC');
echo date ("Y-m-d H:i:s");
var_dump(date("s"));
if (date("s") <= 4){
    echo 'true';
}