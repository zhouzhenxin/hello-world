<?php
/**
 * Created by PhpStorm.
 * User: jzy1036
 * Date: 2018/12/4
 * Time: 上午10:34
 */

/**
 * 发送数据
 * @param String $url     请求的地址
 * @param Array  $header  自定义的header数据  $header = array('x:y','language:zh','region:GZ');
 * @param Array  $content POST的数据  $content = array('name' => 'wumian');
 * @param Array  $backHeader 返回数据是否返回header  0不反回 1返回
 * @param Array  $cookie 携带的cookie
 * @return String
 */
function tocurl($url, $header=null, $content=array(),$backHeader=0,$cookie=''){
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
    $hd = fopen("/Volumes/D/work/mygit/hello-world/img/erweima.jpeg",'wb'); //只写打开或新建一个二进制文件；只允许写数据
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
    curl_setopt($ch,CURLOPT_FILE,$hd); //设置成资源流的形式
    curl_setopt($ch, CURLOPT_COOKIEJAR, 'responseCookie.txt');    //存cookie的文件名，

//    curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');  //发送

    $response = curl_exec($ch);
    if($error=curl_error($ch)){
        die($error);
    }
    curl_close($ch);
    fclose($hd); //关闭句柄
    return $response;
}

function zhenzhe($contents){
    $preg_cookies = array('/\stk\s(.*)\s/','/\sJSESSIONID\s(.*)\s/','/\sk\s(.*)\s/');
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

$url = 'https://e.oppomobile.com/loginCaptcha';
$content = array();
$r = tocurl($url,null,$content,1);
//var_dump($r);
$cookieFile = fopen("/Volumes/D/work/mygit/hello-world/responseCookie.txt",'r');
//var_dump(filesize("/Volumes/D/work/mygit/hello-world/cookie.txt"));
$contents = fread($cookieFile,filesize("/Volumes/D/work/mygit/hello-world/responseCookie.txt"));

$cookiesString = zhenzhe($contents);

var_dump($cookiesString);
fclose($cookieFile);

$cookieFileWrite = fopen("/Volumes/D/work/mygit/hello-world/cookie.txt",'w');
fwrite($cookieFileWrite,$cookiesString);
fclose($cookieFileWrite);

$fileName = './oppourcl.php';
echo "<form action=\"".$fileName."\" method=\"post\">";
echo "<input type=\"text\" name=\"captcha\" />";
echo "<input type=\"submit\" value=\"登录\" /></form>";

echo "<br><img widht=120 height=45 src=\"./img/erweima.jpeg\"><br>";
echo " <input type=\"button\" value=\"换一个\" onclick=\""."javascript: location.reload(true);"."\" />";
//$preg_json = '/{(.*?)}/m';
//if(preg_match($preg_json,$r,$json)){
//    $json = $json['0'];
//}
//echo $json;