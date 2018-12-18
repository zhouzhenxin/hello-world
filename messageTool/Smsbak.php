<?php
/**
 * Created by PhpStorm.
 * User: songyuhang
 * Date: 2018/4/26
 * Time: 下午6:12
 */
namespace common\api;
use common\exception\BussinessException;
use common\helper\ErrorHelper;

class Smsbak
{
    //小鬼当佳发送验证码
    public static function sendSms($phones,$number,$type)
    {
        $cpName='xiaoguidangjiayx';//账号
        $cpPwd='xgdj2017';//密码
        // $phones=15811205357;
//        $number=rand(1000,9999);

        if($type==3){
            $msg='【小鬼当佳】验证码：['.$number.'],您正在使用小鬼当佳原片下载后台，3分钟有效，请及时处理。';
            $redisKeyNum = 'admin_sms_num_' .$type.'_'.$phones;
            $redisKey = 'admin_sms_' .$type.'_'.$phones;
        }else{
            $msg='【小鬼当佳】验证码：['.$number.'],您正在使用小鬼当佳小程序，3分钟有效，请及时处理。';
            $redisKeyNum = 'applet_sms_num_' .$type.'_'.$phones;
            $redisKey = 'applet_sms_' .$type.'_'.$phones;
        }
//        $msg='【小鬼当佳】验证码：['.$number.'],您正在使用小鬼当佳小程序，3分钟有效，请及时处理。';

        $msg=iconv("utf-8","gbk",$msg);//这里需要转换成gbk
        $msg=urlencode($msg);
        $url='http://api.itrigo.net/mt.jsp?cpName='.$cpName.'&cpPwd='.$cpPwd.'&phones='.$phones.'&msg='.$msg;
        $res=file_get_contents($url);
        \Yii::info($res,'SMS-INFO'.$phones);
//        $redisKey = 'applet_sms_' .$type.'_'.$phones;
//        $redisKeyNum = 'applet_sms_num_' .$type.'_'.$phones;

        if($res==0){
            \Yii::$app->redis->set($redisKey,$number,'EX', 180);

            $num=\Yii::$app->redis->get($redisKeyNum);

            \Yii::$app->redis->set($redisKeyNum,$num+1,'EX', 3600*24);

            return true;
        }else{
            return false;
        }
    }

    public static function createNum()
    {
        return mt_rand(1000,9999);
    }

    /**
     * 验证验证码是否正确
     * @param $mobile
     * @param $type
     * @param $code
     * @param $del
     * @return bool
     */
    public static function validateCode($mobile, $type,$code, $del = true)
    {
        $redisKey = 'applet_sms_' .$type.'_'.$mobile;
        $data = \Yii::$app->redis->get($redisKey);

        \Yii::info($data,'redis_code');
        \Yii::info($code,'user_code');

        if ($data && $code == $data) {
            if ($del) {
                \Yii::$app->redis->del($redisKey);
            }
            return true;
        }

        return false;
    }

    /**
     * 检测是否频发发送短信
     * @param $mobile
     * @param $type
     * @return bool
     */
    public static function canSend($mobile,$type)
    {
        $redisKey = 'applet_sms_' .$type.'_'.$mobile;
        $expire = \Yii::$app->redis->ttl($redisKey);
        if (!$expire || $expire < 60) {
            return true;
        }
        return false;
    }




}