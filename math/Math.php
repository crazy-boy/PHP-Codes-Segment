<?php


class Math
{
    /**
     * Given a non-negative integer c, this function is to decide whether there're two integers a and b such that a2 + b2 = c.
     * 判断一个数是否为两个数的平方和
     * @param Integer $c
     * @return Boolean
     */
    public function judgeSquareSum(int $c): bool
    {
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
     * @param string $interval  区间  支持格式：(,20)、[10,5)、[20.5,)、(-∞，50.1)、(-∞,+∞)、(,)
     * @return bool
     */
    public function isInInterval($num, $interval): bool
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

    /**
     * 去掉小数点后面无效的零  如：20.5060=》20.506，200.00=》200
     * @param mixed $num 给定的数
     * @return string 处理后的结果
     */
    public function removeTrailingZeros($num): string
    {
        return rtrim(rtrim($num, '0'),'.');
    }

    /**
     * 随机指定范围内的一个数(可以为小数)   如：getRandomNumber(2,10,2) =》3.46
     * @param mixed $min 范围起始数
     * @param mixed $max 范围结束数
     * @param int $decimals 小数点位数
     * @return string   随机结果
     */
    public function getRandomNumber($min, $max, int $decimals = 2): string
    {
        $number = $min + mt_rand() / mt_getrandmax() * ($max - $min);
        return number_format($number, $decimals);
    }


    /**
     * 校验是否为偶数
     * This function checks whether the provided integer is even.
     *
     * @param int $number An integer input
     * @return bool whether the number is even or not
     */
    function isEven(int $number): bool
    {
        return $number % 2 === 0;
    }


    /**
     * 校验是否为奇数
     * This function checks whether the provided integer is odd.
     *
     * @param int $number An integer input
     * @return bool whether the number is odd or not
     */
    function isOdd(int $number): bool
    {
        return $number % 2 !== 0;
    }

    /**
     * 获取数字数组的中值
     * This function calculates the median value of numbers provided
     *
     * @param  array $numbers  A variable sized number input
     * @return mixed $median Median of provided numbers
     */
    function median(array $numbers)
    {
        if (empty($numbers)) {
            return null;
        }

        sort($numbers);
        $length = count($numbers);
        $middle = ceil($length / 2);
        if ($length % 2 == 0) {
            return ($numbers[$middle] + $numbers[$middle - 1]) / 2;
        }

        return $numbers[$middle - 1];
    }


    /**
     * 判断一个给定的整数是否为阿姆斯特朗数（也称纳西索斯数）
     * This function checks if given number is Armstrong
     * 阿姆斯特朗数 是一个三位以上的正整数，它的每个位上的数字的立方和等于它本身。例如，153 就是一个阿姆斯特朗数，因为 1^3 + 5^3 + 3^3 = 153。
     * e.g. 153
     * @param integer $input 待判断的整数
     * @return boolean 是否为阿姆斯特朗数
     */
    function isNumberArmstrong(int $input): bool
    {
        $arr = array_map('intval', str_split($input));
        $sumOfCubes = 0;
        foreach ($arr as $num) {
            $sumOfCubes += $num * $num * $num;
        }
        if ($sumOfCubes == $input) {
            return true;
        } else {
            return false;
        }
    }
}