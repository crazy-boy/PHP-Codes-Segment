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
}