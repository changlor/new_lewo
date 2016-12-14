<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">    
	<meta http-equiv="keywords" content="keyword1,keyword2,keyword3">
	<meta http-equiv="description" content="This is my page">
	<title>房屋管理</title>
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width"><meta name="format-detection" content="email=no,address=no,telephone=no">
    <link href="/Public/css/normalize.css" rel="stylesheet" type="text/css">
    <link href="/Public/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Public/css/common.css" rel="stylesheet" type="text/css">
    <link href="/Public/css/bill.css" rel="stylesheet" type="text/css">
    <script src="/Public/js/jquery-1.9.1.js" type="text/javascript"></script>
    <script src="/Public/js/bootstrap.min.js"></script>
    <script src="/Public/js/common.js"></script>
    <script type="text/javascript" src="/Public/js/iscroll.js"></script>
    <script type="text/javascript">

    var myScroll;

    function loaded () {
        myScroll = new IScroll('#wrapper', { mouseWheel: true, click: true, tap: true });
    }

    document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);

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
    table ~ td {
        word-wrap:break-word;
    }
    </style>

</head>

<body onload="loaded()">
<div id="fwxx">
	<div class="container-fluid header">
    	<div class="row-fluid">
        	<div class="col-xs-2">
                <a href="<?php echo U('Home/Steward/index');?>"><img src="/Public/images/icon-db-retun.png" class="icon-db-retun"></a>
            </div>
            <div class="col-xs-7">
                <div class="ssearch">
                  <form action="">
                    <input class="iSearch" type="search" name="search" value="<?php echo ($search); ?>" placeholder="<?=empty($search_history) ? '请输入' : $search_history?>">
                    <input class="isubmit" type="submit" value="搜索">
                  </form>
                </div>
            </div>
            <div class="col-xs-2">
                <?php if(!isset($type)): ?><a href="<?php echo U('Home/Steward/allhouses');?>" class="btn btn-success" style="margin-top:5px; margin-left: 4px;">所有房源</a>
                <?php else: ?>
                    <?php if($type != 'empty'): ?><a href="<?php echo U('Home/Steward/allhouses',array('select'=>'empty'));?>" class="btn btn-success" style="margin-top:5px; margin-left: 4px;">空置房源</a>
                    <?php else: ?>
                        <a href="<?php echo U('Home/Steward/allhouses',array('select'=>'is_let_out'));?>" class="btn btn-success" style="margin-top:5px; margin-left: 4px;">已租房源</a><?php endif; endif; ?>
            </div>
        </div>
    </div>
    <div id="wrapper">
        <div>
			<div class="container-fluid" id="XS" style="height: auto; padding-bottom: 500px;">
                <div class="row-fluid">
                    <div class="col-xs-12">
                    	<div class="row-fluid">
                            <div class="col-xs-12"><div class="innre-bill-ls-dsh"></div></div>
                        </div>
                        <div class="row-fluid">
                            <table class="table table-bordered table-striped" style="table-layout:fixed ;">
                                <thead>
                                    <tr>
                                        <th width="20%">类型</th>
                                        <th width="20%">姓名</th>
                                        <th width="20%">小区</th>
                                        <th width="20%">金额</th>
                                        <th width="20%">状态</th>
                                    </tr>
                                </thead>

                                <?php if(is_array($houses)): $i = 0; $__LIST__ = $houses;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tbody class="first-tbody">
                                        <tr>
                                            <td><?php echo ($vo["bill_type"]); ?></td>
                                            <td><?php echo ($vo["realname"]); ?></td>
                                            <td><?php echo ($vo["area_name"]); ?><br /><?php echo ($vo["building"]); ?>-<?php echo ($vo["floor"]); ?>-<?php echo ($vo["door_no"]); ?></td>
                                            <td>
                                                <?php echo ($vo["price"]); ?>
                                            </td>
                                            <td><?=$vo.pay_status == 0 ? '<label class="btn btn-success btn-mini">已付</label>' : '<label class="btn btn-danger btn-mini">未付</label>';?></td>
                                        </tr>
                                        <tr class="drop-arrow">
                                            <td colspan="5" align="center" style="padding: 0px;font-size: 8px;transform: rotate(180deg);">▲</td>
                                        </tr>
                                    </tbody>
                                    <tbody class="second-tbody" >
                                        <?php if($vo["bill_type"] == '合同'): ?><tr class="info">
                                                <td align="center">押金</td>
                                                <td align="center"><?php echo ($vo["deposit"]); ?></td>
                                                <td align="center">房租</td>
                                                <td align="center"><?php echo ($vo["rent"]); ?></td>
                                            </tr>
                                            <tr class="info">
                                                <td align="center">服务费</td>
                                                <td align="center"><?php echo ($vo["fee"]); ?></td>
                                                <td align="center">付费方式</td>
                                                <td align="center"><?php echo ($vo["rent_type"]); ?></td>
                                            </tr>
                                            <tr class="info">
                                                <td colspan="5" align="center">
                                                    <a class="btn btn-success btn-mini">管家代收</a>
                                                    <a class="btn btn-success btn-mini">催款</a>
                                                    <label onclick="window.location.href = '<?php echo U('Home/Steward/tenant_contract', ['pro_id' => $vo['pro_id']]);?>'" class="btn btn-warning btn-mini">详情</label>
                                                </td>
                                            </tr>
                                        <?php elseif($vo["bill_type"] == '日常'): ?>
                                            <tr class="info">
                                                <td align="center" colspan="5">水电气: <?php echo ($vo["total_daily_room_fee"]); ?> | 个人电费: <?php echo ($vo["room_energy_fee"]); ?></td>
                                            </tr>
                                            <tr class="info">
                                                <td colspan="5" align="center">维修费: <?php echo ($vo["wx_fee"]); ?></td>
                                            </tr>
                                            <tr class="info">
                                                <td colspan="5" align="center">
                                                    <a class="btn btn-success btn-mini">管家代收</a>
                                                    <a class="btn btn-danger btn-mini">催款</a>
                                                    <label onclick="window.location.href = '<?php echo U('Home/Steward/total_daily_room_fee', ['pro_id' => $vo['pro_id'], 'account_id' => $vo['account_id']]);?>'" class="btn btn-warning btn-mini">详情</label>
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <tr class="info">
                                                <td align="center">订单描述</td>
                                                <td colspan="3" align="center"><?php echo ($vo["bill_des"]); ?></td>
                                            </tr>
                                            <tr class="info">
                                                <td colspan="4" align="center">
                                                    <a class="btn btn-success btn-mini">管家代收</a>
                                                </td>
                                                <td colspan="1" align="center">
                                                    <a class="btn btn-success btn-mini">催款</a>
                                                </td>
                                            </tr><?php endif; ?>
                                    </tbody><?php endforeach; endif; else: echo "" ;endif; ?>

                            </table>
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