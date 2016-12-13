<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">    
	<meta http-equiv="keywords" content="keyword1,keyword2,keyword3">
	<meta http-equiv="description" content="This is my page">
	<title><?php echo ($check_out_type["$type"]); ?>信息</title>
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width"><meta name="format-detection" content="email=no,address=no,telephone=no">
    <link href="/Public/css/normalize.css" rel="stylesheet" type="text/css">
    <link href="/Public/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Public/css/bootstrap-theme.min.css" type="text/css">
    <link href="/Public/css/common.css" rel="stylesheet" type="text/css">
    <link href="/Public/css/bill.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="/Public/js/iscroll.js"></script>
<link href="/Public/css/mobiscroll.core-2.5.2.css" rel="stylesheet" type="text/css">
<link href="/Public/css/mobiscroll.animation-2.5.2.css" rel="stylesheet" type="text/css">
<link href="/Public/css/mobiscroll.android-ics-2.5.2.css" rel="stylesheet" type="text/css">
<!-- E 可根据自己喜好引入样式风格文件 -->  

    <script type="text/javascript">
    var myScroll;

    function loaded () {
        myScroll = new IScroll('#wrapper', { tap : true, mouseWheel : true});
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
        #status-btn {
            width:15%;
        }
    .wupin {
        font-size: 25px;
        width: 40px;
        height: : 40px;
        border: 1px solid #ccc;
    }
    #check-home {
        position: fixed;
        top: : 0;
        z-index: 3;
        background: #fff;
        width: 100%;
        height: 100%;
        display: none;
        overflow-y: scroll;
    }
    #check-home label {
        font-size: 25px;
    }
    #check-home .item {
        width: 40px;
        height: 40px;
    }
    #check-home #close {
        float: right;
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
                <form action="" method="post" onsubmit="return checkform();">
                <div id="check-home">
                    <a class="btn btn-warning btn-mini" id="close">X</a>
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <td colspan="2"><a class="btn btn-mini btn-info" id="all">全选/反选</a></td>
                            </tr>
                            <?php if(is_array($check_item)): $i = 0; $__LIST__ = $check_item;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($key % 2 != 0): ?><tr>
                                    <td>
                                        <label for="check_item[<?php echo ($key); ?>]"><?php echo ($vo); ?></label>
                                        <input class="item" type="checkbox" name="check_item[<?php echo ($key); ?>]" id="check_item[<?php echo ($key); ?>]" value="1">
                                    </td>
                            <?php else: ?>
                                    <td>
                                        <label for="check_item[<?php echo ($key); ?>]"><?php echo ($vo); ?></label>
                                        <input class="item" type="checkbox" name="check_item[<?php echo ($key); ?>]" id="check_item[<?php echo ($key); ?>]" value="1">
                                    </td>
                                </tr><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                            <tr>
                                <td colspan="2" style="text-align: center;">其余物品管家按房间设施检查损坏</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: center;">验房情况</td>
                            </tr>
                            <tr>
                                <td colspan="2" >
                                    <textarea id="check_out_msg" style="width: 100%" placeholder="例:一切正常/**损坏"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: center;">
                                    <input type="hidden" name="is_success" id="is_success" value="0">
                                    <a class="btn btn-success" id="check-success">验房完成</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row-fluid">
                    <div class="col-xs-12">
                    	<div class="row-fluid">
                            <div class="col-xs-12"><div class="innre-bill-ls-dsh"></div></div>
                        </div>
                        <div class="row-fluid">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr><td colspan="2" align="center"><?php echo ($room_info["area_name"]); ?>(<?php echo ($room_info["house_info"]["building"]); ?>-<?php echo ($room_info["house_info"]["floor"]); ?>-<?php echo ($room_info["house_info"]["door_no"]); ?>)</td></tr>
                                    <tr><td>房间编号</td><td><input type="text" value="<?php echo ($room_info['house_code']); ?>"></td></tr>
                                    <tr><td>租客姓名</td><td><a href="123">asd</a><?php echo ($account_info['realname']); ?></td></tr>
                                    <tr><td>联系电话</td><td><?php echo ($account_info['mobile']); ?></td></tr>
                                    <tr><td>房屋地址</td><td><input type="text" name="address" value="<?php echo ($room_info["house_info"]["address"]); echo ($room_info["area_name"]); ?>(<?php echo ($room_info["house_info"]["building"]); ?>-<?php echo ($room_info["house_info"]["floor"]); ?>-<?php echo ($room_info["house_info"]["door_no"]); ?>)"></td></tr>
                                    <tr><td>退租日期</td><td><input type="text" id="check-out-day" name="check_out_day" value=""></td></tr>
                                <?php if($type != 3): ?><tr><td>退租原因</td><td><input type="text" id="check-out-des" name="check_out_des" value=""></td></tr>
                                    <tr><td>退款账号</td><td><input type="text" id="refund-account" name="pay_account" value="" placeholder="账号+账号姓名"></td></tr><?php endif; ?>
                                    <tr>
                                        <td>退款方式</td>
                                        <td>    
                                            <select name="pay_type">
                                                <?php if(is_array($refund_type)): $i = 0; $__LIST__ = $refund_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>类型</td>
                                        <td>
                                            <select name="type">
                                            <?php if(is_array($check_out_type)): $i = 0; $__LIST__ = $check_out_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if($type == $key): ?>selected<?php endif; ?>><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody>
                                    <tr><td colspan="2" align="center">房屋电表</td></tr>
                                    <tr><td>总电表</td><td><input id="zD" type="text" name="zD" value=""></td></tr>
                                    <tr><td>总水表</td><td><input id="zS" type="text" name="zS" value=""></td></tr>
                                    <tr><td>总气表</td><td><input id="zQ" type="text" name="zQ" value=""></td></tr>
                                    <tr>
                                        <td colspan="2" align="center">房间电表</td>
                                    </tr>
                                    <?php if(is_array($room_list)): $i = 0; $__LIST__ = $room_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                            <td><?php echo ($vo["room_code"]); ?></td>
                                             <td>
                                              <input placeholder="0" type="text" name="roomD[<?php echo ($vo["id"]); ?>][room_energy]">
                                              <!-- <input type="hidden" name="roomD[<?php echo ($vo["id"]); ?>][room_code]" value="<?php echo ($vo["room_code"]); ?>"> -->
                                             </td>
                                         </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                                    <!-- <tr><td colspan="2" align="center"><input type="checkbox" name="not_judge" value="1">不与最新水表/电表/气表作判断</td></tr> -->
                                    <tr><td colspan="2" align="center"><a class="btn btn-primary" id="start-check">开始验房</a></td></tr>
                                    <tr>
                                        <td colspan="2">
                                        <textarea name="check_out_msg" id="input_check_out_msg" style="width: 100%" placeholder="例:一切正常/**损坏"></textarea>
                                        </td>
                                    </tr>
                                    <tr><td>维修费</td><td><input type="text" name="wx_fee" value="" placeholder=""></td></tr>
                                    <tr><td>维修信息</td><td><input type="text" name="wx_des" value="" placeholder=""></td></tr>
                                    <tr>
                                        <td colspan="2" align="center">物品交接</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="center">
                                            <?php if(is_array($check_out_goods)): $i = 0; $__LIST__ = $check_out_goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; echo ($vo); ?><input type="text" name="goods[<?php echo ($key); ?>]" value="0" class="wupin">
                                                <?php if($key % 2 == 0): ?><br/><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>其他交接</td><td><input type="text" name="goods[other]" value="" placeholder=""></td>
                                    </tr>
                                    <tr>
                                    <td colspan="2" align="center">
                                        <input type="hidden" name="account_id" value="<?php echo ($account_id); ?>">
                                        <input type="hidden" name="room_id" value="<?php echo ($room_id); ?>">
                                        <input class="btn btn-success" type="submit" value="提交">
                                    </td>
                                    </tr>
                                </tbody>
                                
                            </table>
                        </div>
                        <!--滑动过度区域-->
                        <div class="row-fluid">
                            <div class="col-xs-12"><div style="height:90px;"></div></div>
                        </div>
                        <!--滑动过度区域-->
                    </div>
                </div>
                </form> 
			</div>                   
        </div>  
    </div>
</div>
</body>
<script src="/Public/js/jquery-1.9.1.js"></script>
<script src="/Public/js/bootstrap.min.js"></script>
<script src="/Public/js/mobiscroll.core-2.5.2.js" type="text/javascript"></script>
<script src="/Public/js/mobiscroll.core-2.5.2-zh.js" type="text/javascript"></script>
<script src="/Public/js/mobiscroll.datetime-2.5.1.js" type="text/javascript"></script>
<script src="/Public/js/mobiscroll.datetime-2.5.1-zh.js" type="text/javascript"></script>
<!-- S 可根据自己喜好引入样式风格文件 -->
<script src="/Public/js/mobiscroll.android-ics-2.5.2.js" type="text/javascript"></script>
<script>
function checkform(obj){
    if(document.getElementById("check-out-des").value == "" || document.getElementById("check-out-des").value==null){
        alert("退租描述不能为空");
        return false;   
    }
    if(document.getElementById("check-out-day").value == "" || document.getElementById("check-out-day").value==null){
        alert("退租日期不能为空");
        return false;   
    }
    if(document.getElementById("refund-account").value == "" || document.getElementById("refund-account").value==null){
        alert("打款账号不能为空");
        return false;   
    }
    
    if(document.getElementById("zS").value == "" || document.getElementById("zS").value==null){
        alert("总水不能为空");
        return false;   
    }
    if(document.getElementById("zD").value == "" || document.getElementById("zD").value==null){
        alert("总水不能为空");
        return false;   
    }
    if(document.getElementById("zQ").value == "" || document.getElementById("zQ").value==null){
        alert("总水不能为空");
        return false;   
    }
    if(document.getElementById("roomD").value == "" || document.getElementById("roomD").value==null){
        alert("房间电表不能为空");
        return false;   
    }   
    if ( $('#is_success').val() != 1 ) {
        alert("未验房");
        return false;
    }
    if ( !confirm('是否确认退房') ) {
        return false;
    }
}
$(function () {
    $("#check-out-day").val('<?php echo ($now_day); ?>').scroller($.extend({preset : 'date'}, { theme: "android-ics light", mode: "scroller", display: 'modal', lang: 'zh',dateFormat : "yy-mm-dd",dateOrder: 'yymmddDD',endYear: (new Date()).getFullYear() + 20})); 
});
</script>
<script type="text/javascript">
$(window).load(function(){
    $('#start-check').on('tap',function(){
        $('#check-home').show('fast');
        myScroll.scrollTo(0, 0);
    });
    $('#check-home > #close').on('tap',function(){
        $('#check-home').hide('fast');
    });
    $('#check-success').on('tap',function(){
        if ( !confirm('确认检查完成') ) {
            return false;
        }
        $('#check-home').hide('fast');
        $('#is_success').val(1);

        $('#input_check_out_msg').val( $('#check_out_msg').val() );
    });
    $('#all').on('tap',function(){
        if ( $(this).attr("checked") == undefined) {  
            $(this).attr('checked','true');
            $(":checkbox").each(function() {  
                $(this).prop("checked", 'true');  
            });  
        } else {  
            $(this).removeAttr('checked');
            $(":checkbox").each(function() {  
                $(this).removeProp('checked');  
            });  
        }  
    })
});
</script>

</html>