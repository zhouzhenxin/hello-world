<?php
/**
 * Created by PhpStorm.
 * User: jzy1036
 * Date: 2018/12/4
 * Time: 下午4:27
 */

function signCurl($url, $header=null, $content=array(),$backHeader=0,$cookie=''){
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
//    curl_setopt($ch, CURLOPT_HEADER,$backHeader); // 显示返回的Header区域内容
//    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, 'responseCookie.txt');    //存cookie的文件名，

//    curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');  //发送

    $response = curl_exec($ch);
    if($error=curl_error($ch)){
        die($error);
    }
    curl_close($ch);
    return $response;
}

function loginCookie($contents){
    $preg_cookies = array('/\stk\s(.*)\s/','/\sk\s(.*)\s/','/\s_ot\s(.*)\s/','/\sWEBTOKEN\s(.*)\s/');
//    $preg_cookie = '/\sJSESSIONID\s(.*)\s/';
//    $preg_cookie = '/\stk\s(.*)\s/';
//    $preg_cookie = '/\sk\s(.*)\s/';
    $cookies = array();
    $cookiesString = '';
    foreach ($preg_cookies as $preg_cookie) {
        if (preg_match_all($preg_cookie, $contents, $cookie)) {
            $line=str_replace("\t","=",$cookie['0']);
            $line = substr($line['0'],1);
            $line = substr($line,0,strlen($line)-1);
//            $line=explode("=",$line);
            array_push($cookies,$line);
//            var_dump($cookies);die;
        }
    }
    $cookiesString = implode(';', $cookies);
    return $cookiesString;
}

$captcha = $_POST['captcha'];
$cookieFileWrite = fopen("/Volumes/D/work/mygit/hello-world/cookie.txt",'r');
$cookiesString = fread($cookieFileWrite,filesize("/Volumes/D/work/mygit/hello-world/cookie.txt"));
fclose($cookieFileWrite);
$cookie=str_replace(";","; ",$cookiesString);
$url = 'https://e.oppomobile.com/login';
$params = array("name"=>"聚看点1","passwd"=>"jvkandian123","password"=>null,"captcha"=>$captcha);
$response = signCurl($url,null,$params,1,$cookie);

$cookieFile = fopen("/Volumes/D/work/mygit/hello-world/responseCookie.txt",'r');
$contents = fread($cookieFile,filesize("/Volumes/D/work/mygit/hello-world/responseCookie.txt"));
fclose($cookieFile);

if(strpos($contents,'WEBTOKEN') === false){
    echo '验证码错误';
    echo "<a href=\"./poopurcl.php\">确定</a>";
    die;
}

$cookiesLoginString = loginCookie($contents);


$cookiesjq = explode(";",$cookiesString);
$jsessionId = '';
foreach ($cookiesjq as $value){
    if(strpos($value,'JSESSIONID') !== false){
        $jsessionId = $value;
    }
}
$cookiesLoginString = $cookiesLoginString.';'.$jsessionId;

var_dump($cookiesLoginString);

$cookieFileWrite = fopen("/Volumes/D/work/mygit/hello-world/cookie.txt",'w');
fwrite($cookieFileWrite,$cookiesLoginString);
fclose($cookieFileWrite);

$cookieFile = fopen("/Volumes/D/work/mygit/hello-world/judge.txt",'w');
$contents = fwrite($cookieFile,'true');
fclose($cookieFile);


