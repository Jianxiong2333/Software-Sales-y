<?php
$config = array (
    //app_id应用ID,您的APPID。
    'app_id' => '123456789',

    //商户私钥
    'merchant_private_key' => '',

    //异步通知地址
    'notify_url' => 'http://xxx.xxx.xxx/notify_url.php',

    //同步跳转
    'return_url' => 'http://xxx.xxx.xxx/return_url.php',

    //编码格式
    'charset' => 'UTF-8',

    //签名方式
    'sign_type'=> 'RSA2',

    //支付宝网关
    'gatewayUrl' => 'https://openapi.alipaydev.com/gateway.do',//此为沙盒网关，生产环境需要自行替换支付宝网关

    //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
    'alipay_public_key' => '',

);
/*************************加密参数，一定按照注释填写。（同一套公私钥不得同时出现在客户端上）************************/
//机器码验签私钥，对应客户端加密公钥。
$private_key = '-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQDMy7tL9tUK6F/63Uv9SM+mvCBtfov75HM0krNE36SI7bFXTEfP
oG7AtsB9VMjU/GayE45muqwF4rVXhMz4zP2qVkVviKSRKN0zkeK/aWQMgZI5/JuS
ZH64IezpOqnwzh+RHVn6DqDTFP8S83pHnISYMINQs0uYXHqE63EVIIXDAQIDAQAB
AoGAEFlzam0aNPS4NN7V4jKd4UHDlPo1Ura6O8g6Z5UzHXtcXQvJ79lu/hOY6miK
X/aGfFDzXWApu46Ny57efj5fQcVPpCeM8BOTISK+n9wRc5TMrEy3wgdNx5cexsTF
V7TLFGQ9Wqi9E3sNTyPO9Jphg6WRyti1+fLod3ppfHlgZAECQQD5g3x5JSdwC0wu
BrdGYVLutMy01yqKjnAJxw9uw0IJR7mYqowlhifB2aARxBPODAup2D+5IRACD8Qv
GlPNLWlpAkEA0h6l/Ke/0CY5TfNj/ibBGLlcYWdOD/dhsH8zp0vYjFcAQtagf9IM
AOfixy/rU0KgiMAu/tK4dOqi2HmlyO4B2QJASeP2aKnoE/ZEiRzUCbOoq6g/Nw7B
OmcUJtEccODCHZ1wCRX4iuQ/wdiA3sICW3KVwaaYuGpiIzooDwrs5kYcgQJAB572
9D/9VAZe47XlNY2gSU5HzHybtzaIw4cJj5LPqt9o8gOo1JoAt3OxpPnW9jEfc3ZM
/g8Ug6ETAPkAi1YemQJBAPPWG7ue3nVlskLeKcqjZBj4KTUvvArWHQP3+I+El7V7
lucN+01L96hWDxlnK2m6bjxFZyGUhlqKOX14IwHekz8=
-----END RSA PRIVATE KEY-----';
//激活码生成公钥，对应客户端解密私钥。
$private_make_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDTJegCqxzj5gX7u6Sql6BXXdiX
PZFZLVwK3SCzinJAtnMfeHgK93yVPV6Uop11RF7okG7sPg+RsWT12ltW6862HDVU
DdGvmPFLDohbtQsjyRxZH+UEq2M7yWW0DQiI+3WNKHLa/Y8pI/uHwVvSqqrYQZ/v
K+QxQowBZbfRFBtR3wIDAQAB
-----END PUBLIC KEY-----';
//加密混淆值，请确保各为六位字符（数字将为自动转为ASCII码，将与本地端对应）
$blur_l = "asdsad";
$blur_r = "ACDSDA";
//出售价格
$saleprice = 160;