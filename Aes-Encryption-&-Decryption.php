<?php

/**
* 利用mcrypt做AES加密解密
* 支持密钥：64bit（字节长度8）
* 支持算法：DES
* 支持模式：ECB
* 填充方式：PKCS5
*/
class Aes{
    const CIPHER = MCRYPT_DES;
    const MODE = MCRYPT_MODE_ECB;

    /**
     * 加密
     * @param string $str	需加密的字符串
     * @param string $key	密钥(8位)
     * @return string   密文
     */
    public static function encryption($str,$key){
        $size = mcrypt_get_block_size ( MCRYPT_DES, 'ecb' );
        $str = self::pkcs5_pad($str, $size);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(self::CIPHER,self::MODE),MCRYPT_RAND);
        $result = mcrypt_encrypt(self::CIPHER, $key, $str, self::MODE, $iv);
        return base64_encode($result);
    }

    /**
     * 解密
     * @param string $str   密文
     * @param string $key   密钥(8位)
     * @return string   明文
     */
    public static function decryption($str,$key){
        $str = base64_decode($str);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(self::CIPHER,self::MODE),MCRYPT_RAND);
        $str = trim(mcrypt_decrypt(self::CIPHER, $key, $str, self::MODE, $iv));
        return  self::pkcs5_unpad($str);
    }

    /**
     * PKCS5填充
     * @param $text
     * @param $blocksize
     * @return string
     */
    private static function pkcs5_pad($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    /**
     *
     * @param $text
     * @return bool|string
     */
    private static function pkcs5_unpad($text) {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text)) {
            return false;
        }
        if (strspn($text, chr($pad), strlen($text)-$pad) != $pad) {
            return false;
        }
        return substr($text, 0, -1 * $pad);
    }
}


//调用
$key = 'WGiSP3UQ';
$str = '18578019432';
$enStr = Aes::encryption($str,$key);
$deStr = Aes::decryption($enStr,$key);
var_dump($str,$enStr,$deStr);