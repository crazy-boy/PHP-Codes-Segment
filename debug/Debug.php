<?php

//调试工具类
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

//test
$arr = [['name'=>'liLei','age'=>20],['name'=>'liLi','age'=>18]];
$str = 'hello,just test';

Debug::dump($arr,$str);