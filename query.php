<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>樱花|激活码查询</title>
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
    .einput input{width:398px;height:24px;border:1px solid #0ae;font-size:16px}
    .legend{margin-left:120px;font-size:24px}.paysubmit{width:250px;height:40px;border:0;background-color:#0ae;font-size:16px;color:#FFF;cursor:pointer;margin-left:200px}
    .element a{border-bottom:1px dashed #111;text-decoration:none;}
    .element a:link{color: #000000;}
    .element a:visited{color: #000000;}
    .element a:hover{border:0;}
</style>
<body>
<div class="header">
    <div class="container">
        <div class="nav">
            <a href="#" class="logo"><img src="assets/img/logo.png" height="33px"></a>
            <span class="divier"></span>
            <a href="#" class="open" target="_blank">敬亭山</a>
            <ul class="navbar">
                <li><a href="#" target="_blank">English</a></li>
                <li><a href="index.php" target="_top">购买软件</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="content">
    <?php
    /*************************内嵌 PHP 开始*************************
     * Created by PhpStorm.
     * User: jianxiong2333
     * Date: 2018-09-06
     * Time: 16:36
     */
    error_reporting(0);//防止报错
    //查询窗口
    $html_body = '
        <form action="" class="payform" method="post" target="_top">
        <div class="element" style="margin-top:105px;">
            <div class="legend">请在此处填写信息，查询您的正版权益。</div>
        </div><br>
        <div class="element">
            <div class="etitle">支付宝交易号:</div>
            <div class="einput"><input type="text" name="trade_no" placeholder=" 可在授权邮件内、购买返回页、支付宝账单中找到。" required/></div> <br>
        </div><br>
        <div class="element">
            <input type="submit" class="paysubmit" value ="确认查询">
        </div>
        </form>';

    //查询失败
    $html_error = '
    <div class="payform">
        <div class="element" style="margin-top:105px;">
            <div class="legend">------------ 授权查询结果 ------------</div>
        </div>
        <div class="element">
            <div style="font-size:14px; margin-left:7%; line-height:21px;">&nbsp;&nbsp;&nbsp;&nbsp;非常抱歉，您输入的交易凭证无法被证实，点击 <a href="https://cshall.alipay.com/lab/help_detail.htm?help_id=201602058759" target="_blank">此处链接</a> 了解如何正确获取交易号。<br>若确认交易号输入正确无误，请您前往 <a href="https://" target="_blank">bbs.XxXxX.cn(官方社区)</a> 联系我们。</div>
        </div>
        <div class="element">
            <input type="submit" class="paysubmit" onclick="history.back(-1)" value ="重新查询">
        </div>
    </div>';

    //获取 POST 支付宝交易号
    $trade_no ="";//初始化防止报错
    $trade_no = $_POST['trade_no'];
    /*************************参数本地处理开始************************/
    if($trade_no == null)//检测 POST 参数是否存在
    {
        echo $html_body;//未传递参数，显示激活查询窗口
    } else {
        //if(isset($trade_no{27}))//检测第 27 下标是否存在（即字符长度不小于28位）效率高，但实用性不强
        //参数已传递，本地检查参数是否合规
        if(strlen($trade_no) == 28)//检测是否为28位字符
        {
            if(!is_numeric($trade_no))//检测数据类型是否为全数值型(取反)
            {
                echo $html_error;//数据类型不符合报错
            } else {
                /*************************参数线上处理开始************************/
                require_once 'class/DB/db_all.php';//链接数据库

                $query_trade_no = new pay_db_r_all();//实例化读交易号
                $trust_array = $query_trade_no -> db_r('user_trust', 'y_trade_no', $trade_no);
                //if(isset($trust_array['y_trade_no']))//验证交易号是否存在以确认是否传回空值 不严谨，修正为验证支付订单号
                if($trust_array['y_trade_no'] == $trade_no)//对比线上传回交易号是否为本地 Post 传递交易号
                {
                    /*************************查询到数据************************/
                    //$trade_no = $trust_array['y_trade_no'];//支付交易号 本身用户传递的 Post 参数已存在
                    $key = $trust_array['y_key'];//激活码
                    $money = $trust_array['y_price'];//价格

                    //判断支付方式
                    $payer = $trust_array['y_order'][0]; //提取平台方标识
                    switch($payer)//将平台标识转换为字符串
                    {
                        case 'Z':
                            $payer = "支付宝";
                            break;
                        case 'W':
                            $payer = "微信支付";
                            break;
                        default:
                            $payer = "支付错误";
                            break;
                    }

                    //判断激活码状态
                    $key_state = $trust_array['y_state']; //提取激活码状态
                    switch($key_state)//将平台标识转换为字符串
                    {
                        case 0:
                            $key_state = "未激活";
                            break;
                        case 1:
                            $key_state = "已激活";
                            break;
                        case -1:
                            $key_state = "已禁用";
                            break;
                        default:
                            $key_state = "支付错误";
                            break;
                    }

                    //查询成功页面
                    $html_success = "
                    <div class='payform'>
                        <div class='element' style='margin-top:105px;'>
                            <div class='legend'>------------ 授权查询结果 ------------</div>
                        </div>
                        <div class='element' style='font-size:13px'>
                            <table cellpadding='9'>
                                <tr>
                                    <th>交易凭证</th>
                                    <th>激活凭证</th>
                                    <th>方式</th>
                                    <th>金额</th>
                                    <th>状态</th>
                                </tr>
                                <tr align='center'>
                                    <td>$trade_no</td>
                                    <td>$key</td>
                                    <td>$payer</td>
                                    <td>$$money</td>
                                    <td>$key_state</td>
                                </tr>
                            </table>
                        </div>
                        <div class='element'> </div>
                        <div class='element'>
                            <input type='submit' class='paysubmit' onclick='history.back(-1)' value ='重新查询'>
                        </div>
                    </div>";
                    echo $html_success;//输出成功页面
                }else{
                    /*************************未查询到数据************************/
                    echo $html_error;//未在数据库中查询到数据报错
                }
            }
        } else {
            echo $html_error;//长度不符合报错
        }
    }
    ?>
</div>
</body>
</html>