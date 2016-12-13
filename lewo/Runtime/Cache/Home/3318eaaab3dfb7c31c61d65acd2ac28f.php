<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">    
	<meta http-equiv="keywords" content="keyword1,keyword2,keyword3">
	<meta http-equiv="description" content="This is my page">
	<title>租客信息</title>
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
    	myScroll = new IScroll('#wrapper', { mouseWheel: true ,click: true});
    }

    document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false)

    </script>
    <style>
    #wrapper {
    	position:absolute;
        z-index: 1;
    	top:44px;
    	bottom:0px;
    	left:0;
    	width:100%;
    	overflow:hidden;
    	background-color:#f4f4f4;
    	}

    #scroller {
        position: absolute;
        z-index: 1;
        -webkit-tap-highlight-color: rgba(0,0,0,0);
        height: 50px;
        width: 80%;
        left: 0;
        right: 0;
        top:-20%;
        bottom: 0;
        margin: auto auto;
        -webkit-transform: translateZ(0);
        -moz-transform: translateZ(0);
        -ms-transform: translateZ(0);
        -o-transform: translateZ(0);
        transform: translateZ(0);
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        -webkit-text-size-adjust: none;
        -moz-text-size-adjust: none;
        -ms-text-size-adjust: none;
        -o-text-size-adjust: none;
        text-size-adjust: none;
    }

    #scroller ul {
        list-style: none;
        padding: 0;
        margin: 0;
        width: 100%;
        height: auto;
        text-align: left;
        border: 1px solid #ccc;
    }

    #scroller li {
        padding: 0 10px;
        height: 50px;
        line-height: 50px;
        border-bottom: 1px solid #ccc;
        border-top: 1px solid #fff;
        background-color: #fafafa;
        font-size: 14px;
        text-align: center;
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
    </style>

</head>

<body onload="loaded()">
<div id="fwxx">
	<div class="container-fluid header">
    	<div class="row-fluid">
        	<div class="col-xs-2 header-icon1"><a href="<?php echo (session('stewrad_houses_back_url')); ?>"><div class="retun-db-box"><img src="/Public/images/icon-db-retun.png" class="icon-db-retun"></div></a></div>
        </div>
    </div>
    <div id="wrapper">
        <div>
            <div id="scroller" style="display: none">
                <ul>
                    <?php if(is_array($check_out_type)): $i = 0; $__LIST__ = $check_out_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a onclick="location.href='<?php echo U('Home/Steward/check_out',array('account_id'=>$account_info['id'],'room_id'=>$room_info['id'],'type'=>$key));?>'"><?php echo ($vo); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
			<div class="container-fluid" id="XS">
                <div class="row-fluid">
                    <div class="col-xs-12">
                    	<div class="row-fluid">
                            <div class="col-xs-12"><div class="innre-bill-ls-dsh"></div></div>
                        </div>
                        <div class="row-fluid">
                            <form action="<?php echo U('Home/Steward/tenant_info');?>" method="post" onsubmit="return checkform();">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr><td colspan="2" align="center"><?php echo ($room_info["area_name"]); ?>(<?php echo ($room_info["building"]); ?>-<?php echo ($room_info["floor"]); ?>-<?php echo ($room_info["door_no"]); ?>)&nbsp;&nbsp;<?php echo ($room_info["room_code"]); ?>&nbsp;&nbsp;<?php echo ($room_code["bed_code"]); ?></td></tr>
                                    <tr><td>姓名：</td><td><input type="text" name="realname" value="<?php echo ($account_info["realname"]); ?>"/></td></tr>
                                    <tr><td>电话：</td><td><input type="text" name="mobile" value="<?php echo ($account_info["mobile"]); ?>"/><a href="tel:<?php echo ($account_info["mobile"]); ?>" class="btn btn-info">拨打</a></td></tr>
                                    <tr><td>邮箱：</td><td><input type="text" name="email" value="<?php echo ($account_info["email"]); ?>"/></td></tr>
                                    <tr>
                                    <td>性别：</td>
                                        <td>
                                        <label for="nan" class="input-grxx-xb-box"><input type="radio" value="1" name="sex" <?php if($account_info["sex"] == 1): ?>checked<?php endif; ?> > 男</label>
                                        <label for="nv" class="input-grxx-xb-box"><input type="radio" value="2" name="sex" <?php if($account_info["sex"] == 2): ?>checked<?php endif; ?>> 女</label>
                                        </td>
                                    </tr>
                                    <tr><td>身份证：</td><td><input type="text" name="card_no" value="<?php echo ($account_info["card_no"]); ?>"/></td></tr>
                                    <tr><td>生日：</td><td><input type="text" name="birthday" value="<?php echo ($account_info["birthday"]); ?>"/></td></tr>
                                    <tr><td>睡眠习惯：</td><td><input type="text" name="sleep_habit" value="<?php echo ($account_info["sleep_habit"]); ?>"/></td></tr>
                                    <tr><td>标签：</td><td><input type="text" name="tag" value="<?php echo ($account_info["tag"]); ?>"/></td></tr>
                                    <tr><td>工作：</td><td><input type="text" name="job" value="<?php echo ($account_info["job"]); ?>"/></td></tr>
                                    <tr><td>兴趣：</td><td><input type="text" name="hobby" value="<?php echo ($account_info["hobby"]); ?>"/></td></tr>
                                    <tr><td>特长：</td><td><input type="text" name="specialty" value="<?php echo ($account_info["specialty"]); ?>"/></td></tr>
                                    <tr><td>学历：</td><td><input type="text" name="edu_history" value="<?php echo ($account_info["edu_history"]); ?>"/></td></tr>
                                    <tr>
                                    <td colspan="2">
                                        <input type="hidden" name="account_id" value="<?php echo ($account_info["id"]); ?>">
                                        <input type="hidden" name="room_id" value="<?php echo ($room_info["id"]); ?>">
                                        <button class="btn btn-success">修改</button>
                                        <a onclick="location.href='<?php echo U('Home/Steward/tenant_contract',array('account_id'=>$account_info['id'],'room_id'=>$room_info['id']));?>'"  class="btn btn-primary">合同</a>
                                        <a onclick="recovery(<?php echo ($account_info["id"]); ?>)"  class="btn btn-warning">还原密码</a>
                                        <a class="btn btn-danger " id="check-out">退房</a>
                                    </td>
                                    </tr>
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
<script src="/Public/js/jquery-1.9.1.js"></script>
<script>
/*$(window).load(function(){
   $("#status-btn").click(function(e){
    var txt = $(this)[0].innerText;
    $("#status-info").attr("value",txt);
   }); 

   $("#radio1").click(function(){
    $("#radio1_on").attr("checked","true");
    $("#radio2_on").removeAttr("checked");
   });
   $("#radio2").click(function(){
     $("#radio2_on").attr("checked","true");
     $("#radio1_on").removeAttr("checked");
   });
});*/
function checkform(obj){
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
}

function recovery(account_id){
    if ( account_id == 'undefined') return false;
    if ( confirm('是否还原') ) {
        url = "<?php echo U('Home/Steward/recovery');?>";
        $.ajax({
           type: "POST",
           url: url,
           data: "account_id="+account_id,
           dataType: "json",
           success: function(data){
             if ( data ) {
                alert('修改成功');
             } else {
                alert('修改失败');
             }
           }
        });
    } else {
        return false;
    }
}

$('#check-out').click(function(){
    $('#scroller').toggle();
});
</script>

</html>