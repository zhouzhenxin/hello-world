<?php
/**
 * Created by PhpStorm.
 * User: jzy1036
 * Date: 2018/11/20
 * Time: 下午2:58
 */


/**
 * 创建一个ZIP文件
 * @param  [array]  $files       文件路径的一个数组
 * @param  [string] $destination 压缩生成的文件名(路径)
 * @param  [bool] $overwrite     是否覆盖
 * @return [bool]                成功返回TRUE, 失败返回FALSE
 */
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
            $zip->addFile($file, $file);//前面那个是文件路径，后面那个设置在zip中文件的路径（精确到文件本身）
        }
        $zip->addFromString('test.txt', '怎的了，我就加哥东西');//这个是创建并往里面加一个文本文件
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



/**
 * 解压一个ZIP文件
 * @param  [string] $toName   解压到哪个目录下（没有的话就会创建）
 * @param  [string] $fromName 被解压的文件名(路径)
 * @return [bool]             成功返回TRUE, 失败返回FALSE
 */
function unzip($fromName, $toName)
{
    echo filesize($fromName);
    if(!file_exists($fromName)){
        return FALSE;
    }
    $zipArc = new ZipArchive();
    if(!$zipArc->open($fromName)){
        return FALSE;
    }
    if(!$zipArc->extractTo($toName)){
        $zipArc->close();
        return FALSE;
    }
    return $zipArc->close();
}

function getpath(){
    header("location: http://ncc.nice.com/557.zip");//重定向访问这个地址
//    return "";
}


/**
 * @param $file
 * 下载文件到客户电脑
 */
function downloadFile($file){
    header("Content-type:application/octet-stream");
    header("Content-Disposition:attachment;filename=" . basename($file));
    header("Content-Length:" . filesize($file));
    ob_clean();
    readfile($file);
}

downloadFile('/Volumes/D/8888.jpg');

//重点！！！！！！！！！！！！！！！！重点！！！！！！！！！！！！！！！！！
//访问那个资源如果不能被网站解析的话就会变成下载
//六六六



//$arr = array('/Volumes/D/8888.jpg','/Volumes/D/cf.jpg');
//$resule = create_zip($arr,'/Volumes/D/557.zip');
////echo __FILE__;
//var_dump($resule);
//$fromName = '/Volumes/D/557.zip';
//$toName = '/Volumes/D/wee';
//$resule=unzip($fromName,$toName);
//getpath();
//var_dump($resule);