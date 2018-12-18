<?php
/**
 * Created by PhpStorm.
 * User: jzy1036
 * Date: 2018/12/17
 * Time: 下午4:28
 */
$user_info = df;
list($t1, $t2) = explode(' ', microtime());
$microTime=(float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
$token=md5(md5($microTime));

\Yii::$app->redis->set('token_' . $token, json_encode($user_info));



$token=\Yii::$app->redis->get($user_info->user_id);
if ($token != null){
    \Yii::$app->redis->del($token);
}
//略.....
\Yii::$app->redis->set($user_info->user_id, $token);


\Yii::$app->redis->set($user_info->user_id, $token);
return $token;



