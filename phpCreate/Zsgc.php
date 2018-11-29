<?php

namespace phpCreate;

class Zsgc {

    /**
     * @param $filePath
     * @param $fileName
     * @return mixed
     */
   public static function createFile($filePath, $fileName){

        $createpath = './Public/Uploads/' .$filePath.'/'.$fileName.'.php';
        $_createpath = iconv('utf-8', 'utf-8', $createpath);
        if (file_exists($_createpath) == false)
        {
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            if (mkdir($_createpath, 0700, true)) {
                $value['file'] ='文件夹创建成功';
                $value['success']='success';
            } else {
                $value['file'] ='文件夹创建失败';
                $value['fail']='fail';
            }
        }
        else
        {
            $value['file'] ='文件夹已存在';
            $value['fail']='fail';
        }
        return $value;
    }


}
