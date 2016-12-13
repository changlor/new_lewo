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
        myScroll = new IScroll('#wrapper', { mouseWheel: true, click: true  });
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
                    <input class="iSearch" type="search" name="search" value="<?php echo ($search); ?>" placeholder="请输入">
                    <input class="isubmit" type="submit" value="搜索">
                  </form>
                </div>
            </div>
            <div class="col-xs-2">
                <a href="<?php echo U('Home/Steward/allhouses');?>" class="btn btn-success" style="margin-top:5px; margin-left: 4px;">所有房源</a>
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
                                        <th width="25%">房屋编号</th>
                                        <th width="25%">小区</th>
                                        <th width="27%">栋-层-房号</th>
                                        <th width="23%">状态</th>
                                    </tr>
                                </thead>

                                <?php if(is_array($houses)): $i = 0; $__LIST__ = $houses;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tbody class="first-tbody">
                                        <tr>
                                            <td><?php echo ($vo["house_code"]); ?></td>
                                            <td><?php echo ($vo["area_name"]); ?></td>
                                            <td><?php echo ($vo["building"]); ?>-<?php echo ($vo["floor"]); ?>-<?php echo ($vo["door_no"]); ?></td>
                                            <td>
                                                <?php if($vo["is_checkin"] == true): ?><label class="btn btn-success btn-mini">有租
                                                <?php echo ($vo["yz_count"]); ?>/<?php echo ($vo["count"]); ?>
                                                </label>
                                                <?php else: ?>
                                                <label class="btn btn-danger btn-mini">未租</label><?php endif; ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody class="second-tbody" >
                                        <?php if(is_array($vo['room_list'])): $i = 0; $__LIST__ = $vo['room_list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr  class="info">
                                            <?php if($v['room_type'] == 1): ?><td>房间<?php echo ($v["room_sort"]); ?></td>
                                            <?php else: ?>
                                                <td>床位<?php echo ($v["bed_code"]); ?></td><?php endif; ?>
                                            <td>￥<?php echo ($v["rent"]); ?></td>
                                                <td>
                                                    <?php if($v['account_id'] != 0): ?><a href="<?php echo U('Home/Steward/tenant_info',array('account_id'=>$v['account_id'],'room_id'=>$v['id']));?>" class="btn btn-primary"><?php echo ($v["realname"]); ?>
                                                        
                                                        <?php switch($v["sex"]): case "1": ?>男<?php break;?>
                                                         <?php case "2": ?>女<?php break;?>
                                                         <?php default: endswitch;?>
                                                        </a><?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php switch($v['status']): case "0": ?><a href="<?php echo U('Home/Steward/roominfo',array('id'=>$v['id']));?>" class="btn btn-danger btn-mini">入住</a><?php break;?>
                                                        <?php case "1": ?><label class="btn btn-warning btn-mini">缴定</label><?php break;?>
                                                        <?php case "2": ?><label class="btn btn-success btn-mini">已租</label><?php break; endswitch;?>  
                                                </td>
                                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
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