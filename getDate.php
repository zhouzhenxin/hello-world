<?php
/**
 * Created by PhpStorm.
 * User: jzy1036
 * Date: 2018/12/4
 * Time: 下午7:14
 */

date_default_timezone_set('PRC');

function getoppoDate($url, $header=null, $content=array(),$cookie=''){
    $ch = curl_init();
    if(substr($url,0,5)=='https'){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
    }
    if ($header!=null) {
        if(!isset($header[0])){//将索引数组转为键值数组
            foreach($header as $hk=>$hv){
                unset($header[$hk]);
                $header[]=$hk.':'.$hv;
            }
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);

    if(count($content)){
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$content);
    }
    if(!empty($cookie)){
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    }
    curl_setopt($ch, CURLOPT_COOKIEJAR, 'responseCookie.txt');    //存cookie的文件名，

    $response = curl_exec($ch);
    if($error=curl_error($ch)){
        die($error);
    }
    curl_close($ch);
    return $response;
}

function shujuCookie($contents){
    $preg_cookies = array('/\stk\s(.*)\s/','/\sk\s(.*)\s/');
    $cookies = array();
    foreach ($preg_cookies as $preg_cookie) {
        if (preg_match_all($preg_cookie, $contents, $cookie)) {
            $line=str_replace("\t","=",$cookie['0']);
            $line = substr($line['0'],1);
            $line = substr($line,0,strlen($line)-1);
            array_push($cookies,$line);
        }
    }
    $cookiesString = implode(';', $cookies);
    return $cookiesString;
}



$cookieFile = fopen("/Volumes/D/work/mygit/hello-world/judge.txt",'r');
$contents = fread($cookieFile,filesize("/Volumes/D/work/mygit/hello-world/judge.txt"));
fclose($cookieFile);

if(strpos($contents,'true') === false){
    //输出至页面
}

$cookieFileWrite = fopen("/Volumes/D/work/mygit/hello-world/cookie.txt",'r');
$cookiesString = fread($cookieFileWrite,filesize("/Volumes/D/work/mygit/hello-world/cookie.txt"));
fclose($cookieFileWrite);

$cookiesjq = explode(";",$cookiesString);
$jsessionId = '';
$tk = '';
foreach ($cookiesjq as $value){
    if(strpos($value,'JSESSIONID') !== false){
        $jsessionId = $value;
    }
    if(strpos($value,'tk=') !== false){
        $tkkv = explode("=",$value);
        $tk = $tkkv[1];
    }
}
$cookiesLoginString = $cookiesString.';'.$jsessionId;

$cookie=str_replace(";","; ",$cookiesLoginString);
$url = 'https://e.oppomobile.com/searchStat/list';

$header = array('tk'=>$tk);

$todayTime=date("Y-m-d");

$params = array("daterange"=>$todayTime.'~'.$todayTime);

$response = getoppoDate($url,$header,$params,$cookie);

$cookieFile = fopen("/Volumes/D/work/mygit/hello-world/responseCookie.txt",'r');
$contents = fread($cookieFile,filesize("/Volumes/D/work/mygit/hello-world/responseCookie.txt"));
fclose($cookieFile);

$dingShi = shujuCookie($contents);

$cookiesds = explode(";",$dingShi);
//var_dump($cookiesjq);

//var_dump($cookiesds);
foreach ($cookiesjq as &$value){
    if(strpos($value,'k=') !== false ){
        if(strpos($value,'tk=') !== false){
            if (strpos($cookiesds[0],'tk=') !== false){
                $value = $cookiesds[0];
            }else{
                $value = $cookiesds[1];
            }
        }else{
            if (strpos($cookiesds[0],'tk=') !== false){
                $value = $cookiesds[1];
            }else{
                $value = $cookiesds[0];
            }
        }
    }
}
//var_dump($cookiesjq);

$newcookies = implode(";",$cookiesjq);

$cookieFileWrite = fopen("/Volumes/D/work/mygit/hello-world/cookie.txt",'w');
fwrite($cookieFileWrite,$newcookies);
fclose($cookieFileWrite);
$todayTime=date("Y-m-d H:i");

//var_dump($response);
$date = fopen("/Volumes/D/work/mygit/hello-world/date.txt",'a');
$dateMinute = fopen("/Volumes/D/work/mygit/hello-world/dateMinute.txt",'a');

if (filesize("/Volumes/D/work/mygit/hello-world/date.txt") < 20){
    fwrite($date,"\t执行时间\t\t\t\t  时间\t\t\t  曝光量\t\t\t下载量\t\t\t消耗金额\t\t下载单价\t\t\tCTR\t\t\tECPM\n");
    fwrite($dateMinute,"\t执行时间\t\t\t\t  时间\t\t\t  曝光量\t\t\t下载量\t\t\t消耗金额\t\t下载单价\t\t\tCTR\t\t\tECPM\n");
}

if ($response == null || $response == ''){
    fwrite($date,"请重新登录\n");
    fwrite($dateMinute,"请重新登录\n");
}else{
    $result = json_decode($response,true);
    var_dump($result);
    $dayDate = $result['data'];
    if (empty($dayDate)){
        if (date("i") < 4) {
            fwrite($date, "数据缓存暂未统计\n");
        }
        fwrite($dateMinute,"数据缓存暂未统计\n");
    }else{
        $dayDateString = json_encode($dayDate[0]);
        fwrite($dateMinute,$todayTime."--->".$dayDateString."\n");
        if (date("i") < 4){
            fwrite($date,$todayTime."--->".$dayDateString."\n");
        }
//        foreach ($dayDate[0] as $key=>$value){
//            fwrite($date,$value);
//        }
    }
}

fclose($date);
var_dump($response);die;
