<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <base href=".">
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">    
	<meta http-equiv="keywords" content="keyword1,keyword2,keyword3">
	<meta http-equiv="description" content="This is my page">
	<title>录入水电气</title>
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width"><meta name="format-detection" content="email=no,address=no,telephone=no">
    <link href="/Public/css/normalize.css" rel="stylesheet" type="text/css">
    <link href="/Public/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Public/css/bootstrap-theme.min.css" type="text/css">
    <link href="/Public/css/common.css" rel="stylesheet" type="text/css">
    <link href="/Public/css/bill.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="/Public/js/iscroll.js"></script>
    <script type="text/javascript">
    var myScroll;

    function loaded () {
    	myScroll = new IScroll('#wrapper', { mouseWheel: true ,click:false});
    }

    document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false)

    function check_SDQ(){

        var zs = document.getElementById("zs").value;
        var zd = document.getElementById("zd").value;
        var zq = document.getElementById("zq").value;
        if ( "" == zs || "" == zd || "" == zq) {
            alert("请录入水电气!");
            return false;
        }
        var roomd = document.getElementsByClassName("roomd");
        for( var i=0; i<roomd.length; i++ ){
            var roomdVal = roomd[i].value;
            if ( "" == roomdVal ) {
                alert("请录入水电气!");
                return false;
            }
        }
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
    body{
    	line-height:normal;
    	}
    ol, ul{
    	margin-bottom:0;
    	}
    </style>

</head>

<body onload="loaded()">
<div id="fwxx">
	<div class="container-fluid header">
    	<div class="row-fluid">
        	<div class="col-xs-2 header-icon1"><a href="javascript:window.history.back(-1)"><div class="retun-db-box"><img src="/Public/images/icon-db-retun.png" class="icon-db-retun"></div></a></div>
        </div>
    </div>
    <div id="wrapper">
        <div>
			<div class="container-fluid" id="XS">
                <div class="row-fluid">
                    <div class="col-xs-12">
                    	<div class="row-fluid">
                            <div class="col-xs-12"><div class="innre-bill-ls-dsh"></div></div>
                        </div>
                        <div><h5 style="text-align: center;"><?php echo ($house_info["house_code"]); ?></h5></div>
                        <div class="row-fluid">
                            <form action="<?php echo U('Home/Steward/type_in');?>" method="post" onsubmit="return check_SDQ()">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    
                                    <tr><td>总水：</td><td><input placeholder="0" type="text" name="zS" id="zs" <?php if($house_ammeter['total_water'] != 0): ?>value="<?php echo ($house_ammeter['total_water']); ?>"<?php else: ?>value=""<?php endif; ?></td></tr>
                                    <tr><td>总电：</td><td><input placeholder="0" type="text" name="zD" id="zd" <?php if($house_ammeter['total_energy'] != 0): ?>value="<?php echo ($house_ammeter['total_energy']); ?>"<?php else: ?>value=""<?php endif; ?>></td></tr>
                                    <tr><td>总气：</td><td><input placeholder="0" type="text" name="zQ" id="zq" <?php if($house_ammeter['total_gas'] != 0): ?>value="<?php echo ($house_ammeter['total_gas']); ?>"<?php else: ?>value=""<?php endif; ?>></td></tr>
                                    
                                    <?php if($house_info['type'] == 1): ?><tr><td colspan="2" align="center">房间电表</td></tr>
                                    <?php if(is_array($aRoom_list)): $i = 0; $__LIST__ = $aRoom_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                        <td>房间编码：<?php echo ($vo["room_code"]); ?></td>
                                     <td>
                                      <input placeholder="0" type="text" name="roomD[<?php echo ($vo["id"]); ?>][room_energy]" class="roomd" <?php if($vo['room_energy'] != 0): ?>value="<?php echo ($vo["room_energy"]); ?>"<?php else: ?>value=""<?php endif; ?>>
                                      <input placeholder="0" type="hidden" name="roomD[<?php echo ($vo["id"]); ?>][room_id]" value="<?php echo ($vo["room_id"]); ?>">
                                      <input placeholder="0" type="hidden" name="roomD[<?php echo ($vo["id"]); ?>][room_code]" value="<?php echo ($vo["room_code"]); ?>">
                                     </td></tr><?php endforeach; endif; else: echo "" ;endif; endif; ?>

                                    <tr>
                                    <td colspan="2" align="center">
                                        <input type="checkbox" name="is_clear" value="1" placeholder="">是否清表(不和上个月判断)
                                        <input class="btn btn-success" type="submit" value="保存">
                                        <input type="hidden" name="amme_id" value="<?php echo ($amme_id); ?>">
                                        <input type="hidden" name="house_id" value="<?php echo ($house_id); ?>">
                                        <input type="hidden" name="input_month" value="<?php echo ($input_month); ?>">
                                        <input type="hidden" name="input_year" value="<?php echo ($input_year); ?>">
                                    </td>
                                    </tr>
                                </tbody>
                                </form>
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
</div>
</body>
</html>