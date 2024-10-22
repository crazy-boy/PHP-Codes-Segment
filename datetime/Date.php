<?php

//日期时间处理方法类
class Date{

    /**
     * 根据出生日期计算年龄
     * @param int $birthday	出生日期(时间戳)
     * @return int 年龄
     */
    public static function calcAge($birthday) {
        $age = 0;
        $time = time();
        if($birthday <= $time){
            list($y1,$m1,$d1) = explode("-",date("Y-m-d", $birthday));
            list($y2,$m2,$d2) = explode("-",date("Y-m-d"), $time);
            $age = $y2 - $y1;
            if((int)($m2.$d2) < (int)($m1.$d1)){
                $age -= 1;
            }
        }
        return $age;
    }

    /**
     * 按周截取时间段
     * @param $start_time
     * @param $end_time
     * @return array
     */
    public static function getWeek($start_time, $end_time){
        $rs = [];
        $i = 0;
        while($start_time <= $end_time){
            $rs[$i]['start'] = date('Y-m-d',$start_time);
            $w = 7 - date('N',$start_time);
            $tmp = strtotime("+{$w} days",$start_time);
            if($end_time <= $tmp)
                $rs[$i]['end'] = date('Y-m-d',$end_time);
            else
                $rs[$i]['end'] = date('Y-m-d',$tmp);
            $i++;
            $start_time = strtotime("+1 day",$tmp);
        }
        return $rs;
    }

    /**
     * 根据时间段拆分成时间戳
     * @param string $time          时间段
     * @param string $delimiter     分隔符
     * @return array    分隔后的时间戳
     */
    public static function getTimeByRangeDateTime($time,$delimiter=' ~ '){
        $time = trim($time);
        if(!$time)  return [0,0];
        $time = explode($delimiter,$time);

        $start_time = isset($time[0]) ? strtotime($time[0]) : 0;
        $end_time = isset($time[1]) ? strtotime($time[1]) : 0;

        return [$start_time,$end_time];
    }

    /**
     * 根据时间描述获取时间段
     * @param string $term          时间描述
     * @param string $type          返回时间格式
     * @return array
     */
    public static function getRangeDateTimeByTerm($term='this-week',$type='time'){
        switch ($term){
            case 'last-month':  //上月
                $start_time = mktime(0, 0 , 0,date("m")-1,1,date("Y"));
                $end_time = mktime(23,59,59,date("m") ,0,date("Y"));
                break;
            case 'this-month':  //本月
                $start_time = mktime(0, 0 , 0,date("m"),1,date("Y"));
                $end_time = time();
                break;
            case 'last-week':  //上周
                $start_time = mktime(0, 0 , 0,date("m"),date("d")-date("N")+1-7,date("Y"));
                $end_time = mktime(23,59,59,date("m"),date("d")-date("N")+7-7,date("Y"));
                break;
            case 'this-week':  //本周
                $start_time = mktime(0, 0 , 0,date("m"),date("d")-date("N")+1,date("Y"));
                $end_time = time();
                break;
            case 'this-year':  //最近365
                $start_time = strtotime(date('Y-m-d'))-365*86400;
                $end_time = strtotime(date('Y-m-d'));
                break;
            default :
                $start_time = 0;
                $end_time = 0;
                break;
        }

        $start = self::getDateByType($start_time,$type);
        $end = self::getDateByType($end_time,$type);

        return [$start,$end];
    }

    private static function getDateByType($time,$type='time'){
        switch ($type){
            case 'time':
                return $time;
            case 'dateTime':
                return date('Y-m-d H:i:s',$time);
            case 'date':
                return date('Y-m-d',$time);
            default :
                return '';
        }
    }
}


//for test
var_dump(Date::calcAge(626602970));                     //int(29)
var_dump(Date::getWeek(1538928000,1540396800));
var_dump(Date::getRangeDateTimeByTerm('this-month','dateTime'));
