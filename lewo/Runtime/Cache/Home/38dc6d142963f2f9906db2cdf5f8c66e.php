<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!-- saved from url=(0040)http://mp.loveto.co/account/myManager.do -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">    
	<meta http-equiv="keywords" content="keyword1,keyword2,keyword3">
	<meta http-equiv="description" content="This is my page">
	<title>我的管家</title>
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
    	myScroll = new IScroll('#wrapper', { mouseWheel: true, click: true  });
    }

    document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false)

    </script>
    <style>
    #wrapper {
    	position:absolute;
    	top:44px;
    	bottom:0;
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
    a:hover{
    	color:#222222;
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
        	<div class="col-xs-4 header-icon1"><a href="javascript:window.history.back(-1)"><div class="retun-db-box"><img src="/Public/images/icon-db-retun.png" class="icon-db-retun"></div></a></div>
        </div>
    </div>
    <div id="wrapper">
        <div style="transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
			<div class="container-fluid" id="XS">
                <div class="row-fluid">
                    <div class="col-xs-12">
                    	<!--第一部分开-->
                    	<div>
                            <div class="row-fluid">
                                <div class="col-xs-12">
                                	<div class="wdgj-top-box">
                                        <div class="wdgj-top-icon-box"><img src="/Public/images/user.jpg" class="wdgj-top-icon-tx"></div>
                                        <div class="wdgj-top-text-box">
                                            <span class="text-wdgj-xm-ys"><?php echo ($account_info["realname"]); ?></span>
                                            <p class="text-wdgj-gxsm-ys"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="col-xs-12 text-wdgj-rs">我的管家<span class="text-wdgj-rs-ys">（1位管家）</span></div>
                            </div>
                        </div>
                        <!--第一部分关-->
                        <!--第二部分开-->
                        <div>
                            <div class="row-fluid">
                                <div class="col-xs-12">
                                	<div class="top-height-10"></div>
                                	<a href="tel:<?php echo ($steward_info["mobile"]); ?>">
                                	<div class="wdgjtx-inner-box wdgj-back-color-ys">
                                        <div class="wdgjtx-inner-icon-box"><img src="/Public/images/steward_head.png" class="wdgjtx-inner-icon-tx"></div>
                                        <div class="wdgjtx-inner-text-box">
                                        	<ul>
                                            <li class="wdgjtx-inner-xm-ys"><?php echo ($steward_info["nickname"]); ?></li>
                                            <li><img src="/Public/images/icon-wdgj-sjhm.png" class="icon-wdgj-sjhm-ys"><span class="wdgjtx-sjhm-overflow-hidden"><?php echo ($steward_info["mobile"]); ?></span></li>
                                            <button type="button" class="but-bot-wdgj-lxt">联系他</button>
                                            </ul>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="col-xs-12">
                                	<div class="text-wdgj-bord-top-box wdgj-back-color-ys">
                                		<p class="text-wdgj-bord-top">我们会努力为您服务&nbsp;·&nbsp;·&nbsp;·</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--第二部分关-->
                       
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