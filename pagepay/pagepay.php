<?php
/**
 * Created by PhpStorm.
 * User: Jianxiong2333
 * Date: 2018-08-14
 * Time:：10:52
 * 注意：保证安全，此代码运行环境为 PHP7.0 开启opcache（系统缓存）、apcu(用户缓存)，关闭 pathinfo 功能及 file_uploads 上传，memory_limit 从 128M 提升到 256M，
 * 无变量字符串推荐采用单引号，字符串建议使用.=拼接，数据库读写需严格按照 DB/db_all.php 文件的 POD 用法。
 */
error_reporting(0);//防止报错
header('Content-type: text/html; charset=UTF8'); // UTF8
require_once dirname(dirname(__FILE__)) . '/config.php';//配置文件
require_once dirname(__FILE__) . '/service/AlipayTradeService.php';
require_once dirname(__FILE__) . '/buildermodel/AlipayTradePagePayContentBuilder.php';
require_once '../class/DB/db_all.php';//数据库链接

/*支付环境检查类*/
class pay_env
{
    public $body;//商品描述，可空
    public $code;//授权编码，必须
    public $email;//激活邮箱，必须
    public $ip;//访问IP，必须
    public $out_trade_no;//商户订单号，必须
    public $private_key;//RSA私钥，必须
    public $subject;//订单名称，必须
    public $total_amount;//价格，必须

    /**
     * pay_env constructor.
     * @param $private_key string 机器码验签私钥
     * @param $saleprice int 出售价格
     */
    public function __construct($private_key, $saleprice)
    {
        /*传递 Post 参数*/
        $this->email = trim($_POST['Email']);
        $this->code = str_replace(" ", "+", trim($_POST['Code']));//参数传递中 +  被浏览器转义为空格，此处转回来。
        /*传递 普通 参数*/
        $this->total_amount = $saleprice;//价格
        $this->body = '一枚XXXX激活码';
        $this->subject = 'XXXX激活码';
        /*拼接订单号（效率最高）*/
        $this->out_trade_no = 'ZB';//支付宝付款+标准版
        $this->out_trade_no .= strtoupper(substr(hash('md5',$this->email), 0, 4));//转换大写（截取前四位MD5(邮箱整体，MD5为32位)）
        $this->out_trade_no .= date('YmdHis');//+四位年月日时分秒（带前导）
        $this->out_trade_no .= rand(0, 9);
        $this->out_trade_no .= rand(0, 9);//置入随机数
        $this->private_key = $private_key;
        /*----------------------------------参数传递结束------------------------------------------------------------*/
        /*检查网络环境及检查写入访问缓存
        $this->ip = $this->Get_ip();//获取IP
        if ($this->ip == 'Unknow') {
            echo "当前网络环境异常，请关闭代理软件或更换浏览器尝试，如无法解决请联系官方人员咨询。<br>";
            exit();//退出
        } else {
            $ip_r = new pay_db_r();//实例化读IP
            $ip_w = new pay_db_w();//实例化写IP
            if($ip_r -> db_r('access_log', 'IP', $this->ip) > 50)//限制IP访问
            {
                echo '勿频繁发起购买，请稍后访问。';
                exit();//退出
            }
            $IP[1] = $this->ip;//预先把参数导入数组（入参准备）
            $ip_w -> db_w("INSERT INTO access_log(IP) VALUES (?)", 1, $IP);//写IP缓存

        }*/

        /*检查邮箱环境*/
        if ($this->Get_email() != 1) {
            echo '邮箱输入错误，或者这个邮箱没有被注册。<br>';
            exit();//退出
        }

        /*检查私钥可用，并且解密激活码*/
        if ($this->key_testing() != 1) {
            echo $this->key_testing();//返回错误
            exit();//退出
        }

        /*检索机器码是否已经购买*/
        $code_r = new pay_db_r();//实例化读机器码
        if($code_r ->  db_r('user_trust', 'y_code', $this->code) != 0){
            echo '您已经购买过这台机器的激活码，无需再次购买。<br>';
            exit();//退出
        }

        /*写购买日志表*/
        $log_w = new pay_db_w();//实例化写日志
        $log_arr = array(null, $this->ip, $this -> out_trade_no, $this->email, $this->code);//0下标不使用，置空（入参准备）
        $log_w -> db_w("INSERT INTO user_log(y_ip, y_order, y_email, y_code, y_state) VALUES (?, ?, ?, ?, 0)", 4, $log_arr);//写IP缓存、订单号、ip、email、购买状态、机器码、版本（已取消）//写IP缓存、订单号、ip、email、购买状态、机器码、版本
    }

    /*获取IP*/
    public function Get_ip()
    {
        if (getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        else $ip = 'Unknow';//返回网络异常
        return $ip;
    }

    /*检测邮箱合法性*/
    public function Get_email()
    {
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) == false) //filter_va离线检测邮箱有效性
            return 0;//其中一个校验失败
        $arr = explode('@', $this->email);//兼容php7.0
        if (checkdnsrr(array_pop($arr), 'MX') == false)//checkdnsrr 通过 DNS 在线检测截取的邮箱域名是否存在MX解析。
            return 0;
        return 1;//两个校验均成功
    }

    /*私钥可信检测*/
    public function key_testing()
    {
        if (openssl_pkey_get_private($this->private_key) == false)//私钥校验
            return '当前交易不可信，请联系管理员。<br>';//校验失败
        else {
            if (openssl_private_decrypt(base64_decode($this->code), $this->code, $this->private_key) != 1)//私钥解密公钥加密到 Code, 成功返回1，失败返回0，错误返回-1
                return '机器码有误，请检查机器码输入。<br>';
        }
        return 1;//两个校验成功
    }

}

/*主程序入口*/
$env = new pay_env($private_key, $saleprice);
echo '正在转跳到支付页面...<br>';

/*构造参数*/
$payRequestBuilder = new AlipayTradePagePayContentBuilder();
$payRequestBuilder->setBody($env -> body);
$payRequestBuilder->setSubject($env -> subject);
$payRequestBuilder->setTotalAmount($env -> total_amount);
$payRequestBuilder->setOutTradeNo($env -> out_trade_no);

$aop = new AlipayTradeService($config);

/**
 * pagePay 电脑网站支付请求
 * @param $builder 业务参数，使用buildmodel中的对象生成。
 * @param $return_url 同步跳转地址，公网可以访问
 * @param $notify_url 异步通知地址，公网可以访问
 * @return $response 支付宝返回的信息
 */
$response = $aop->pagePay($payRequestBuilder,$config['return_url'],$config['notify_url']);//转跳

//输出表单
var_dump($response);
//不断开数据库连接是因为写了足够多的结束语句，能够保证连接在关闭页面后断开。

