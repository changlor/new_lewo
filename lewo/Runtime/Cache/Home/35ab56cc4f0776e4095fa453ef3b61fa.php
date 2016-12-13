<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">    
	<meta http-equiv="keywords" content="keyword1,keyword2,keyword3">
	<meta http-equiv="description" content="This is my page">
	<title>确认<?php echo ($schedule_type_name); ?></title>
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width"><meta name="format-detection" content="email=no,address=no,telephone=no">
    <link href="/Public/css/normalize.css" rel="stylesheet" type="text/css">
    <link href="/Public/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Public/css/bootstrap-theme.min.css" type="text/css">
    <link href="/Public/css/common.css" rel="stylesheet" type="text/css">
    <link href="/Public/css/bill.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="/Public/js/iscroll.js"></script>
<!--     <script type="text/javascript">
var myScroll;

function loaded () {
    myScroll = new IScroll('#wrapper', { mouseWheel: true ,click:true});
}

document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false)

</script> -->

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

<body>
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
                        <div class="row-fluid">
                            <form action="<?php echo U('Home/Tenant/checkout');?>" method="post" enctype="multipart/form-data" onsubmit="return checkform(this)">
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        <tr><td colspan="2" align="center">租客信息</td></tr>
                                        <tr>
                                            <td>房间编号</td><td>
                                            <input type="text" value="<?php echo ($room_info['room_code']); ?>">
                                            <input type="hidden" name="schedule_type" value="<?php echo ($schedule_type); ?>">
                                            <input type="hidden" name="room_id" value="<?php echo ($room_id); ?>">
                                            <input type="hidden" name="steward_id" value="<?php echo ($room_info['steward_info']['id']); ?>">
                                            </td>
                                        </tr>
                                        <tr><td>租客姓名</td><td><?php echo ($realname); ?></td></tr>
                                        <tr><td>联系电话</td><td><?php echo ($mobile); ?></td></tr>
                                        <tr><td>申请日期</td><td><?php echo ($apply_time); ?></td></tr>
                                        <tr><td><?php echo ($schedule_type_name); ?>原因</td><td><input  type="text" name="msg" value="" placeholder=""></td></tr>
                                        <?php if($schedule_type != 1): ?><tr>
                                                <td colspan="2" align=center>换房/转房需要收取房租30%手续费</td>
                                            </tr><?php endif; ?>
                                        <?php if($schedule_type != C('schedule_type_hf')): ?><tr>
                                            <td>退款方式</td>
                                            <td>
                                                <select id="pay_type" name="pay_type">
                                                    <option value="0">请选择</option>
                                                    <option value="1">支付宝</option>
                                                    <option value="2">微信</option>
                                                </select>       
                                            </td>
                                        </tr>
                                        <tr><td>退款帐号</td><td><input type="text" id="pay_account" name="pay_account" value="" placeholder=""></td></tr><?php endif; ?>
                                        <tr><td>约定抄表时间(即退房日)</td><td><input type="text" value="" id="appoint_time" name="appoint_time" class="shuru" readonly=""></td></tr>
                                        <tr><td colspan="2">
                                            <input class="btn btn-success" type="submit" value="确定提交">
                                            </td></tr>
                                    </tbody>
                                </table>
                            </form>
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
<script src="/Public/js/jquery-1.9.1.js"></script>
<script src="/Public/js/mobiscroll.core-2.5.2.js" type="text/javascript"></script>
<script src="/Public/js/mobiscroll.core-2.5.2-zh.js" type="text/javascript"></script>
<link href="/Public/css/mobiscroll.core-2.5.2.css" rel="stylesheet" type="text/css">
<link href="/Public/css/mobiscroll.animation-2.5.2.css" rel="stylesheet" type="text/css">
<script src="/Public/js/mobiscroll.datetime-2.5.1.js" type="text/javascript"></script>
<script src="/Public/js/mobiscroll.datetime-2.5.1-zh.js" type="text/javascript"></script>
<!-- S 可根据自己喜好引入样式风格文件 -->
<script src="/Public/js/mobiscroll.android-ics-2.5.2.js" type="text/javascript"></script>
<link href="/Public/css/mobiscroll.android-ics-2.5.2.css" rel="stylesheet" type="text/css">
<!-- E 可根据自己喜好引入样式风格文件 -->
<script>
    $(function () {
        $("#appoint_time").val('').scroller($.extend({preset : 'date'}, { theme: "android-ics light", mode: "scroller", display: 'modal', lang: 'zh',dateFormat : "yy-mm-dd",dateOrder: 'yymmddDD',endYear: (new Date()).getFullYear() + 20})); 
    });
    function checkform(obj){
        var selct = document.getElementById("pay_type");
        var index = selct.selectedIndex;
        var text = selct.options[index].text; 
        var value = selct.options[index].value; 
        if (text == 0) {
            alert("请选择支付类型");
            return false;
        }
        if(document.getElementById("appoint_time").value == "" || document.getElementById("appoint_time").value==null){
            alert("约定日期不能为空");
            return false;   
        }
        if(document.getElementById("pay_account").value == "" || document.getElementById("pay_account").value==null){
            alert("帐号不能为空");
            return false;   
        }
    }

</script>