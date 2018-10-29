<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>山樱|Software_Sales-y</title>
</head>

<style>
    html,body{width:100%;height:100%;padding:0;margin:0;font-family:"Microsoft YaHei",sans-serif;background:url(assets/img/background.jpg) no-repeat;background-size: 100% 100%;position: absolute;}
    .header{width:100%;margin:0 auto;height:230px}.container{background: rgba(255,255,255,0.9);width:100%;min-width:100px;height:auto}
    .guanzhuqr img{margin-top:10px;width:80px}.nav{width:1200px;margin:0 auto;height:70px;}
    .open,.logo{display:block;float:left;height:40px;width:85px;margin-top:20px}.divier{display:block;float:left;margin-left:20px;margin-right:20px;margin-top:23px;width:1px;height:24px;background-color:#d3d3d3}
    .open{line-height:30px;font-size:20px;text-decoration:none;color:#1a1a1a}.navbar{float:right;width:200px;height:40px;margin-top:15px;list-style:none}
    .navbar li{float:left;width:100px;height:40px}.navbar li a{display:inline-block;width:100px;height:40px;line-height:40px;font-size:16px;color:#1a1a1a;text-decoration:none;text-align:center}
    .navbar li a:hover{color:#0ae}.content{width:100%;min-width:1200px;height:660px}
    .payform{width:800px;margin:0 auto;height:480px;border:1px solid #0ae;background: rgba(255,255,255,0.5);}
    .element{width:600px;height:80px;margin-left:90px;font-size:20px}.etitle,.einput{float:left;height:26px}
    .etitle{width:150px;line-height:26px;text-align:right}.einput{width:200px;margin-left:20px}
    .einput input{width:398px;height:24px;border:1px solid #0ae;font-size:16px}.mark{margin-top:10px;width:500px;height:30px;margin-left:80px;line-height:30px;font-size:12px;color:#171717}
    .legend{margin-left:120px;font-size:24px}.paysubmit{width:250px;height:40px;border:0;background-color:#0ae;font-size:16px;color:#FFF;cursor:pointer;margin-left:200px}</style>
<body>
<div class="header">
    <div class="container">
        <div class="nav">
            <a href="#" class="logo"><img src="assets/img/logo.png" height="33px"></a>
            <span class="divier"></span>
            <a href="#" class="open" target="_blank">敬亭山</a>
            <ul class="navbar">
                <li><a href="#" target="_blank">English</a></li>
                <li><a href="query.php" target="_top">查询激活</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="content">
    <form action="pagepay/pagepay.php" class="payform" method="post" target="_blank">
        <div class="element" style="margin-top:60px;">
            <div class="legend">请在此处填写信息，开启您的正版权益。</div>
        </div>
        <div class="element">
            <div class="etitle">凭证邮箱:</div>
            <div class="einput"><input type="email" name="Email" placeholder=" 如：name@mail.com" required/></div> <br>
            <div class="mark" style="color:#000000"><b>需要注意：您务必核对邮箱是否填写正确，以便软件激活凭证妥投。</b></div>
        </div>
        <div class="element">
            <div class="etitle">授权编号:</div>
            <div class="einput"><input type="text" value="<?php
                /** 接受地址栏参数
                 * User: Jianxiong2333
                 * Date: 2018-08-10
                 * Time: 22:42
                 */
                error_reporting(0);//屏蔽报错
                echo $_GET['Code'];
                ?>" name="Code" placeholder=" 粘贴授权编号，或者直接访问购买链接。" required/></div> <br>
            <div class="mark" style="color:#000000"><b>需要注意：授权编码将会在软件页面出现，可选多种方式填写编号。</b></div>
        </div>
        <div class="element">
            <div class="etitle">支付金额:</div>
            <div class="einput">￥160（标准版）</div> <br>
            <div class="mark">以上的价格为授权单台设备的费用，授权多台设备需另外购买。</div>
        </div>
        <div class="element">
            <input type="submit" class="paysubmit" value ="确认支付">
        </div>
    </form>
</div>
</body>
</html>