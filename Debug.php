<?php
//调试工具类
namespace libs;


class Debug
{
    /**
     * 打印内容
     */
    public static function dump(){
        $data = func_get_args();
        echo "<pre>";
        foreach ($data as $item){
            var_export($item);
            echo "<br/>";
        }
        echo "</pre>";
    }

}
