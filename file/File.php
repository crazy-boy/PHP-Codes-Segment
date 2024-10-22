<?php

//文件处理类
class File
{
    /**
     * 删除文件
     * @param string  $path     文件路径
     * @return bool  成功则返回true，否则返回false
     */
    public static function removeFile($path){
        return @unlink($path);
    }


    /**
     * 获取文件扩展名
     * @param string $fileName      给定的文件名
     * @return string       扩展名
     */
    public static function getFileExt($fileName){
        return strtolower( strrchr( $fileName , '.' ) );
    }

    /**
     * 循环删除目录和文件
     * @param string  $dirName
     * @return bool
     */
    public static function delDirAndFile($dirName){
        if ($handle = opendir($dirName) ) {
            while (false !== ($item=readdir($handle))) {
                if ($item != "." && $item != "..") {
                    if (is_dir($dirName.'/'.$item)) {
                        self::delDirAndFile($dirName.'/'.$item);
                    } else {
                        unlink($dirName . '/' . $item);
                    }
                }
            }
        }
        closedir($handle);
        return rmdir($dirName);
    }

}
