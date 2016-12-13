<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!-- saved from url=(0038)http://mp.loveto.co/account/feeList.do -->
<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <base href=".">
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">    
	<meta http-equiv="keywords" content="keyword1,keyword2,keyword3">
	<meta http-equiv="description" content="This is my page">
	<title>未支付账单</title>
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width"><meta name="format-detection" content="email=no,address=no,telephone=no">
    <link href="/Public/css/normalize.css" rel="stylesheet" type="text/css">
    <link href="/Public/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Public/css/bootstrap-theme.min.css" type="text/css">
    <link href="/Public/css/common.css" rel="stylesheet" type="text/css">
    <link href="/Public/css/bill.css" rel="stylesheet" type="text/css">
    <script src="/Public/js/jquery-1.9.1.js" type="text/javascript"></script>
    <script src="/Public/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/Public/js/iscroll.js"></script>
    <script type="text/javascript">
    var myScroll;

    function loaded () {
    	myScroll = new IScroll('#wrapper', { mouseWheel: true ,click:true});
    }

    document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false)

    </script>
    <style>
    #wrapper {
    	position:absolute;
    	top:44px;
    	bottom:0px;
    	left:0;
    	width:100%;
    	overflow:hidden;
    	background-color:#f4f4f4;
    	}
    .img-responsive {
    	display: inline-block;
    	height: auto;
    	max-width: 100%;
    	}
    body{
    	line-height:normal;
    	}
    ol, ul{
    	margin-bottom:0;
    	}
    </style>

</head>

<body onload="loaded()">
<!--遮盖层头-->
<!--<div id="loading" style="width:auto; height:1080px; background-color:rgba(51,51,51,0.4); z-index:100; position:relative;">
	<img src="view/images/loading1.gif" class="load-h-w" />
</div>-->
<!--遮盖层尾-->
<div id="fwxx">
	<div class="container-fluid header">
    	<div class="row-fluid">
        	<div class="col-xs-3 header-icon1"><a href="<?php echo U('Home/Tenant/index');?>"><div class="retun-db-box"><img src="/Public/images/icon-db-retun.png" class="icon-db-retun"></div></a></div>
        </div>
    </div>
    <div id="wrapper">
        <div style="transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
			<div class="container-fluid" id="XS">
                <div class="row-fluid">
                    <div class="col-xs-12">
                    	<div class="row-fluid">
                            <div class="col-xs-12"><div class="innre-bill-ls-dsh"></div></div>
                        </div>
                        <?php if(is_array($notpaylist)): $i = 0; $__LIST__ = $notpaylist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="row-fluid">
                                <div class="col-xs-12 innre-bill-ls-box">
                                    <div class="bill-ls-box-backcolor">
                                    	<div class="img-icon_ywcd-box">
                                            <?php if($vo["pay_status"] == 1): ?><img src="/Public/images/icon_ywcd.jpg" class="icon_ywcd-float"><?php endif; ?>
                                        </div>
                                    		<a href="<?php echo U('Home/Tenant/detail_fee',array('id'=>$vo['id']));?>"><div class="but-bot-ckxq" onclick="">查看详情</div></a>
                                            <!-- <a href="<?php echo U('Home/Tenant/detail_fee',array('id'=>$vo['id']));?>"><div class="but-bot-ckxq2" onclick="">立即支付</div></a> -->
                                    	<p class="innre-bill-ls-bot">账单ID号:<?php echo ($vo["id"]); ?></p>
                                        <ul>
                                        <li class="innre-bill-ls-text1">支付金额：￥<?php echo ($vo["total_fee"]); ?></li>
                                        <li class="innre-bill-ls-text2">支付类型：<?php echo ($vo["type_name"]); ?></li>
                                        <li class="innre-bill-ls-text3">支付时间：<?php echo ($vo["pay_time"]); ?></li>
                                        </ul>
                                        <p class="innre-bill-ls-box-bot"></p><!--误删，若要添加项目直接复制上面innre-bill-ls-text1~3条任意一条即可。-->
                                    </div>
                                    
                                </div>
                            </div><?php endforeach; endif; else: echo "" ;endif; ?>
                        <div class="row-fluid">
                            <div class="col-xs-12 innre-bill-ls-box">
                                <div class="bill-ls-box-backcolor">
                                    <p class="innre-bill-ls-bot">总未支付金额:<?php echo ($sum_total_fee); ?></p>
                                    <p class="innre-bill-ls-bot">押金:<?php echo ($deposit); ?></p>
                                    <p class="innre-bill-ls-bot">抵押后押金余额:<?php echo ($residue_deposit); ?></p>
                                </div>
                            </div>
                        </div>
                        <!--滑动过度区域-->
                        <div class="row-fluid">
                            <div class="col-xs-12"><div style="height:90px;"></div></div>
                        </div>
                        <!--滑动过度区域-->
                    </div>
                </div>
			</div>          
        </div>  
    </div>
</div>

</body></html>