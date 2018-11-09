<?php


class CurlRequest{

    //通用POST提交
    public static function GeneralPost($url, $data=[],$header=[]){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Expect:']);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch,CURLOPT_TIMEOUT,30);
        if($data){
            curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($data));
        }
        $header ? curl_setopt($ch, CURLOPT_HTTPHEADER, $header) : curl_setopt($ch, CURLOPT_HEADER, 0);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result ?: false;
    }

    //POST提交 无结果
    public static function GeneralPostNoRs($url,$data=[]){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Expect:']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT,1);
        curl_setopt($ch, CURLOPT_POST,true);
        if($data){
            curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
        }
        curl_exec($ch);
        curl_close($ch);

        return true;
    }

    //GET提交 无结果
    public static function GeneralGetNoRs($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT,1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
    }

    //GET请求
    public static function GeneralGet($url, $data=[], $header=[]){
        if (!$url)  return false;
        if($data)  $url .= '?'.http_build_query($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $header ? curl_setopt($ch, CURLOPT_HTTPHEADER, $header) : curl_setopt($ch, CURLOPT_HEADER, 0);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result ?: false;
    }

    //curl请求
    public static function GeneralRequest($url, $type='get', $data=[], $header=[]){
        if (!$url)  return false;
        $ch = curl_init();

        switch ($type){
            case 'get':
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                if($data)  $url .= '?'.http_build_query($data);
                break;
            case 'post':
                curl_setopt($ch,CURLOPT_POST,true);
                if($data)   curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($data));
                break;
            case 'put':
                curl_setopt($ch, CURLOPT_PUT, true);
                break;
            case 'delete':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            default :
                break;
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        $header ? curl_setopt($ch, CURLOPT_HTTPHEADER, $header) : curl_setopt($ch, CURLOPT_HEADER, 0);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result ?: false;
    }
}