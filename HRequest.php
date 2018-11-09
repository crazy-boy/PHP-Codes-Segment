<?php


class HRequest
{
    /**
     * 获取远程服务器IP地址
     * @return string  IP地址
     */
    public static function getUserHostAddress(){
        switch(true){
            case ($ip = getenv("HTTP_X_FORWARDED_FOR")):
                break;
            case ($ip = getenv("HTTP_CLIENT_IP")):
                break;
            default:
                $ip = getenv("REMOTE_ADDR") ? getenv("REMOTE_ADDR") : '127.0.0.1';
        }
        if (strpos($ip, ', ')>0) {
            $ips = explode(', ', $ip);
            $ip = $ips[0];
        }
        return $ip;
    }
}