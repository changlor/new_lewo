<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>用户中心</title>
  <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width">
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">    
	<meta http-equiv="keywords" content="keyword1,keyword2,keyword3">
	<meta http-equiv="description" content="This is my page">
	<link href="/Public/css/normalize.css" rel="stylesheet" type="text/css">
	<link href="/Public/css/bootstrap.min.css" rel="stylesheet">
	<link href="/Public/css/bootstrap-theme.min.css" type="text/css">
	<link href="/Public/css/common.css" rel="stylesheet" type="text/css">
	<link href="/Public/css/grzx.css" rel="stylesheet" type="text/css">
	<script src="/Public/js/jquery-1.9.1.js" type="text/javascript"></script>
	<script src="/Public/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/Public/js/iscroll.js"></script>
	<script type="text/javascript">
		var myScroll;
		function loaded () {
			myScroll = new IScroll('#wrapper', { mouseWheel: true,keyBindings:true,click:true });
		}
		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);		
	</script>
	<style>
	#wrapper {
		position: absolute;
		top: 44px;
		bottom: 50px;
		left: 0;
		width: 100%;
		overflow: hidden;
		background-color:#f8f8f8;
	}
	.img-responsive {
		display: inline-block;
		height: auto;
		max-width: 100%;
	}
	.col-xs-12 {
		padding-left: 0;
		padding-right: 0;
	}
	.col-xs-6{
		padding-left: 10px;
		padding-right: 10px;
		}
	.container-fluid {
		padding-left: 0;
		padding-right: 0;
	}
	</style>
</head>

<body onload="loaded();">
<div id="fwxx">
	<div class="container-fluid header">
    	<div class="row-fluid">
        	<div class="col-xs-4 header-icon1"></div>
            <div class="col-xs-4 header-icon2"></div>
            <div class="col-xs-4 header-icon3"><a href=""><div class="menu-db-box"><img src="/Public/images/icon-db-menu.png" class="icon-db-menu"></div></a></div>
        </div>
    </div>
    <div id="wrapper">
      <div>
        <div class="container-fluid">
          <div class="row-fluid">
            <div class="col-xs-12">
              <div class="row-fluid">
                <div class="col-xs-12">
                  <div class="img-icon1-dx">
                    <div class="row-fluid">
                      <div class="col-xs-12">
                        <div class="img-icon1-tx"><a href=""><img src="/Public/images/user.jpg" style="margin-top:20px;width:66px;height:66px;border-radius:66px;border:2px solid #fff;" class="img-responsive"></a></div>
                      </div>
                    </div>
                    <div class="row-fluid">
                      <div class="col-xs-12">
                        <div class="banner-wz"><span class="banner-wz-tz1"><?php echo ($account_info['realname']); ?></span><span class="banner-wz-tz2"> </span> <br>
                          <?php if(!empty($room_info)): ?><span class="banner-wz-tz3"><?php echo ($house_info["area_name"]); ?>&nbsp;&nbsp;<?php echo ($house_info["building"]); ?>栋&nbsp;&nbsp;<?php echo ($house_info["floor"]); ?>楼&nbsp;&nbsp;<?php echo ($house_info["door_no"]); ?>号
                            &nbsp;&nbsp;<?php echo ($room_info["room_code"]); ?>
                            </span> 
                            <?php else: ?>
                            <span class="banner-wz-tz3">暂未入住</span><?php endif; ?>
                         </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row-fluid">
              <div class="col-xs-12">
                <div class="border-bot-xt1"></div>
              </div>
            </div>
            <div>

            <!-- 未支付账单开 -->
            <?php if(is_array($not_pay_bill)): $i = 0; $__LIST__ = $not_pay_bill;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="row-fluid">
              <div class="col-xs-12 background-f8f8f8">
                <div class="row-fluid">
                  <div class="col-xs-12">
                    <ul>
                      <li class="core-ht-box margin-top10">账单信息:<?php echo ($vo["input_year"]); ?>年<?php echo ($vo["input_month"]); echo ($vo["bill_type_name"]); ?></li>
                      <li class="core-ht-box">应缴金额:<span class="core-ht-cor">￥<?php echo ($vo["price"]); ?></span></li>
                      <?php if($vo['bill_des'] != ''): ?><li class="core-ht-box">账单描述:<span class="core-ht-cor">￥<?php echo ($vo["bill_des"]); ?></span></li><?php endif; ?>
                      <li class="core-ht-box">缴费时间:<?php echo ($vo["should_date"]); ?>
                        <?php if($vo['days'] > 0): ?><span class='core-ht-cor'>还有<?php echo ($vo["days"]); ?>天</span>
                          <?php else: ?>
                          <span class='core-ht-cor'>已拖欠<?php echo ($vo["days"]); ?>天</span><?php endif; ?>
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="row-fluid">
                <?php switch($vo["bill_type"]): case "2": ?><div class="col-xs-6">
                      <div class="button-ckzi1" onclick="window.location.href='<?php echo U('Home/Tenant/show_contract_bill',array('pro_id'=>$vo['pro_id']));?>'">查看详情</div>
                    </div><?php break;?>
                  <?php case "3": ?><div class="col-xs-6">
                      <div class="button-ckzi1" onclick="window.location.href='<?php echo U('Home/Tenant/detail_fee',array('pro_id'=>$vo['pro_id']));?>'">查看详情</div>
                    </div><?php break; endswitch;?>
                  <div class="col-xs-6">
                    <div class="button-ckzi2" onclick="window.location.href='<?php echo U('Home/Alipay/pay',array('WIDout_trade_no'=>$vo['pro_id'],'WIDsubject'=>$vo['bill_info'],'WIDtotal_fee'=>$vo['price']));?>'">支付宝支付</div>
                  </div>
                </div>
                <div class="row-fluid">
                  <div class="col-xs-12">
                    <div class="border-bot-xt2"></div>
                  </div>
                </div>
              </div>
            </div><?php endforeach; endif; else: echo "" ;endif; ?>
  
            <!-- 定金账单关 -->
            <?php if(!empty($contract_info)): ?><div class="row-fluid">
              <div class="col-xs-12 background-f8f8f8">
                <div class="row-fluid">
                  <div class="col-xs-12">
                    <ul>
                      <li class="core-ht-box margin-top10">类型:合同</li>
                      <li class="core-ht-box">应缴金额:<span class="core-ht-cor">￥<?php echo ($contract_info["price"]); ?></span></li>
                    </ul>
                  </div>
                </div>
                <div class="row-fluid">
                  <div class="col-xs-6">
                    <div class="button-ckzi1" onclick="window.location.href='<?php echo U('Home/Tenant/show_contract_bill',array('pro_id'=>$contract_info['pro_id']));?>'">查看详情</div>
                  </div>
                  <div class="col-xs-6">
                    <div class="button-ckzi2" onclick="window.location.href='<?php echo U('Home/AlipayContract/pay',array('WIDout_trade_no'=>$contract_info['pro_id'],'WIDsubject'=>$contract_info['bill_info'],'WIDtotal_fee'=>$contract_info['price']));?>'">支付宝支付</div>
                  </div>
                </div>
                <div class="row-fluid">
                  <div class="col-xs-12">
                    <div class="border-bot-xt2"></div>
                  </div>
                </div>
              </div>
            </div><?php endif; ?>
            <?php if(!empty($back_bill)): if(is_array($back_bill)): $i = 0; $__LIST__ = $back_bill;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><!--我的退款-->
            <div>
              <div class="row-fluid">
                <div class="col-xs-12">
                  <div class="border-bot-xt-wdfj-icon">
                  <img src="/Public/images/icon_wdfj2.png" class="icon_wdfj-dx"><span class="icon_wdfj-wz">我的退款</span>
                    <span class="icon_wdfj-wz-xq">
                      <?php switch($vo["is_finish"]): case "1": ?><span style="color:green">已打款</span><?php break;?>
                        <?php case "0": ?><span style="color:red">未打款</span><?php break; endswitch;?>
                    </span>
                    <span class="icon_wdfj-wz-xq">
                    退款金额:<?php echo ($vo["money"]); ?>
                    </span>
                     <span class="icon_wdfj-wz-xq">
                    退款帐号:<?php echo ($vo["pay_account"]); ?>
                    </span>
                     <span class="icon_wdfj-wz-xq">
                     退款方式:
                      <?php switch($vo["pay_type"]): case "1": ?>支付宝<?php break;?>
                        <?php case "2": ?>微信<?php break; endswitch;?>
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <!--我的退款尾--><?php endforeach; endif; else: echo "" ;endif; endif; ?>
            <?php if(!empty($confirm_back_bill)): ?><div class="row-fluid">
              <div class="col-xs-12 background-f8f8f8">
                <div class="row-fluid">
                  <div class="col-xs-12">
                    <ul>
                      <li class="core-ht-box margin-top10">类型:确认<?php echo ($schedule_type_name); ?>账单</li>
                      <li class="core-ht-box">抵扣后押金:<span class="core-ht-cor">￥<?php echo ($confirm_back_bill["money"]); ?></span></li>
                    </ul>
                  </div>
                </div>
                <div class="row-fluid">
                  <div class="col-xs-6">
                    <div class="button-ckzi1" onclick="window.location.href='<?php echo U('Home/Tenant/show_back_bill');?>'">查看详情</div>
                  </div>
                  <div class="col-xs-6">
                    <div class="button-ckzi2" onclick=" if(!confirm('是否确认账单?')) { return false; } else{ window.location.href='<?php echo U('Home/Tenant/confirm_bill',array('id'=>$confirm_back_bill['id']));?>'}">确认账单</div>
                  </div>
                </div>
                <div class="row-fluid">
                  <div class="col-xs-12">
                    <div class="border-bot-xt2"></div>
                  </div>
                </div>
              </div>
            </div>
            
            <?php else: ?>

            <!-- 未支付账单开 -->
            <?php if(empty($back_bill)): if(is_array($notpaylist)): $i = 0; $__LIST__ = $notpaylist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="row-fluid">
              <div class="col-xs-12 background-f8f8f8">
                <div class="row-fluid">
                  <div class="col-xs-12">
                    <ul>
                      <li class="core-ht-box margin-top10">账单信息:<?php echo ($vo["bill_info"]); ?></li>
                      <li class="core-ht-box">应缴金额:<span class="core-ht-cor">￥<?php echo ($vo["price"]); ?></span></li>
                      <li class="core-ht-box">缴费时间:<?php echo ($vo["should_date"]); ?>
                        <?php if($vo['days'] > 0): ?><span class='core-ht-cor'>还有<?php echo ($vo["days"]); ?>天</span>
                          <?php else: ?>
                          <span class='core-ht-cor'>已拖欠<?php echo ($vo["days"]); ?>天</span><?php endif; ?>
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="row-fluid">
                  <div class="col-xs-6">
                    <div class="button-ckzi1" onclick="window.location.href='<?php echo U('Home/Tenant/detail_fee',array('pro_id'=>$vo['pro_id']));?>'">查看详情</div>
                  </div>
                  <div class="col-xs-6">
                    <div class="button-ckzi2" onclick="window.location.href='<?php echo U('Home/Alipay/pay',array('WIDout_trade_no'=>$vo['pro_id'],'WIDsubject'=>$vo['bill_info'],'WIDtotal_fee'=>$vo['price']));?>'">支付宝支付</div>
                  </div>
                </div>
                <div class="row-fluid">
                  <div class="col-xs-12">
                    <div class="border-bot-xt2"></div>
                  </div>
                </div>
              </div>
            </div><?php endforeach; endif; else: echo "" ;endif; endif; ?>
            <!-- 未支付账单关 --><?php endif; ?>
            
            <div class="row-fluid">
              <div class="col-xs-12 icon-top-h20">
                <div class="row-fluid">
                    <div class="col-xs-4" onclick="location.href='<?php echo U('Home/Tenant/myhouse');?>'"><img src="/Public/images/gr-wdfj.png" class="img-responsive img-icon2-dx"> <br>
                    <span class="nav-inner-zt">我的房间</span> </div>
                    <div class="col-xs-4" onclick="location.href='<?php echo U('Home/Tenant/feelist');?>'"><img src="/Public/images/gr-fycx.png" class="img-responsive img-icon2-dx"> <br>
                    <span class="nav-inner-zt">费用查询</span> </div>
                    <div class="col-xs-4" onclick="location.href='<?php echo U('Home/Tenant/mysteward');?>'"><img src="/Public/images/gr-wdgj.png" class="img-responsive img-icon2-dx"> <br>
                    <span class="nav-inner-zt">我的管家</span> </div>
                </div>
                <div class="row-fluid">
                  <!-- <div class="col-xs-4" onclick=""><img src="images/gr-wxbx.png" class="img-responsive img-icon2-dx"> <br>
                    <span class="nav-inner-zt">维修报修</span>
                  </div> -->
                  <div class="col-xs-4" onclick="location.href='<?php echo U('Home/Tenant/myinfo');?>'"><img src="/Public/images/gr-grzl.png" class="img-responsive img-icon2-dx"> <br>
                    <span class="nav-inner-zt">个人资料</span>
                  </div>
                  <div class="col-xs-4"> <br> </div>
                </div>
                <div class="row-fluid">
                  <div class="col-xs-12">
                    <div class="border-bot-box-h25"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="button">
    <div class="button_ds">
      <div class="container-fluid">
        <div class="row-fluid">
          <a href="">
            <div class="col-xs-3"> <img src="/Public/images/icon_sy.png" class="img-responsive img-icon3-dx"> <br>
              <span class="nav-bootom-zt">首页</span>
            </div>
          </a>
          <a href="https://shequ.yunzhijia.com/thirdapp/forum/network/5679fdf8e4b07a6f62be571a?pcode=2ygkht&pinvite=574bccf6e4b01e916548976f">
            <div class="col-xs-3"> <img src="/Public/images/icon_sy.png" class="img-responsive img-icon3-dx"> <br>
              <span class="nav-bootom-zt">良邻</span>
            </div>
          </a>
           <a href="<?php echo U('Home/Mapsearch/index');?>">
            <div class="col-xs-3"> <img src="/Public/images/icon_zf.png" class="img-responsive img-icon3-dx"> <br>
              <span class="nav-bootom-zt">找房</span>
            </div>
          </a>
          <a href="<?php echo U('Home/Tenant/index');?>">
            <div class="col-xs-3"> <img src="/Public/images/icon_wo.png" class="img-responsive img-icon3-dx"> <br>
              <span class="nav-bootom-zt" style="color:#68bb9b;">我</span>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>



</body></html>