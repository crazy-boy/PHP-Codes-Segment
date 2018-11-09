<?php

//字符串处理类
class Str
{
    /**
     * 截取指定字符串的内容
     * @param string $string		给定的字符串
     * @param int $sublen			截取长度
     * @param int $start			截取起始位置
     * @param string $code			字符串编码
     * @return string 返回截取后的字符串
     */
    public static function cut_str($string, $sublen, $start = 0, $code = 'UTF-8'){
        if($code == 'UTF-8') {
            $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
            preg_match_all($pa, $string, $t_string);
            if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen));
            return join('', array_slice($t_string[0], $start, $sublen));
        } else {
            $start = $start*2;
            $sublen = $sublen*2;
            $strlen = strlen($string);
            $tmpstr = '';
            for($i=0; $i< $strlen; $i++) {
                if($i>=$start && $i< ($start+$sublen)) {
                    if(ord(substr($string, $i, 1))>129) {
                        $tmpstr.= substr($string, $i, 2);
                    } else {
                        $tmpstr.= substr($string, $i, 1);
                    }
                }
                if(ord(substr($string, $i, 1))>129) $i++;
            }

            return $tmpstr;
        }
    }


    /**
     * 数据展示过滤
     * @param string $str		    给定的字符串
     * @param string $type			字符串过滤类型
     * @return string 过滤结果
     */
    public static function dumpStr($str = null,$type = "no_html"){
        $str = trim($str," ");
        switch($type) {
            case 'no_html':
                return htmlentities($str,ENT_IGNORE,"utf-8");
            case 'float':
                return floatval($str);
            case 'no_form':
                return number_format($str,5);
            case 'date':
                return $str ? date("Y-m-d H:i:s",$str) : "";
            default:
                return $str;
        }
        return $str;
    }


    /**
     * 获取字符串的长度  一个中文算2/3个字符
     * @param string $s	字符串
     * @return  int 字符串的长度
     */
    public static function absLength($s, $is_utf8=true){
        if(strlen($s) == 0)     return 0;

        $n = 0;
        preg_match_all("/./us",$s,$matchs);
        foreach($matchs[0] as $p){
            if($is_utf8){
                $n += preg_match('#^['.chr(0x1).'-'.chr(0xff).']$#',$p) ? 1 : 3;
            }else{
                $n += preg_match('#^['.chr(0x1).'-'.chr(0xff).']$#',$p) ? 1 : 2;
            }
        }
        return $n;
    }

    /**
     * 获取已指定字符连接的字符串的差集
     * @param string $str1	    字符串1
     * @param string $str2	    字符串2
     * @param string $separator 分隔符
     * @return  string
     */
    public static function getLeftChars($str1,$str2,$separator=','){
        $arr1 = explode($separator,$str1);
        $arr2 = explode($separator,$str2);
        $rs = array_unique(array_filter(array_diff($arr1,$arr2)));
        return join($separator,$rs);
    }

    /**
     * 获取已指定字符连接的字符串的并集
     * @param string $str1	    字符串1
     * @param string $str2	    字符串2
     * @param string $separator 分隔符
     * @return  string
     */
    public static function getMergeChars($str1,$str2,$separator=','){
        $arr1 = explode($separator,$str1);
        $arr2 = explode($separator,$str2);
        $rs = array_unique(array_filter(array_merge($arr1,$arr2)));
        return join($separator,$rs);
    }

    /**
     * 验证密码是否符合要求
     * @param string $password       明文密码
     * @return boolean   符合则返回true，否则返回false
     */
    public static function checkPassword($password){
        //$pass = "/^[a-zA-Z0-9_]{6,12}$/";//6-12位的数字、字母、下划线
        $pass = "/(?!^[0-9]+$)(?!^[a-zA-Z]+$)(?!^[^a-zA-Z0-9]+$)^.{6,20}$/";//6-20位，由字母、数字、或特殊字符2种或2种以上的组合
        return preg_match($pass, $password);
    }

    /**
     * 字符串高亮
     * @param string $find      查找的字符串
     * @param string $str       整个字符串
     * @return string   高亮后的字符串
     */
    public static function highLight($find=null, $str=null){
        return $find!="" ? strtr($str,array($find=>"<font color='red'>".$find."</font>")) : $str;
    }


    /**
     * xml转数组
     * @param object $simpleXmlElement  xml对象
     * @return array    结果数组
     */
    public static function xmlToArray($simpleXmlElement){
        //return json_decode(json_encode((array) simplexml_load_string($simpleXmlElement)), true);
        if(!$simpleXmlElement)  return '';
        $simpleXmlElement=(array)$simpleXmlElement;
        foreach($simpleXmlElement as $k=>$v){
            if($v instanceof \SimpleXMLElement ||is_array($v)){
                $simpleXmlElement[$k]=self::xmlToArray($v);
            }
        }
        return $simpleXmlElement;
    }

    /**
     * 字符串内添加间隔符
     * @param string $str           指定字符串
     * @param integer $interval     分隔长度
     * @param string $delimiter     分隔符
     * @return string       处理后的字符串
     */
    public static function strSpliceByDelimiter($str,$interval=1,$delimiter='-'){
        if(!$str)   return '';

        $str = str_split($str,$interval);
        return join($delimiter,$str);
    }

    /**
     * 删除字符串末尾的指定字符
     * @param string $str   字符串
     * @param string $char  指定字符
     * @return string   处理后的字符串
     */
    public static function delRightChar($str,$char='/'){
        $str = $str ?: '';
        if(substr($str,-1) == $char){
            $str = rtrim($str,$char);
        }

        return $str;
    }
}


//for test
var_dump(Str::cut_str('my test 你好 hello',6,3));         //string(8) "test 你"
var_dump(Str::absLength('my test 你好 hello'));           //int(20)
var_dump(Str::getLeftChars('3,5,8','8,6,7'));            //string(3) "3,5"
var_dump(Str::getMergeChars('3,5,8','8,6,7'));           //string(9) "3,5,8,6,7"
var_dump(Str::checkPassword('abcdefg'));                 //int(0)
var_dump(Str::strSpliceByDelimiter('abcdefg',2));        //string(10) "ab-cd-ef-g"

