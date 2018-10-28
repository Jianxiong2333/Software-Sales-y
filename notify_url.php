<?php
/* *
 * 功能：支付宝服务器异步通知页面
 * 版本：2.0
 * 修改日期：2017-05-01(Alipay)/2018-10-1(Jianxiong2333)
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。

 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 */
require_once 'config.php';
require_once 'pagepay/service/AlipayTradeService.php';
require_once 'class/DB/db_all.php';//链接数据库
$arr=$_POST;
$alipaySevice = new AlipayTradeService($config);
$alipaySevice->writeLog(var_export($_POST,true));
$result = $alipaySevice->check($arr);
/* 实际验证过程建议商户添加以下校验。
1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
4、验证app_id是否为该商户本身。
*/

//验证成功
if($result) {
    /**
     * Created by PhpStorm.
     * User: jianxiong2333
     * Date: 2018-08-27
     * Time: 16:21
     */
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //请在这里加上商户的业务逻辑程序代码
    //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表

    //商户订单号
    $out_trade_no = $_POST['out_trade_no'];

    //支付宝交易号
    $trade_no = $_POST['trade_no'];

    //交易状态
    $trade_status = $_POST['trade_status'];

    //已完成状态
    if($_POST['trade_status'] == 'TRADE_FINISHED') {
        //判断该笔订单是否在商户网站中已经做过处理
        //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
        //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
        //如果有做过处理，不执行商户的业务程序

        //注意：
        //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
    }

    //已付款状态
    else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
        //判断该笔订单是否在商户网站中已经做过处理
        //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
        //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
        //如果有做过处理，不执行商户的业务程序
        //注意：
        //付款完成后，支付宝系统发送该交易状态通知。

        /** 读取日志表
         *  获取商户订单号、邮箱等信息供验证
         */
        $email_r = new pay_db_r_all();//实例化读结果集
        $trust_array = $email_r ->  db_r('user_log', 'y_order', $out_trade_no);
        $ip = $trust_array['y_ip'];//日志ip
        $email = $trust_array['y_email'];//此索引数组为用户邮箱
        $code = $trust_array['y_code'];//机器码
        //计算key值
        $key = strtoupper(substr(md5($code),8,16));//机器码转MD5 -》 截取中间16位 -》 转大写
        $key = preg_replace("/([0-9a-z]{4})([0-9a-z]{4})([0-9a-z]{4})([0-9a-z]{4})/i", '${1}-${2}-${3}-${4}', $key);//使用正则，4个字符中间替换一个斜杠

        /** 写入正版表
         *  把购买软件的用户写入正版用户表
         */
        $trust_w = new pay_db_w();//实例化写正版表
        $trust_arr = array(null, $ip, $trade_no, $out_trade_no, $email, $code, $key, $saleprice);//0下标不使用，置空（入参准备）价格参数来自配置文件
        $trust_w -> db_w("INSERT INTO user_trust(y_ip, y_trade_no, y_order, y_email, y_code, y_key, y_price, y_state) VALUES (?, ?, ?, ?, ?, ?, ?, 0)", 7, $trust_arr);//预先sql语句，参数数量，ip、交易号、订单号、email、机器码、激活码、购买价格、状态数组参数

        /** 更改订单日志状态
         *
         */
        $query_log_no = new pay_up_db();//实例化
        $query_log_no -> db_r("user_log", "y_state", 1, "y_code", $code);

        /** 邮件发送
         * 以下代码来自 woider 以及 PHPMailer 项目
         * 在此致谢
         */
        require_once 'class/PHPMailer/SendMailer.php';
        $mailer = new Mailer(true);// 实例化SendMailer
        $title = '山樱|激活码';// 邮件标题
        $content ="尊敬的山樱用户，您的购买已生效！<br />下为订单信息：<br />
            <font color=\"#FFC0FF\">=</font><font color=\"#F7B8FF\">=</font><font color=\"#EFB0FF\">=</font><font color=\"#E7A8FF\">=</font><font color=\"#DFA0FF\">=</font><font color=\"#D798FF\">=</font><font color=\"#CF90FF\">=</font><font color=\"#C788FF\">=</font><font color=\"#C080FF\">=</font><font color=\"#C078FF\">=</font><font color=\"#C070FF\">=</font><font color=\"#C068FF\">=</font><font color=\"#C060FF\">=</font><font color=\"#C058FF\">=</font><font color=\"#C050FF\">=</font><font color=\"#C048FF\">=</font><font color=\"#C040FF\">=</font><font color=\"#C038FF\">=</font><font color=\"#C030FF\">=</font><font color=\"#C028FF\">=</font><font color=\"#C020FF\">=</font><font color=\"#C018FF\">=</font><font color=\"#C010FF\">=</font><font color=\"#C008FF\">=</font><font color=\"#C000FF\">=</font><font color=\"#C000FF\">=</font><font color=\"#C000FF\">=</font><font color=\"#C000FF\">=</font><font color=\"#C000FF\">=</font><br />
            产品名：山樱“标准版” | 激活码一枚<br />
            购买渠道：官方支付渠道，支付宝支付<br />
            订单号：$trade_no<br />
            激活码：$key<br />
            备注信息：建议您在本地妥善保存此注册凭证,<br />
            如遇凭证丢失前往 bbs.xXxXx.cn 寻求帮助<br />
            <font color=\"#C000FF\">=</font><font color=\"#C808FF\">=</font><font color=\"#D010FF\">=</font><font color=\"#D818FF\">=</font><font color=\"#E020FF\">=</font><font color=\"#E828FF\">=</font><font color=\"#F030FF\">=</font><font color=\"#F838FF\">=</font><font color=\"#FF40FF\">=</font><font color=\"#FF48FF\">=</font><font color=\"#FF50FF\">=</font><font color=\"#FF58FF\">=</font><font color=\"#FF60FF\">=</font><font color=\"#FF68FF\">=</font><font color=\"#FF70FF\">=</font><font color=\"#FF78FF\">=</font><font color=\"#FF80FF\">=</font><font color=\"#FF88FF\">=</font><font color=\"#FF90FF\">=</font><font color=\"#FF98FF\">=</font><font color=\"#FFA0FF\">=</font><font color=\"#FFA8FF\">=</font><font color=\"#FFB0FF\">=</font><font color=\"#FFB8FF\">=</font><font color=\"#FFC0FF\">=</font><font color=\"#FFC0FF\">=</font><font color=\"#FFC0FF\">=</font><font color=\"#FFC0FF\">=</font><font color=\"#FFC0FF\">=</font><br />
            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;山樱官方支付团队<br />";//邮件内容,发送支付宝订单号，而不是统一订单号
        // 发送邮件
        $mailer->send($email, $title, $content);
    }
    //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
    echo "success";	//请不要修改或删除，验证成功
}else{//验证失败
    echo "fail";
}