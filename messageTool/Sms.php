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
use common\service\SmsService;

class Sms
{
    //小鬼当佳发送验证码
    public static function sendSms($phone)
    {
        $number=mt_rand(1000,9999);
        $params['phone']=$phone;
        $params['code']=$number;
        $params['template']='SMS_148866707';

        $res=SmsService::send($params);

        $redisKey = 'applet_sms_'.$phone;

        if($res==1){
            \Yii::$app->redis->set($redisKey,$number,'EX', 300);
            return true;
        }else{
            return false;
        }
    }

    public static function sendTts($phone)
    {
        $number=mt_rand(1000,9999);
        $params['phone']=$phone;
        $params['code']=$number;
        $params['template']='TTS_150573652';

        $res=SmsService::singleCallByTts($params);

        $redisKey = 'applet_sms_'.$phone;

        if($res==1){
            \Yii::$app->redis->set($redisKey,$number,'EX', 300);
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
    public static function validateCode($mobile, $code, $del = true)
    {
        $redisKey = 'applet_sms_'.$mobile;
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