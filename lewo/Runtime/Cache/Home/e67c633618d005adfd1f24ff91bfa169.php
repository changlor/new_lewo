<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="expires" content="0">    
<meta http-equiv="keywords" content="keyword1,keyword2,keyword3">
<meta http-equiv="description" content="This is my page">
<title>本期账单</title>
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
        	<div class="col-xs-3 header-icon1"><a href="<?php echo U('Home/Steward/index');?>"><div class="retun-db-box"><img src="/Public/images/icon-db-retun.png" class="icon-db-retun"></div></a></div>
         <div class="col-xs-3"></div><div class="col-xs-3"></div>  <!--  <div class="col-xs-3 header-icon2-1"><a href="#"><div class="header-icon2-1icon"><img src="view/images/icon-tenant-but-wdj.png" class="icon-db-but-z" /></div><span class="header-icon1-1text">本期账单</span></a></div>
            <div class="col-xs-3 header-icon2-2"><a href="#"><div class="header-icon2-2icon"><img src="view/images/icon-tenant-but-ydj.png" class="icon-db-but-y" /></div><span class="header-icon2-2text">往前账单</span></a></div> -->
            <div class="col-xs-3 header-icon3"><a href="<?php echo U('Home/Tenant/feelist');?>"><div class="menu-db-box"><img src="/Public/images/icon-db-menu.png" class="icon-db-menu"></div></a></div>
        </div>
    </div>
    <div id="wrapper">
        <div>
			<div class="container-fluid" id="XS">
                <div class="row-fluid">
                    <div class="col-xs-12">
                        <div class="row-fluid">
                            <div class="col-xs-12 banner-box-bjs">
                                <div class="row-fluid">
                                    <div class="col-xs-12 banner-box-bjt"><img src="/Public/images/icon-tenant-top.png" class="icon-tenant-top">
                                        <div class="row-fluid">
                                            <div class="col-xs-12"><span class="banner-title-1"><?php echo ($area_name); ?>&nbsp;<?php echo ($bill_info["house_code"]); ?></span></div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="col-xs-6 banner-title-2"><p class="banner-title-2-1">总金额</p><p class="banner-title-2-2"></p></div>
                                            <div class="col-xs-6 banner-title-3"><p class="banner-title-3-1">￥<?php echo ($contract_info["total_fee"]); ?></p></div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="col-xs-6 banner-title-4"><p class="banner-title-4-1">应支付金额</p><p class="banner-title-4-2"></p></div>
                                            <div class="col-xs-6 banner-title-5">
                                                ￥<?php echo ($contract_info["price"]); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="col-xs-12"><div class="innre-bill-image-ia"></div></div>
                        </div>   
                        <!--点击弹出开-->
                        <div class="row-fluid">
                            <div class="col-xs-12 innre-bill-box">
                                <a data-toggle="collapse" data-parent="#accordion" href="">
                                <div class="panel-heading innre-bill-image-i">
                                    <ul>
                                    <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                    <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                    <li class="innre-bill-text3"><?php echo ($contract_info["mobile"]); ?></li>
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
                                    <li class="innre-bill-text3"><?php echo ($contract_info["pro_id"]); ?></li>
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
                                    <li class="innre-bill-text3">￥<?php echo ($contract_info["deposit"]); ?></li>
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
                                    <li class="innre-bill-text3">￥<?php echo ($contract_info["rent"]); ?></li>
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
                                    <li class="innre-bill-text3">￥<?php echo ($contract_info["fee"]); ?></li>
                                    <li class="innre-bill-text1">服务费</li><br>
                                    </ul>
                                </div>
                                </a>
                            </div>
                        </div>
                        <!--点击弹出关-->
                        <!--点击弹出开-->
                        <?php if($contract_info['wg_fee'] != 0): ?><div class="row-fluid">
                                <div class="col-xs-12 innre-bill-box">
                                    <a data-toggle="collapse" data-parent="#accordion" href="">
                                    <div class="panel-heading innre-bill-image-i">
                                        <ul>
                                        <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                        <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                        <li class="innre-bill-text3">￥<?php echo ($contract_info["wg_fee"]); ?></li>
                                        <li class="innre-bill-text1">物业费</li><br>
                                        </ul>
                                    </div>
                                    </a>
                                </div>
                            </div><?php endif; ?>
                        <!--点击弹出关-->
                        <!--点击弹出开-->
                        <?php if($contract_info['book_deposit'] != 0): ?><div class="row-fluid">
                                <div class="col-xs-12 innre-bill-box">
                                    <a data-toggle="collapse" data-parent="#accordion" href="http://mp.loveto.co/#collapseFour-wx">
                                    <div class="panel-heading innre-bill-image-i">
                                        <ul>
                                        <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                        <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                        <li class="innre-bill-text3">￥<?php echo ($contract_info["book_deposit"]); ?></li>
                                        <li class="innre-bill-text1">缴定金额</li><br>
                                        </ul>
                                    </div>
                                    </a>
                                </div>
                            </div><?php endif; ?>
                        <!--点击弹出关-->
                        <!--点击弹出开-->
                        <?php if($contract_info['balance'] != 0): ?><div class="row-fluid">
                                <div class="col-xs-12 innre-bill-box">
                                    <a data-toggle="collapse" data-parent="#accordion" href="http://mp.loveto.co/#collapseFour-wx">
                                    <div class="panel-heading innre-bill-image-i">
                                        <ul>
                                        <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                        <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                        <li class="innre-bill-text3">￥<?php echo ($contract_info["balance"]); ?></li>
                                        <li class="innre-bill-text1">余额使用</li><br>
                                        </ul>
                                    </div>
                                    </a>
                                </div>
                            </div><?php endif; ?>
                        <!--点击弹出关-->
                        <!--点击弹出开-->
                        <?php if($contract_info['favorable'] != 0): ?><div class="row-fluid">
                                <div class="col-xs-12 innre-bill-box">
                                    <a data-toggle="collapse" data-parent="#accordion" href="http://mp.loveto.co/#collapseFour-wx">
                                    <div class="panel-heading innre-bill-image-i">
                                        <ul>
                                        <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                        <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                        <li class="innre-bill-text3">￥<?php echo ($contract_info["favorable"]); ?></li>
                                        <li class="innre-bill-text1">优惠</li><br>
                                        </ul>
                                    </div>
                                    </a>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="col-xs-12 innre-bill-box">
                                    <a data-toggle="collapse" data-parent="#accordion" href="http://mp.loveto.co/#collapseFour-wx">
                                    <div class="panel-heading innre-bill-image-i">
                                        <ul>
                                        <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                        <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                        <li class="innre-bill-text3">￥<?php echo ($contract_info["favorable_des"]); ?></li>
                                        <li class="innre-bill-text1">优惠描述</li><br>
                                        </ul>
                                    </div>
                                    </a>
                                </div>
                            </div><?php endif; ?>
                        <!--点击弹出关-->
                         <!--点击弹出开-->
                        <div class="row-fluid">
                            <div class="col-xs-12 innre-bill-box">
                                <a data-toggle="collapse" data-parent="#accordion" href="http://mp.loveto.co/#collapseFour-wx">
                                <div class="panel-heading innre-bill-image-i">
                                    <ul>
                                    <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                    <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                    <li class="innre-bill-text3"><?php echo ($contract_info["period"]); ?></li>
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
                                    <li class="innre-bill-text3">押<?php echo ($contract_info["rent_type"]["0"]); ?>付<?php echo ($contract_info["rent_type"]["1"]); ?></li>
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
                                    <li class="innre-bill-text3"><?php echo ($contract_info["start_time"]); ?></li>
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
                                    <li class="innre-bill-text3"><?php echo ($contract_info["end_time"]); ?></li>
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
                                    <li class="innre-bill-text3"><?php echo ($contract_info["rent_date"]); ?></li>
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
        </div>  
    </div>
    <div class="container-fluid button">
        <div class="row-fluid button-ds">
            <div class="col-xs-12 padding-left-right-24">
                
            </div>
        </div>
    </div>
    <?php if($bill_info['pay_status'] != 1): ?><div class="container-fluid button">
        <div class="row-fluid button-ds">
            <div class=" padding-left-right-10" >
                <div class="button-ckzi2">
                    <a href="<?php echo U('Home/Steward/send_contract',array('pro_id'=>$contract_info['pro_id']));?>" id="jf" class="button-htzh-dx col-xs-12" />立即发送</a>
                </div>
            </div>
        </div>
    </div><?php endif; ?>
    <!--  
	<div class="container-fluid button">
    	<div class="row-fluid button-ds">
	       	<div class="col-xs-6 padding-left-right-10" >
	       		<div class="button-ckzi1" >
	         		<input name="#" type="button" value="查看凭证" id="pz" class="button-htzh-dx" />
	       		</div>
        	</div>
      		<div class="col-xs-6 padding-left-right-10" >
        		<div class="button-ckzi2">
            		<input name="#" type="button" value="立即缴费" id="jf" class="button-htzh-dx" />
        		</div>
			</div>
		</div>-->
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