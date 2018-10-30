<?php
/**
 * Created by PhpStorm.
 * User: Jianxiong2333
 * Date: 2018-09-19
 * Time: 19:48
 */
require_once 'config.php';//导入配置文件
require_once 'class/DB/db_all.php';//链接数据库

$key = $_GET['Key']; //获得 Key 参数
$email = $_GET['Email'];//获得 Email 参数
/*开始证实*/
$trust_r = new pay_db_r_all();//实例化读正版表结果集
$trust_array = $trust_r ->  db_r('user_trust', 'y_key', $key);//用 Key 查询当前用户
if($email == $trust_array['y_email'])//比对用户输入邮件
{
    $key = str_replace("-", "", $key);
    $blur_l .= $key;//左侧加密拼接字符串
    $key = $blur_l;//整体拷贝
    $key .= $blur_r;//拼接右侧加密字符串
    $key = strtoupper(md5($key));//将上面拼接后的加密激活码再次摘要为 32 位 大写MD5
    if (openssl_pkey_get_public($public_key) == false)//私钥校验
    {
        echo "Server validation error";//私钥验证不通过
        exit();//关闭脚本
    }else{
        openssl_public_encrypt($key,$key,$public_key);//通过公钥加密真激活码
        $key = base64_encode($key);//编码转换
        echo '"';
        echo $key;//展现接口
        echo '"';
        /** 更改订单日志状态
         *
         */
        $query_trust = new pay_up_db();//实例化
        $query_trust -> db_r("user_trust", "y_state", 1, "y_key", $_GET['Key']);
        exit();//关闭脚本
    }
}else{
    echo "\"n1XJ5dKk8whL7U85tERiZ4k0UiUdq+r/gxrAUMEP/fFaVdrI9pmFMAL0Yl2Sqc/aS/YCBuNFaRYlHY7rItlrze6HA/FvLiCR1AmGcX1BMmIrx8g5qlOScK5sX/PDacb5t8H7go1HY7Po3xuueiQS2r233XE+yHvewb1SQ4VHuqo=\"";//无法被证实
}
exit();