<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="expires" content="0">    
<meta http-equiv="keywords" content="keyword1,keyword2,keyword3">
<meta http-equiv="description" content="This is my page">
<title><?php echo ($contract_list["0"]["realname"]); ?>合同</title>
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width"><meta name="format-detection" content="email=no,address=no,telephone=no">
<link href="/Public/css/normalize.css" rel="stylesheet" type="text/css">
<link href="/Public/css/bootstrap.min.css" rel="stylesheet">
<link href="/Public/css/bootstrap-theme.min.css" type="text/css">
<script src="/Public/js/jquery.min.js"></script>
<script src="/Public/js/bootstrap.min.js"></script>
<link href="/Public/css/common.css" rel="stylesheet" type="text/css">
<link href="/Public/css/bill.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/Public/js/iscroll.js"></script>
<script type="text/javascript">
var myScroll;

function loaded () {
	myScroll = new IScroll('#wrapper', { mouseWheel: true, click: true });
	         
	/*if('1' == '1'){
		document.getElementById("jf").style.display="none";
	}
	if('1' == '0'){
		document.getElementById("pz").value="返回";
		document.getElementById("pz").setAttribute("onclick", "toIndex();");
	}
	if('0.0' == '0.0'){
		document.getElementById("fz").style.display="none";
	}*/
}

document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false)
function toIndex(){
	location.href="toAccountIndex.do";
}


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
        	<div class="col-xs-3 header-icon1"><a href="javascript:window.history.go(-1)"><div class="retun-db-box"><img src="/Public/images/icon-db-retun.png" class="icon-db-retun"></div></a></div>
         <div class="col-xs-3"></div><div class="col-xs-3"></div>  <!--  <div class="col-xs-3 header-icon2-1"><a href="#"><div class="header-icon2-1icon"><img src="view/images/icon-tenant-but-wdj.png" class="icon-db-but-z" /></div><span class="header-icon1-1text">本期账单</span></a></div>
            <div class="col-xs-3 header-icon2-2"><a href="#"><div class="header-icon2-2icon"><img src="view/images/icon-tenant-but-ydj.png" class="icon-db-but-y" /></div><span class="header-icon2-2text">往前账单</span></a></div> -->
            <div class="col-xs-3 header-icon3"><a href="<?php echo U('Home/Steward/index');?>"><div class="menu-db-box"><img src="/Public/images/icon-db-menu.png" class="icon-db-menu"></div></a></div>
        </div>
    </div>


    <div id="wrapper">
    <?php if(is_array($contract_list)): $i = 0; $__LIST__ = $contract_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div>
			<div class="container-fluid" id="XS">
                <div class="row-fluid">
                    <div class="col-xs-12">
                        <div class="row-fluid">
                            <div class="col-xs-12"><div class="innre-bill-image-ia"></div></div>
                        </div>   
                        <!--点击弹出开-->
                        <div class="row-fluid">
                            <div class="col-xs-12 innre-bill-box" style="text-align: center; font-size: 20px;">
                                <b>合同<?php echo ($key+1); ?></b>
                            </div>
                        </div>
                        <!--点击弹出关--> 
                        <!--点击弹出开-->
                        <div class="row-fluid">
                            <div class="col-xs-12 innre-bill-box">
                                <a data-toggle="collapse" data-parent="#accordion" href="">
                                <div class="panel-heading innre-bill-image-i">
                                    <ul>
                                    <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                    <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                    <li class="innre-bill-text3"><?php echo ($vo["area_name"]); ?></li>
                                    <li class="innre-bill-text1">小区</li><br>
                                    </ul>
                                </div>
                                </a>
                            </div>
                        </div>
                        <!--点击弹出关-->
                        <!--点击弹出开-->
                        <div class="row-fluid">
                            <div class="col-xs-12 innre-bill-box">
                                <a data-toggle="collapse" data-parent="#accordion" href="">
                                <div class="panel-heading innre-bill-image-i">
                                    <ul>
                                    <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                    <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                    <li class="innre-bill-text3"><?php echo ($vo["house_code"]); ?></li>
                                    <li class="innre-bill-text1">房屋编号</li><br>
                                    </ul>
                                </div>
                                </a>
                            </div>
                        </div>
                        <!--点击弹出关-->
                        <!--点击弹出开-->
                        <div class="row-fluid">
                            <div class="col-xs-12 innre-bill-box">
                                <a data-toggle="collapse" data-parent="#accordion" href="">
                                <div class="panel-heading innre-bill-image-i">
                                    <ul>
                                    <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                    <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                    <li class="innre-bill-text3"><?php echo ($vo["mobile"]); ?></li>
                                    <li class="innre-bill-text1">租客电话</li><br>
                                    </ul>
                                </div>
                                </a>
                            </div>
                        </div>
                        <!--点击弹出关-->  
                        <!--点击弹出开-->
                        <div class="row-fluid">
                            <div class="col-xs-12 innre-bill-box">
                                <a data-toggle="collapse" data-parent="#accordion" href="">
                                <div class="panel-heading innre-bill-image-i">
                                    <ul>
                                    <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                    <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                    <li class="innre-bill-text3"><?php echo ($vo["pro_id"]); ?></li>
                                    <li class="innre-bill-text1">账单号</li><br>
                                    </ul>
                                </div>
                                </a>
                            </div>
                        </div>
                        <!--点击弹出关-->                 
                        <!--点击弹出开-->
                        <div class="row-fluid">
                            <div class="col-xs-12 innre-bill-box">
                                <a data-toggle="collapse" data-parent="#accordion" href="">
                                <div class="panel-heading innre-bill-image-i">
                                    <ul>
                                    <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                    <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                    <li class="innre-bill-text3">￥<?php echo ($vo["deposit"]); ?></li>
                                    <li class="innre-bill-text1">押金</li><br>
                                    </ul>
                                </div>
                                </a>
                            </div>
                        </div>
                        <!--点击弹出关-->
                        <!--点击弹出开-->
                        <div class="row-fluid">
                            <div class="col-xs-12 innre-bill-box">
                                <a data-toggle="collapse" data-parent="#accordion" href="">
                                <div class="panel-heading innre-bill-image-i">
                                    <ul>
                                    <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                    <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                    <li class="innre-bill-text3">￥<?php echo ($vo["rent"]); ?></li>
                                    <li class="innre-bill-text1">房租</li><br>
                                    </ul>
                                </div>
                                </a>
                            </div>
                        </div>
                        <!--点击弹出关-->
                        <!--点击弹出开-->
                        <div class="row-fluid">
                            <div class="col-xs-12 innre-bill-box">
                                <a data-toggle="collapse" data-parent="#accordion" href="">
                                <div class="panel-heading innre-bill-image-i">
                                    <ul>
                                    <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                    <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                    <li class="innre-bill-text3">￥<?php echo ($vo["fee"]); ?></li>
                                    <li class="innre-bill-text1">服务费</li><br>
                                    </ul>
                                </div>
                                </a>
                            </div>
                        </div>
                        <!--点击弹出关-->
                        <!--点击弹出开-->
                        <div class="row-fluid">
                            <div class="col-xs-12 innre-bill-box">
                                <a data-toggle="collapse" data-parent="#accordion" href="">
                                <div class="panel-heading innre-bill-image-i">
                                    <ul>
                                    <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                    <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                    <li class="innre-bill-text3">￥<?php echo ($vo["wg_fee"]); ?></li>
                                    <li class="innre-bill-text1">物业费</li><br>
                                    </ul>
                                </div>
                                </a>
                            </div>
                        </div>
                        <!--点击弹出关-->
                        <!--点击弹出开-->
                        <div class="row-fluid">
                            <div class="col-xs-12 innre-bill-box">
                                <a data-toggle="collapse" data-parent="#accordion" href="http://mp.loveto.co/#collapseFour-wx">
                                <div class="panel-heading innre-bill-image-i">
                                    <ul>
                                    <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                    <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                    <li class="innre-bill-text3">￥<?php echo ($vo["book_deposit"]); ?></li>
                                    <li class="innre-bill-text1">缴定金额</li><br>
                                    </ul>
                                </div>
                                </a>
                            </div>
                        </div>
                        <!--点击弹出关-->
                        <!--点击弹出开-->
                        <div class="row-fluid">
                            <div class="col-xs-12 innre-bill-box">
                                <a data-toggle="collapse" data-parent="#accordion" href="http://mp.loveto.co/#collapseFour-wx">
                                <div class="panel-heading innre-bill-image-i">
                                    <ul>
                                    <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                    <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                    <li class="innre-bill-text3">￥<?php echo ($vo["balance"]); ?></li>
                                    <li class="innre-bill-text1">余额使用</li><br>
                                    </ul>
                                </div>
                                </a>
                            </div>
                        </div>
                        <!--点击弹出关-->
                         <!--点击弹出开-->
                        <div class="row-fluid">
                            <div class="col-xs-12 innre-bill-box">
                                <a data-toggle="collapse" data-parent="#accordion" href="http://mp.loveto.co/#collapseFour-wx">
                                <div class="panel-heading innre-bill-image-i">
                                    <ul>
                                    <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                    <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                    <li class="innre-bill-text3"><?php echo ($vo["period"]); ?></li>
                                    <li class="innre-bill-text1">缴费周期</li><br>
                                    </ul>
                                </div>
                                </a>
                            </div>
                        </div>
                        <!--点击弹出关-->
                         <!--点击弹出开-->
                        <div class="row-fluid">
                            <div class="col-xs-12 innre-bill-box">
                                <a data-toggle="collapse" data-parent="#accordion" href="http://mp.loveto.co/#collapseFour-wx">
                                <div class="panel-heading innre-bill-image-i">
                                    <ul>
                                    <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                    <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                    <li class="innre-bill-text3">押<?php echo ($vo["rent_type"]["0"]); ?>付<?php echo ($vo["rent_type"]["1"]); ?></li>
                                    <li class="innre-bill-text1">押付类型</li><br>
                                    </ul>
                                </div>
                                </a>
                            </div>
                        </div>
                        <!--点击弹出关-->
                        <!--点击弹出开-->
                        <div class="row-fluid">
                            <div class="col-xs-12 innre-bill-box">
                                <a data-toggle="collapse" data-parent="#accordion" href="http://mp.loveto.co/#collapseFour-wx">
                                <div class="panel-heading innre-bill-image-i">
                                    <ul>
                                    <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                    <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                    <li class="innre-bill-text3"><?php echo ($vo["start_time"]); ?></li>
                                    <li class="innre-bill-text1">租期开始日</li><br>
                                    </ul>
                                </div>
                                </a>
                            </div>
                        </div>
                        <!--点击弹出关-->
                        <!--点击弹出开-->
                        <div class="row-fluid">
                            <div class="col-xs-12 innre-bill-box">
                                <a data-toggle="collapse" data-parent="#accordion" href="http://mp.loveto.co/#collapseFour-wx">
                                <div class="panel-heading innre-bill-image-i">
                                    <ul>
                                    <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                    <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                    <li class="innre-bill-text3"><?php echo ($vo["end_time"]); ?></li>
                                    <li class="innre-bill-text1">租期结束日</li><br>
                                    </ul>
                                </div>
                                </a>
                            </div>
                        </div>
                        <!--点击弹出关-->
                        <!--点击弹出开-->
                        <div class="row-fluid">
                            <div class="col-xs-12 innre-bill-box">
                                <a data-toggle="collapse" data-parent="#accordion" href="http://mp.loveto.co/#collapseFour-wx">
                                <div class="panel-heading innre-bill-image-i">
                                    <ul>
                                    <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                    <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                    <li class="innre-bill-text3"><?php echo ($vo["rent_date"]); ?></li>
                                    <li class="innre-bill-text1">房租到期日</li><br>
                                    </ul>
                                </div>
                                </a>
                            </div>
                        </div>
                        <!--点击弹出关-->
                        <!--滑动过度区域-->
                        <div class="row-fluid">
                            <div class="col-xs-12"><div style="height:90px;"></div></div>
                        </div>
                        <!--滑动过度区域-->
                    </div>
                </div>
			</div>          
        </div><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>

    
</div>
<script type="text/javascript">
   $(function () { $('#collapseFour').collapse({
      toggle: false
   })});
   $(function () { $('#collapseTwo').collapse('show')});
   $(function () { $('#collapseThree').collapse('toggle')});
   $(function () { $('#collapseOne').collapse('hide')});
   
</script>  


</body></html>