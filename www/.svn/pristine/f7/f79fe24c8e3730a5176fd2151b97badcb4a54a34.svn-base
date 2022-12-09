<?php


namespace App\Servers\CryptAES;


class CryptAES
{
    /**
     * @param $input
     * @param $key
     * @return string
     */
    public static function encrypt($input, $key)
    {
        $data = openssl_encrypt($input, 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
        $data = base64_encode($data);
        return $data;
    }

    /**
     * @param $sStr
     * @param $sKey
     * @return false|string
     */
    public static function decrypt($sStr, $sKey)
    {
        $decrypted = openssl_decrypt(base64_decode($sStr), 'AES-128-ECB', $sKey, OPENSSL_RAW_DATA);
        return $decrypted;
    }
}