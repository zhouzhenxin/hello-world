<?php
/**
 * Created by PhpStorm.
 * User: jzy1036
 * Date: 2018/11/21
 * Time: 下午2:53
 */
include 'phpqrcode.php';

function create_zip($files = array(), $destination = '', $overwrite = false)
{

    //if the zip file already exists and overwrite is false, return false
    if (file_exists($destination) && !$overwrite) { //目录是否存在，是否覆盖
        return false;
    }

    //vars
    $valid_files = array();
    //if files were passed in...
    //遍历每一个文件，确认每一个文件存在。
    if (is_array($files)) {
        //cycle through each file
        foreach ($files as $file) {
            //make sure the file exists
            if (file_exists($file)) {
                $valid_files[] = $file;
            }
        }
    }

    //if we have good files...是否有文件
    if (count($valid_files)) {
        //create the archive
        $zip = new \ZipArchive();//创建压缩工具对象
        //根据$overwrite判断是新建还是覆盖
        if ($zip->open($destination, $overwrite ? \ZIPARCHIVE::OVERWRITE : \ZIPARCHIVE::CREATE) !== true) {
            return false;
        }
        //add the files----添加文件
        foreach ($valid_files as $file) {
            $filearr = explode('/',$file);
            $zip->addFile($file,$filearr[count($filearr)-1]);//前面那个是文件路径，后面那个设置在zip中文件的路径（精确到文件本身）
        }
//      $zip->addFromString('test.txt', '怎的了，我就加哥东西');//这个是创建并往里面加一个文本文件
        //debug
        //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
        //close the zip -- done!
        $zip->close();//echo 1;die;关闭zip文件
        //check to make sure the file exists
        return file_exists($destination);//该文件是否存在
    } else {
        return false;
    }
}
if (!function_exists('dump')) {
    function dump($arr){
        echo '<pre>'.print_r($arr,TRUE).'</pre>';
    }

}

function get($url){
    $ch = curl_init();
    preg_match('/https:\/\//',$url)?$ssl=TRUE:$ssl=FALSE;
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    if($ssl){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $data  =  curl_exec($ch);
    curl_close($ch);
    return $data;
}
function get_ip(){
    $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    if(!preg_match("/^\d+\.\d+\.\d+\.\d+$/", $ip)){
        $ip = '0';
    }
    $ip = '61.187.247.175';
    return $ip;
}

/*
 * 根据ip获取省市
 参数是百度地图key
 */
function getaddress($ak='') {
    $ak = (empty($ak))?"":$ak;
    $url ="https://api.map.baidu.com/location/ip?ak=$ak&coor=bd09ll&ip=".get_ip();
    $rs = json_decode(get($url),1);
    if($rs['status']==0){
        $arr = explode('|',$rs['address']);
        $arr = ['province'=>$arr[1],'city'=>$arr[2]];
    }else{
        $arr2 = json_decode(get('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json'),1);
        if($arr2['ret']==1){
            $arr = [];
            $arr['country'] = $arr2['country'];
            $arr['province'] = $arr2['province'];
            $arr['city'] = $arr2['city'];
        }
    }
    return $arr;
}

$dingweiarr = getaddress('EFKUVX4UR0CQRZuIOBvMdtyvzdDaq35p');

$address = $dingweiarr['province'].$dingweiarr['city'];

$folder = '/Volumes/D/work/PHPwork/img/';
$zipgopath = '/Volumes/D/gic/';
$zipType = '.zip';
$arrimgpath = array();
foreach (scandir($folder) AS $value) {
    if ($value == '.' OR $value == '..') continue;
    $arrimgpath[] = $folder.$value;
}

$errorCorrectionLevel = 'L';
$matrixPointSize = 11;//10是290px 1是29

if (is_array($arrimgpath)&&count($arrimgpath)>0){
    $zippath = $zipgopath.time().$address.$zipType;
    $result = create_zip($arrimgpath,$zippath);
    if ($result===true){
        foreach (scandir($folder) AS $value) {
            if ($value == '.' OR $value == '..') continue;
            unlink($folder.$value);
        }
    }
    $valueurl ='http://ncc.nice.cn'.substr($zippath,10);
    echo $valueurl;
    //生成二维码图片
    QRcode::png($valueurl, '/Volumes/D/work/PHPwork/zipurlimg/qrcode.png', $errorCorrectionLevel, $matrixPointSize, 2);
}


