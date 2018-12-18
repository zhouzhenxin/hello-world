<?php
/**
 * Created by PhpStorm.
 * User: songyuhang
 * Date: 2018/4/9
 * Time: 下午5:30
 */


namespace common\service;
require __DIR__ . '/../../vendor/sms/vendor/autoload.php';

use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;


use Aliyun\Api\Sms\Request\V20170525\SingleCallByTtsRequest;

use common\models\SmsLog;

// 加载区域结点配置
Config::load();


class SmsService
{
    static $acsClient = null;

    /**
     * 发送短信
     * @return stdClass
     */
    public static function send($params) {

        $phone=$params['phone'];
        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        //可选-启用https协议
        //$request->setProtocol("https");

        // 必填，设置短信接收号码
        $request->setPhoneNumbers($params['phone']);

        // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $request->setSignName("小鬼当佳");

        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $request->setTemplateCode($params['template']);

        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
        $request->setTemplateParam(json_encode(array(  // 短信模板中字段的值
            "code"=>$params['code']
        ), JSON_UNESCAPED_UNICODE));

        // 可选，设置流水号
        $request->setOutId("XGOutId");

        // 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
//        $request->setSmsUpExtendCode("1234567");

        // 发起访问请求
        $res = static::getAcsClient()->getAcsResponse($request);


        $res=json_decode(json_encode($res),true);

        $smsModel=new SmsLog();
        $smsModel->phone=$phone;
        $smsModel->res=json_encode($res);
        $smsModel->type=1;
        $smsModel->create_at=time();
        $smsModel->save(false);

        if(isset($res['Code'])&&$res['Code']=='OK'){
            return 1;
        }else{
            return 0;
        }
    }


    /**
     * 取得AcsClient
     *
     * @return DefaultAcsClient
     */
    public static function getAcsClient() {
        //产品名称:云通信短信服务API产品,开发者无需替换
        $product = "Dysmsapi";

        //产品域名,开发者无需替换
        $domain = "dysmsapi.aliyuncs.com";

        // TODO 此处需要替换成开发者自己的AK (https://ak-console.aliyun.com/)
        $accessKeyId = $config = \Yii::$app->params['sms']['accessKeyId']; // AccessKeyId

        $accessKeySecret = $config = \Yii::$app->params['sms']['accessKeySecret']; // AccessKeySecret

        // 暂时不支持多Region
        $region = "cn-hangzhou";

        // 服务结点
        $endPointName = "cn-hangzhou";


        if(static::$acsClient == null) {

            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

            // 增加服务结点
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

            // 初始化AcsClient用于发起请求
            static::$acsClient = new DefaultAcsClient($profile);
        }
        return static::$acsClient;
    }



    /**
     * 文本转语音外呼
     *
     * 语音服务API产品的DEMO程序，直接执行此文件即可体验语音服务产品API功能
     * (只需要将AK替换成开通了云通信-语音服务产品功能的AK即可)
     * 备注:Demo工程编码采用UTF-8
     */
    public static function singleCallByTts($params) {
        //产品名称:云通信语音服务API产品,开发者无需替换
        $product = "Dyvmsapi";

        //产品域名,开发者无需替换
        $domain = "dyvmsapi.aliyuncs.com";

        // TODO 此处需要替换成开发者自己的AK (https://ak-console.aliyun.com/)
        $accessKeyId = $config = \Yii::$app->params['sms']['accessKeyId']; // AccessKeyId

        $accessKeySecret = $config = \Yii::$app->params['sms']['accessKeySecret']; // AccessKeySecret

        // 暂时不支持多Region
        $region = "cn-hangzhou";

        // 服务结点
        $endPointName = "cn-hangzhou";

        //初始化acsClient,暂不支持region化
        $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

        // 增加服务结点
        DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

        // 初始化AcsClient用于发起请求
        $acsClient = new DefaultAcsClient($profile);

        //组装请求对象-具体描述见控制台-文档部分内容
        $request = new SingleCallByTtsRequest();
        //必填-被叫显号
        $request->setCalledShowNumber("073182705860");
        //必填-被叫号码
        $request->setCalledNumber($params['phone']);
        //必填-Tts模板Code
        $request->setTtsCode($params['template']);
        //选填-Tts模板中的变量替换JSON,假如Tts模板中存在变量，则此处必填
//        $request->setTtsParam("{\"code\":\"8098\"}");
        $request->setTtsParam(json_encode(array("code"=>$params['code'])));
        //选填-音量
        $request->setVolume(100);
        //选填-播放次数
        $request->setPlayTimes(3);
        //选填-外呼流水号
        $request->setOutId("XGTTsOutId");

        //hint 此处可能会抛出异常，注意catch
        $res = $acsClient->getAcsResponse($request);
        $res=json_decode(json_encode($res),true);
        if(isset($res['Code'])&&$res['Code']=='OK'){
            return 1;
        }else{
            return 0;
        }
//        return $response;
    }


}