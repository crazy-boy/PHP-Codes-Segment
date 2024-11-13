<?php

// 滑动窗口算法

/**
 * 1. 查找数组中滑动窗口内的最大值
 *
 * 整体思路是：
 *     1. 使用一个单调递减队列来存储索引，队列头部始终是当前窗口的最大值对应的索引。
 *     2. 每次循环，将当前元素与队列尾部的元素比较，如果当前元素更大，则将队列尾部的元素弹出，直到队列为空或当前元素小于等于队列尾部的元素。
 *     3. 当窗口滑动时，如果队列头部的索引对应的元素已经不在窗口内，则将其弹出。
 * @param $nums
 * @param $k
 * @return array
 */
function findMaxSlidingWindow($nums, $k): array
{
    $n = count($nums);
    if ($n == 0) {
        return [];
    }

    $q = []; // 单调递减队列，存储索引  队列头部始终是当前窗口的最大值对应的索引。
    $res = [];

    for ($i = 0; $i < $n; $i++) {
        // 保证队列中只保留索引对应的值大于等于nums[i]的元素
        while ($q && $nums[$q[count($q) - 1]] < $nums[$i]) {
            array_pop($q);
        }
        $q[] = $i;

        // 如果窗口已满，且窗口首元素已过期，则弹出
        if ($i >= $k - 1) {
            $res[] = $nums[$q[0]];      // 窗口最大值就是队列头部的元素
            if ($q[0] === $i - $k + 1) {    // 判断窗口左边界元素是否过期
                array_shift($q);
            }
        }
    }

    return $res;
}

// 调用
$nums = [2,5,3,4,5,2,6,3,2,4,10,1,2,0];
$res = $this->findMaxSlidingWindow($nums,3);
dump(json_encode($res));        // [5,5,5,5,6,6,6,4,10,10,10,2]


/**
 * 2. 查找最长无重复子串
 * @param $s
 * @return int|mixed
 */
function lengthOfLongestSubstring($s) {
    $n = strlen($s);
    $ans = 0;
    // 左右指针: 用两个指针left和right来表示滑动窗口的左右边界。
    $left = 0;
    $right = 0;
    $set = [];      // 哈希表: 用一个哈希表来记录窗口中出现的字符。
    while ($right < $n) {
        $c = $s[$right];
        // 窗口滑动: 当遇到重复字符时，左指针向右移动，直到窗口中不再包含重复字符。
        while (isset($set[$c])) {
            unset($set[$s[$left]]);
            $left++;
        }
        $set[$c] = true;
        $ans = max($ans, $right - $left + 1);
        $right++;
    }
    return $ans;
}

// 调用
$str = 'sdf233rdfekdfkkdfkdkgkdfgggegfgmffdfdfeer343ferrfe';
$res = $this->lengthOfLongestSubstring($str);
dump($res);        // 6