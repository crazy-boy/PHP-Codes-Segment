<?php

namespace system;

// 熔断机制
class CircuitBreaker
{
    private const CLOSED = 'CLOSED';
    private const OPEN = 'OPEN';
    private const HALF_OPEN = 'HALF_OPEN';

    private $state = self::CLOSED;
    private $failureThreshold = 0.5;      // 失败率阈值
    private $windowSize = 10;           // 滑动窗口大小
    private $timeout = 3;               // 半开状态超时时间
    private $window = [];
    private $lastCheckedAt;


    public function call($callback)
    {
        if ($this->state === self::OPEN && time() - $this->lastCheckedAt < $this->timeout) {
            return false;       //直接返回失败
        }

        try {
            $result = $callback();
            $this->recordSuccess();
        } catch (\Exception $e) {
            $this->recordFailure();
            if ($this->isTripped()){
                $this->state = self::OPEN;
                $this->lastCheckedAt = time();
            }
            return false;
        }

        if ($this->state === self::HALF_OPEN) {
            $this->state = ($this->isTripped()) ? self::OPEN : self::CLOSED;
        }

        return $result;
    }

    private function recordSuccess()
    {
        $this->window[] = 'success';
        if (count($this->window) > $this->windowSize) {
            array_shift($this->window);
        }
    }

    private function recordFailure()
    {
        $this->window[] = 'failure';
        if (count($this->window) > $this->windowSize) {
            array_shift($this->window);
        }
    }

    private function isTripped()
    {
        $failures = array_count_values($this->window)['failure'] ?? 0;
        return $failures / $this->windowSize > $this->failureThreshold;
    }

    public function getSystemLoad()
    {
        return sys_getloadavg()[0];
    }

    // 自适应阈值示例（基于负载）
    public function adjustThreshold($load)
    {
        // 根据负载调整阈值，例如：
        if ($load > 80) {
            $this->failureThreshold = 0.3; // 负载高时，降低阈值
        } else {
            $this->failureThreshold = 0.5;
        }
    }
}


// 调用
/*$circuitBreaker = new CircuitBreaker();

while (true) {
    // 定期获取系统负载并调整阈值
    $load = $circuitBreaker->getSystemLoad();
    $circuitBreaker->adjustThreshold($load);

    // 调用服务
    $result = $circuitBreaker->call(function () {
        // ...
    });

    // ... 其他逻辑
}*/