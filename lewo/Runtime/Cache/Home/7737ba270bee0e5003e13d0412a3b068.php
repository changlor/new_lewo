<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">    
	<meta http-equiv="keywords" content="keyword1,keyword2,keyword3">
	<meta http-equiv="description" content="This is my page">
	<title>我的房间</title>
  <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width">
  <meta name="format-detection" content="email=no,address=no,telephone=no">
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
  	myScroll = new IScroll('#wrapper', { mouseWheel: true, click: true  });
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
  	background-color:#f4f4f4;
  }
  .img-responsive {
  	display: inline-block;
  	height: auto;
  	max-width: 100%;
  }
  .table>tbody>tr>td{border: none;}
  </style>
</head>

<body onload="loaded()">
<div id="fwxx">
	<div class="container-fluid header">
    	<div class="row-fluid">
        	<div class="col-xs-4 header-icon1"><a href="<?php echo U('Home/Tenant/index');?>"><div class="retun-db-box"><img src="/Public/images/icon-db-retun.png" class="icon-db-retun"></div></a></div>
        </div>
    </div>
    <div id="wrapper">
        <div class="container-fluid">
          <div class="row-fluid">
            <div class="col-xs-12">
              <div class="row-fluid">
                <div class="col-xs-12">
                  <div class="img-icon1-dx">
                    <div class="row-fluid">
                      <div class="col-xs-12">
                        <div class="img-icon1-tx"><img src="/Public/images/user.jpg" style="margin-top:20px; width:66px; height:66px; border-radius:66px; border:2px solid #fff;" class="img-responsive"></div>
                      </div>
                    </div>
                    <div class="row-fluid">
                      <div class="col-xs-12">
                        <div class="banner-wz"><span class="banner-wz-tz1"><?php echo ($account_info['realname']); ?></span><br>
                        <?php if(!empty($room_info)): ?><span class="banner-wz-tz3"><?php echo ($house_info["area_name"]); ?>&nbsp;&nbsp;<?php echo ($house_info["building"]); ?>栋&nbsp;&nbsp;<?php echo ($house_info["floor"]); ?>楼&nbsp;&nbsp;<?php echo ($house_info["door_no"]); ?>号
                            &nbsp;&nbsp;<?php echo ($room_info["room_code"]); ?></span> 
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
                <div class="border-bot-xt-wdfj"></div>
              </div>
            </div>
            <!--我的房间头-->
            <div>
              <div class="row-fluid">
                <div class="col-xs-12">
                  <div class="border-bot-xt-wdfj-icon"><img src="/Public/images/icon_wdfj1.png" class="icon_wdfj-dx"><span class="icon_wdfj-wz">我的房间</span>
                  </div>
                </div>
              </div>
              <?php if(!empty($contract_info)): ?><div class="row-fluid">
                <div class="col-xs-12">
                  <div class="border-bot-xt-wdfj-zp">
                  	<ul class="wdfj-xix-box">
                      <li class="wdfj-xix-box-zp"><img src="/Uploads/<?php echo ($room_info['room_head_images']); ?>" class="icon_wdfj-zp"></li>
                      <li class="wdfj-xix-box-h1"><?php echo ($house_info["area_name"]); ?>&nbsp;&nbsp;<?php echo ($house_info["building"]); ?>栋&nbsp;&nbsp;<?php echo ($house_info["floor"]); ?>楼&nbsp;&nbsp;<?php echo ($house_info["door_no"]); ?>号</span></li>
                      <li class="wdfj-xix-box-h2">
                      编号：<span class="wdfj-xix-box-h2-ys"><?php echo ($room_info['room_code']); ?></span>
                      &nbsp;
                      <?php if(!empty($room_info["bed_code"])): ?>床位:<span class="wdfj-xix-box-h2-ys"><?php echo ($room_info['bed_code']); ?></span><?php endif; ?>
                      </li>
                      <li class="wdfj-xix-box-h3">
                      租金：<span class="wdfj-xix-box-h3-ys"><?php echo ($contract_info['rent']); ?>/月</span>
                      &nbsp;
                      服务费：<span class="wdfj-xix-box-h3-ys"><?php echo ($contract_info['fee']); ?>/月</span></li>
                    </ul>
                  </div>
                </div>
              </div><?php endif; ?>
              <div class="row-fluid">
                <div class="col-xs-12">
                  <div class="border-bot-xt-wdfj"></div>
                </div>
              </div>
            </div>
            <!--我的房间尾-->
            <!--我的合同头-->
            <div>
              <div class="row-fluid">
                <div class="col-xs-12">
                  <div class="border-bot-xt-wdfj-icon">
                  <a href="<?php echo U('Home/Tenant/my_contract');?>"><img src="/Public/images/icon_wdfj2.png" class="icon_wdfj-dx"><span class="icon_wdfj-wz">我的合同</span>
                  <?php if(!empty($contract_info)): ?><span class="icon_wdfj-wz-xq">合同时间:<?php echo ($contract_info['start_time']); ?>至<?php echo ($contract_info['end_time']); ?>
                    </span><?php endif; ?>
                  </a>
                  </div>
                </div>
              </div>
            </div>
            <!--我的合同尾--> 
            <div class="row-fluid">
              <div class="col-xs-12">
                <div class="border-bot-xt-wdfj"></div>
              </div>
            </div> 
            <?php if(is_array($back_bill)): $i = 0; $__LIST__ = $back_bill;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><!--我的退款-->
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
            <!--我的退款尾--> 
            <div class="row-fluid">
              <div class="col-xs-12">
                <div class="border-bot-xt-wdfj"></div>
              </div>
            </div><?php endforeach; endif; else: echo "" ;endif; ?>
            <!-- 退房流程显示开 -->
            <?php if(!empty($TFinfo)): ?><div class="col-xs-12">
              <div class="border-bot-xt-wdfj"></div>
            </div>
            <div class="row-fluid">
              <div class="col-xs-12">
              <!-- 退租开 -->
                <table class="table table-th-td">
                  <tbody>
                    <tr height="10"><td colspan="2"></td></tr>
                    
                    <!--退房流程开-->
                    <tr height="40">
                      <td class="table-tr-y-line table-td-color1">
                        <img <?php if($status == 1): ?>src="/Public/images/icon_wdfjo1.png"<?php else: ?>src="/Public/images/icon_wdfjo2.png"<?php endif; ?> class="icon_wdfjo1-wz">
                      </td>
                      <td <?php if($TFstatus == 1): ?>class="table-td-color1"<?php else: ?>class="table-td-color2"<?php endif; ?> align="left">
                        <p class="banner-wz-tz3">申请退房</p>
                        <p class="banner-wz-tz3"><?php echo ($TFinfo['0']['create_time']); ?></p>
                      </td>
                    </tr>
                    <tr height="40">
                      <td class="table-tr-y-line table-td-color1">
                        <img <?php if($TFstatus == 2): ?>src="/Public/images/icon_wdfjo1.png"<?php else: ?>src="/Public/images/icon_wdfjo2.png"<?php endif; ?> class="icon_wdfjo1-wz">
                      </td>
                      <td <?php if($TFstatus == 2): ?>class="table-td-color1"<?php else: ?>class="table-td-color2"<?php endif; ?> align="left">
                        <p class="banner-wz-tz3">等待管家验房</p>
                        <p class="banner-wz-tz3"><?php echo ($TFinfo['1']['create_time']); ?></p>
                      </td>
                    </tr>
                    <tr height="40">
                      <td class="table-tr-y-line table-td-color1">
                      <img <?php if($TFstatus == 3): ?>src="/Public/images/icon_wdfjo1.png"<?php else: ?>src="/Public/images/icon_wdfjo2.png"<?php endif; ?> class="icon_wdfjo1-wz">
                      </td>
                      <td <?php if($TFstatus == 3): ?>class="table-td-color1"<?php else: ?>class="table-td-color2"<?php endif; ?> align="left">
                        <p class="banner-wz-tz3">财务完成费用审核</p>
                        <p class="banner-wz-tz3"><?php echo ($TFinfo['2']['create_time']); ?></p>
                      </td>
                    </tr>
                    <tr height="40">
                      <td class="table-tr-y-line table-td-color1">
                        <img <?php if($TFstatus == 4): ?>src="/Public/images/icon_wdfjo1.png"<?php else: ?>src="/Public/images/icon_wdfjo2.png"<?php endif; ?> class="icon_wdfjo1-wz">
                      </td>
                      <td <?php if($TFstatus == 4): ?>class="table-td-color1"<?php else: ?>class="table-td-color2"<?php endif; ?> align="left">
                        <p class="banner-wz-tz3">财务发送账单确认</p>
                        <p class="banner-wz-tz3"><?php echo ($TFinfo['3']['create_time']); ?></p>
                      </td>
                    </tr>
                    <tr height="40">
                      <td class="table-tr-y-line table-td-color1">
                        <img <?php if($TFstatus == 5): ?>src="/Public/images/icon_wdfjo1.png"<?php else: ?>src="/Public/images/icon_wdfjo2.png"<?php endif; ?> class="icon_wdfjo1-wz">
                      </td>
                      <td <?php if($TFstatus == 5): ?>class="table-td-color1"<?php else: ?>class="table-td-color2"<?php endif; ?> align="left">
                        <p class="banner-wz-tz3">租客确认账单</p>
                        <p class="banner-wz-tz3"><?php echo ($TFinfo['4']['create_time']); ?></p>
                      </td>
                    </tr>
                    <tr height="40">
                      <td class="table-tr-y-line table-td-color1">
                        <img <?php if($TFstatus == 6): ?>src="/Public/images/icon_wdfjo1.png"<?php else: ?>src="/Public/images/icon_wdfjo2.png"<?php endif; ?> class="icon_wdfjo1-wz">
                      </td>
                      <td <?php if($TFstatus == 6): ?>class="table-td-color1"<?php else: ?>class="table-td-color2"<?php endif; ?> align="left">
                        <p class="banner-wz-tz3">等待财务确认打款账单</p>
                        <p class="banner-wz-tz3"><?php echo ($TFinfo['5']['create_time']); ?></p>
                      </td>
                    </tr>
                    <!--退房流程关-->
                  </tbody>
                </table>
              <!-- 退租关 -->
              </div>
            </div>
            <div class="row-fluid">
              <div class="col-xs-12"><div style="height:45px;"></div></div>
            </div><?php endif; ?>
            <!-- 退房流程显示关 -->
            <!-- 换房流程显示开 -->
            <?php if(!empty($HFinfo)): ?><div class="col-xs-12">
              <div class="border-bot-xt-wdfj"></div>
            </div>
            <div class="row-fluid">
              <div class="col-xs-12">
              <!-- 换租开 -->
                <table class="table table-th-td">
                  <tbody>
                    <tr height="10"><td colspan="2"></td></tr>
                    
                    <!--退房流程开-->
                    <tr height="40">
                      <td class="table-tr-y-line table-td-color1">
                        <img <?php if($HFstatus == 1): ?>src="/Public/images/icon_wdfjo1.png"<?php else: ?>src="/Public/images/icon_wdfjo2.png"<?php endif; ?> class="icon_wdfjo1-wz">
                      </td>
                      <td <?php if($HFstatus == 1): ?>class="table-td-color1"<?php else: ?>class="table-td-color2"<?php endif; ?> align="left">
                        <p class="banner-wz-tz3">申请换房</p>
                        <p class="banner-wz-tz3"><?php echo ($HFinfo['0']['create_time']); ?></p>
                        
                      </td>
                    </tr>
                    <tr height="40">
                      <td class="table-tr-y-line table-td-color1">
                        <img <?php if($HFstatus == 2): ?>src="/Public/images/icon_wdfjo1.png"<?php else: ?>src="/Public/images/icon_wdfjo2.png"<?php endif; ?> class="icon_wdfjo1-wz">
                      </td>
                      <td <?php if($HFstatus == 2): ?>class="table-td-color1"<?php else: ?>class="table-td-color2"<?php endif; ?> align="left">
                        <p class="banner-wz-tz3">等待管家验房</p>
                        <p class="banner-wz-tz3"><?php echo ($HFinfo['1']['create_time']); ?></p>
                      </td>
                    </tr>
                    <tr height="40">
                      <td class="table-tr-y-line table-td-color1">
                      <img <?php if($HFstatus == 3): ?>src="/Public/images/icon_wdfjo1.png"<?php else: ?>src="/Public/images/icon_wdfjo2.png"<?php endif; ?> class="icon_wdfjo1-wz">
                      </td>
                      <td <?php if($HFstatus == 3): ?>class="table-td-color1"<?php else: ?>class="table-td-color2"<?php endif; ?> align="left">
                        <p class="banner-wz-tz3">财务完成费用审核</p>
                        <p class="banner-wz-tz3"><?php echo ($HFinfo['2']['create_time']); ?></p>
                      </td>
                    </tr>
                    <tr height="40">
                      <td class="table-tr-y-line table-td-color1">
                        <img <?php if($HFstatus == 4): ?>src="/Public/images/icon_wdfjo1.png"<?php else: ?>src="/Public/images/icon_wdfjo2.png"<?php endif; ?> class="icon_wdfjo1-wz">
                      </td>
                      <td <?php if($HFstatus == 4): ?>class="table-td-color1"<?php else: ?>class="table-td-color2"<?php endif; ?> align="left">
                        <p class="banner-wz-tz3">财务发送账单确认</p>
                        <p class="banner-wz-tz3"><?php echo ($HFinfo['3']['create_time']); ?></p>
                      </td>
                    </tr>

                    <tr height="40">
                      <td class="table-tr-y-line table-td-color1">
                        <img <?php if($HFstatus == 5): ?>src="/Public/images/icon_wdfjo1.png"<?php else: ?>src="/Public/images/icon_wdfjo2.png"<?php endif; ?> class="icon_wdfjo1-wz">
                      </td>
                      <td <?php if($HFstatus == 5): ?>class="table-td-color1"<?php else: ?>class="table-td-color2"<?php endif; ?> align="left">
                        <p class="banner-wz-tz3">租客确认账单</p>
                        <p class="banner-wz-tz3"><?php echo ($HFinfo['4']['create_time']); ?></p>
                      </td>
                    </tr>
                    <tr height="40">
                      <td class="table-tr-y-line table-td-color1">
                        <img <?php if($HFstatus == 6): ?>src="/Public/images/icon_wdfjo1.png"<?php else: ?>src="/Public/images/icon_wdfjo2.png"<?php endif; ?> class="icon_wdfjo1-wz">
                      </td>
                      <td <?php if($HFstatus == 6): ?>class="table-td-color1"<?php else: ?>class="table-td-color2"<?php endif; ?> align="left">
                        <p class="banner-wz-tz3">等待财务完成打款(将剩余押金放到个人余额中)</p>
                        <p class="banner-wz-tz3"><?php echo ($HFinfo['5']['create_time']); ?></p>
                      </td>
                    </tr>
                    <!--换房流程关-->
                  </tbody>
                </table>
              <!-- 换房关 -->
              </div>
            </div>
            <div class="row-fluid">
              <div class="col-xs-12"><div style="height:45px;"></div></div>
            </div><?php endif; ?>
            <!-- 换房流程显示关 -->
          </div>
        </div>
    </div>
  </div>
  <div class="container-fluid button">
    <div class="row-fluid button-ds">
        <div class="col-xs-4 padding-left-right-10">
        <div class="button-ckzi1">
        <input onclick="window.location='<?php echo U('Home/Tenant/checkout',array('room_id'=>$room_info['id'],'schedule_type'=>C('schedule_type_tf')));?>'" type="button" value="退房" class="button-htzh-dx">
    </div>
    </div>
    <div class="col-xs-4 padding-left-right-10">
        <div class="button-ckzi1">
        <input onclick="window.location='<?php echo U('Home/Tenant/checkout',array('room_id'=>$room_info['id'],'schedule_type'=>C('schedule_type_hf')));?>'" type="submit" value="换房" class="button-htzh-dx">
        </div>
    </div>
    <div class="col-xs-4 padding-left-right-10">
        <div class="button-ckzi1">
        <input onclick="window.location='<?php echo U('Home/Tenant/checkout',array('room_id'=>$room_info['id'],'schedule_type'=>C('schedule_type_zf')));?>'" name="#" type="submit" value="转房" class="button-htzh-dx">
        </div>
    </div>
  </div>
</div>
</body>
</html>