<?php


//数组处理类
class Arr
{

    /**
     * 二维数组根据字段进行排序
     * @params array $array 需要排序的数组
     * @params string $field 排序的字段
     * @params string $sort 排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
     */
    public static function arraySequence($array, $field, $sort='SORT_DESC'){
        $arrSort = array();
        foreach ($array as $uniqid => $row) {
            foreach ($row as $key => $value) {
                $arrSort[$key][$uniqid] = $value;
            }
        }
        array_multisort($arrSort[$field], constant($sort), $array);
        return $array;
    }


    /**
     * 获取数组中某一个键的值组成新数组
     * @param array  $arr
     * @param string $key
     * @return array
     */
    public static function getArrColumn($arr,$key){
        $newArr = array();
        array_map(function($value) use (&$newArr, $key){
            $newArr[] = $value[$key];
        }, $arr);

        return $newArr;
    }

    public static function arrAdd($arr){
        ksort($arr);
        $no_key_arr = array_values($arr);
        array_walk($no_key_arr,function(&$item,&$key)use($no_key_arr){
            $item = array_sum(array_slice($no_key_arr,0,$key+1));
        });
        return $no_key_arr;
    }

    /**
     * 获取二维数组中指定字段的内容，转为一维数组
     * @param $arr      array       二维数组
     * @param $field    string      字段
     * @return array    结果数组
     */
    public static function getValByKey($arr,$field){
        if(!$arr)   return [];
        $newArr = [];
        foreach ($arr as $v){
            if(isset($v[$field]))   $newArr[] = $v[$field];
        }
        return $newArr;
    }

    /**
     * 根据下标，数组去重
     * @param array $arr1   主数组
     * @param array $arr2   从数组
     * @return array
     */
    public static function arrDiffByKey($arr1,$arr2){
        if(!$arr2)  return $arr1;
        foreach ($arr1 as $k=>$v){
            if(isset($arr2[$k]))    unset($arr1[$k]);
        }
        return $arr1;
    }

}
