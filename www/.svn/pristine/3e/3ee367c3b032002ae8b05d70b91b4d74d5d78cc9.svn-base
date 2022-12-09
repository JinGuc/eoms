<?php


namespace App\Servers\Tools;


class Rsa
{
    /**
     * 私钥
     */
    const PRIVATE_KEY = '-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQCwBu90de8YlDxus4jjZVFnThT7GDyv46dRumy46RZQiPrnTIA6
vM8n/8TURCR4bUqiXlFPEIYySd3Ij6BYMiiv9crp0zQ4+Zq4v4QkQjACKHWx8mH1
AmT5m8ve6tOtktvxsANykkmIM8KLP2tX5CnsOWdb5QSwPwoS7kRE75r9ZwIDAQAB
AoGABYsyX2iXbx68CIB+/yphte8vmfjZcHCrBFfqtKgim8a0oDQ4laD8pTXY7RZ4
T5KhlxuBVAwhLdRFt9tOIXjy7wpaVyYHfGxGGoyR/wV/yPxpW5ftZCfJlNAsTPNj
MDurhsWcZpe0RPTzWeAIS4l59NG/xgXBPaYDrk8IDqRT5wECQQDgbcudsLprHKxQ
hpcNhXjtRZspUmiSssCn2utr+aoiAW1Df+LDdrjAvWEh+/WXIDe32DlN65ufUZlC
8wQ7OFKJAkEAyMoUPZ75IQgd9l1aqeZCQtXLNxQA4RfaeMOqG5wLhTl4YDajJMtJ
WDD4FTpaJKqpIbvQikpFViEeC8SkYLiUbwJAHkoTOgQZFM243+FaT6Pc70D+sPQD
UxE2+TCNp3P5pz9EDOEcrL7ALpsgmeKUgcICYxiWm1KaHUV5BtUflBKE6QJBAISB
HwRfUcbjpWo8xyUR6C5VCktprv7cj/dcIHkh7FRZzb+ortcMNHW3sy/HF0/VM9Io
MIHia4lgR4S6m0Oc648CQGaGrSYUsfxExed/ESAZLuvY8eNAZjKKnkZGFcSokjmU
ID2Zzb+YJhcz7vX9dqIo4CwC0kTd2roTfl4T8K2swAc=
-----END RSA PRIVATE KEY-----';

    /**
     * 公钥
     */
    const PUBLIC_KEY = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCwBu90de8YlDxus4jjZVFnThT7
GDyv46dRumy46RZQiPrnTIA6vM8n/8TURCR4bUqiXlFPEIYySd3Ij6BYMiiv9crp
0zQ4+Zq4v4QkQjACKHWx8mH1AmT5m8ve6tOtktvxsANykkmIM8KLP2tX5CnsOWdb
5QSwPwoS7kRE75r9ZwIDAQAB
-----END PUBLIC KEY-----';

    public function index() {
        $pwd = 'abcdefg';
        $password = $this->enRSA_private($pwd);
        echo 'mm =  '.$password;
        echo '<br>';
        $password = $this->deRSA_public($password);
        echo 'mm =  '.$password;

        $pwd = 'J_intelligent';
        $password = $this->enRSA_public($pwd);
        echo '<br>';
        echo 'mm =  '.$password;
        echo '<br>';
        $password = $this->deRSA_private($password);
        echo 'mm =  '.$password;

        $pwd = 'J_intelligent_p';
        $password = $this->enRSA_public($pwd);
        echo '<br>';
        echo 'mm =  '.$password;
        echo '<br>';
        $password = $this->deRSA_private($password);
        echo 'mm =  '.$password;
    }

    /*-----------------------------  公钥加密, 私钥解密 --------------------------------------*/
    /*
     * RSA公钥加密
     * 使用私钥解密
     */
    public static function enRSA_public($aString) {
        $pu_key = openssl_pkey_get_public(self::PUBLIC_KEY);//这个函数可用来判断公钥是否是可用的
        openssl_public_encrypt($aString, $encrypted, $pu_key);//公钥加密，私钥解密
        $encrypted = base64_encode($encrypted);//加密后的内容通常含有特殊字符，需要编码转换下，在网络间通过url传输时要注意base64编码是否是url安全的
        return $encrypted;
    }
    /*
     * RSA私钥解密
     * 有可能传过来的aString是经过base64加密的，则传来前需先base64_decode()解密
     * 返回未经base64加密的字符串
     */
    public static function deRSA_private($aString) {
        $pr_key = openssl_pkey_get_private(self::PRIVATE_KEY);//这个函数可用来判断私钥是否是可用的
        openssl_private_decrypt(base64_decode($aString), $decrypted, $pr_key);//公钥加密，私钥解密
        return $decrypted;
    }

    /*-----------------------------  私钥加密, 公钥解密 --------------------------------------*/
    /*
     * RSA私钥加密
     * 加密一个字符串，返回RSA加密后的内容
     * aString 需要加密的字符串
     * return encrypted rsa加密后的字符串
     */
    public static function enRSA_private($aString) {
        //echo "------------",$aString,"====";
        $pr_key = openssl_pkey_get_private(self::PRIVATE_KEY);//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
        openssl_private_encrypt($aString, $encrypted, $pr_key);//私钥加密
        $encrypted = base64_encode($encrypted);//加密后的内容通常含有特殊字符，需要编码转换下，在网络间通过url传输时要注意base64编码是否是url安全的
        //echo "加密后:",$encrypted,"\n";
        return $encrypted;
    }
    /*
     * RSA公钥解密
     */
    public static function deRSA_public($aString) {
        $pu_key = openssl_pkey_get_public(self::PUBLIC_KEY);//这个函数可用来判断公钥是否是可用的
        openssl_public_decrypt(base64_decode($aString), $decrypted, $pu_key);//公钥加密，私钥解密
        return $decrypted;
    }
}