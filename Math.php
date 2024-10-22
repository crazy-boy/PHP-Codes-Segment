<?php


class Math
{
    /**
     * Given a non-negative integer c, this function is to decide whether there're two integers a and b such that a2 + b2 = c.
     * 判断一个数是否为两个数的平方和
     * @param Integer $c
     * @return Boolean
     */
    function judgeSquareSum($c) {
        $i = 0;
        $j = (int)sqrt($c);

        while($i <= $j){
            $mySum = $i*$i + $j*$j;
            if($mySum == $c){
                return true;
            }else if($mySum > $c){
                $j--;
            }else{
                $i++;
            }
        }

        return false;
    }

    /**
     * 判断指定数字是否在区间里
     * @param int|float $num    指定数字
     * @param string $interval  区间  支持格式：(,20)、[10,5)、【20.5,)、(-∞，50.1)、(-∞,+∞)、(,)
     * @return bool
     */
    public static function isInInterval($num, $interval): bool
    {
        $pattern = '/^([\[\(])(-?∞|\d*),(\+?∞|\d*)([\]\)])$/';
        $res = preg_match($pattern, $interval, $matches);
        if (!$res)  return false;

        $leftBracket = $matches[1];
        $leftNum = $matches[2];
        $rightNum = $matches[3];
        $rightBracket = $matches[4];

        if ($leftNum !== '∞' && $leftNum !== '') {
            if ($leftBracket == '[') {
                if ($num < $leftNum) {
                    return false;
                }
            } else {
                if ($num <= $leftNum) {
                    return false;
                }
            }
        }

        if ($rightNum !== '+∞' && $rightNum !== '') {
            if ($rightBracket == ']') {
                if ($num > $rightNum) {
                    return false;
                }
            } else {
                if ($num >= $rightNum) {
                    return false;
                }
            }
        }

        return true;
    }
}