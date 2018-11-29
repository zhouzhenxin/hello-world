<?php
/**
 * Created by PhpStorm.
 * User: jzy1036
 * Date: 2018/11/21
 * Time: 上午10:12
 */
// 上传配置信息
$upconfig = array(
    'maxSize' => 3145728,         //3145728B（字节） = 3M
    'exts' => array('jpg', 'gif', 'png', 'jpeg'),
//  'rootPath'   =>    './Public/Uploads/info/',
    'rootPath' => 'https://www.eyuebus.com/Public/Uploads/info/',
);
/**
 * @param $string_image_content - 所要上传图片的字符串资源
 * @param $new_imgname - 图片的名称，如：57c14e197e2d1744.jpg
 * @return mixed
 */
function upload($string_image_content, $new_imgname)
{
    $res['result'] = 1;
    $res['imgurl'] = '';
    $res['comment'] = '';
    do {
        $ret = true;
        $fullPath = $this->upconfig['rootPath'] . $this->upconfig['savePath'];
        if (!file_exists($fullPath)) {
            $ret = mkdir($fullPath, 0777, true);
        }
        if (!$ret) {                // 上传错误提示错误信息
            $res['result'] = 12;
            $res['comment'] = "创建保存图片的路径失败！";
            return $res;
            break;
        }            //开始上传
        if (file_put_contents($fullPath . $new_imgname, $string_image_content)) {                // 上传成功 获取上传文件信息
            $res['result'] = 0;
            $res['comment'] = "上传成功！";
            $res['imgname'] = $new_imgname;
        } else {                // 上传错误提示错误信息
            $res['result'] = 11;
            $res['comment'] = "上传失败！";
        }
    } while (0);
    return $res;
}

/**
 * 图片上传
 * @param $imginfo - 图片的资源，数组类型。['图片类型','图片大小','图片进行base64加密后的字符串']
 * @param $companyid - 公司id
 * @return mixed
 */
function uploadImage($imginfo, $companyid, $upconfig)
{
    $image_type = strip_tags($imginfo[0]);  //图片类型
    $image_size = intval($imginfo[1]);  //图片大小
    $image_base64_content = strip_tags($imginfo[2]); //图片进行base64编码后的字符串

    if (($image_size > $upconfig['maxSize']) || ($image_size == 0)) {
        $array['status'] = 13;
        $array['comment'] = "图片大小不符合要求！";
        return $array;
    }
    if (!in_array($image_type, $upconfig['exts'])) {
        $array['status'] = 14;
        $array['comment'] = "图片格式不符合要求！";
        return $array;
    }        // 设置附件上传子目录
    $savePath = 'bus/group/' . $companyid . '/';
    $upconfig['savePath'] = $savePath;        //图片保存的名称
    $new_imgname = uniqid() . mt_rand(100, 999) . '.' . $image_type;        //base64解码后的图片字符串
    $string_image_content = base64_decode($image_base64_content);        // 保存上传的文件
    $array = upload($string_image_content, $new_imgname);
    return $array;
}

$file = $_FILES['file'];//得到传输的数据
//得到文件名称
echo $file['tmp_name'];
$name = $file['name'];

$type = strtolower(substr($name,strrpos($name,'.')+1)); //得到文件类型，并且都转化成小写

$allow_type = array('jpg','jpeg','gif','png'); //定义允许上传的类型
//判断文件类型是否被允许上传
if(!in_array($type, $allow_type)){    //如果不被允许，则直接停止程序运行
return false;
}//判断是否是通过HTTP POST上传的
if(!is_uploaded_file($file['tmp_name'])){    //如果不是通过HTTP POST上传的
return false;
}
//sleep(60);
$upload_path = "./img/";//上传文件的存放路径
//开始移动文件到相应的文件夹
//var_dump($file['tmp_name']);die;
if(move_uploaded_file($file['tmp_name'],$upload_path.$file['name'])){
    echo "Successfully!";
}else{
    echo "Failed!";
}