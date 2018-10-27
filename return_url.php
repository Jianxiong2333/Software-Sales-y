<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
            html, body{width: 95%;height: 90%;}
            body{background-image: linear-gradient(45deg, #83a4d4, #b6fbff);}
            h1{color: #ffffff;}
            h4{color: #f9e395;}
            p{color:#F5F7FA;}
            a{ border-bottom:1px dashed #111;text-decoration:none;}
            a:link{color: #f9e395;}
            a:visited{color: #f9e395;}
            a:hover{border:0;}
        </style>
        <title>樱花|支付结果返回页面</title>
	</head>
    <body>
    <?php
    /* *
     * 功能：支付宝页面跳转同步通知页面
     * 版本：2.0
     * 修改日期：2017-05-01(Alipay)/2018-10-10(Jianxiong2333)
     * 说明：
     * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。

     *************************页面功能说明*************************
     * 该页面可在本机电脑测试
     * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
     */
    require_once("config.php");
    require_once 'pagepay/service/AlipayTradeService.php';


    $arr=$_GET;
    $alipaySevice = new AlipayTradeService($config);
    $result = $alipaySevice->check($arr);

    /* 实际验证过程建议商户添加以下校验。
    1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
    2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
    3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
    4、验证app_id是否为该商户本身。
    */
    if($result) {//验证成功
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //请在这里加上商户的业务逻辑程序代码

        //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
        //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

        //商户订单号
        $out_trade_no = htmlspecialchars($_GET['out_trade_no']);

        //支付宝交易号
        $trade_no = htmlspecialchars($_GET['trade_no']);

        echo "  <h1>:) 恭喜，支付成功！</h1>
                <p style=\"font-size:21px;font-weight:200;line-height:10%;letter-spacing:1pt\">激活码正在投递至凭证邮箱</p>
                <hr style=\"width:265px;margin-left:0\">
                <p>$trade_no
                (建议保存此支付宝交易号)</p>
                <h4>查询激活码？</h4>
                <p>
                    1、凭证邮箱查看激活码邮件；<br/>
                    2、通过支付宝付款历史或当前页面的支付宝交易号到<a href='query.php' target='_blank'>授权页面</a>查询激活；<br/>
                    3、如支付宝及凭证邮箱均无法查询，请往软件社区发帖寻求管理员帮助；
                </p>";

        //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    }
    else {
        //验证失败
        echo "支付失败，请联系我们的管理员~";
    }
    ?>
    </body>
</html>
