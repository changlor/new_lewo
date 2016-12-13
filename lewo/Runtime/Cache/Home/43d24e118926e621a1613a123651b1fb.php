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
	<title>房屋水电气列表</title>
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width"><meta name="format-detection" content="email=no,address=no,telephone=no">
    <link href="/Public/css/normalize.css" rel="stylesheet" type="text/css">
    <link href="/Public/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Public/css/bootstrap-theme.min.css" type="text/css">
    <link href="/Public/css/common.css" rel="stylesheet" type="text/css">
    <link href="/Public/css/bill.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="/Public/js/iscroll.js"></script>
    <script src="/Public/js/jquery-1.9.1.js" type="text/javascript"></script>
    <script src="/Public/js/common.js" type="text/javascript"></script> 
    <script type="text/javascript">
    var myScroll;

    function loaded () {
    	myScroll = new IScroll('#wrapper', { mouseWheel: true ,click:true});
    }

    document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false)

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
<div id="fwxx" >
	<div class="container-fluid header">
    	<div class="row-fluid">
        	<div class="col-xs-2 header-icon1"><a href="javascript:window.history.back(-1)"><div class="retun-db-box"><img src="/Public/images/icon-db-retun.png" class="icon-db-retun"></div></a></div>
        <div class="col-xs-8">
            <div class="ssearch">
              <form action="">
                <input class="iSearch" type="search" name="" value="" placeholder="请输入">
                <!-- <input class="isubmit" type="submit" value="搜索"> -->
              </form>
            </div>
        </div>
        <div class="col-xs-2 ztfj-top-pad2">
        </div>
        </div>
    </div>
    <div id="wrapper" >
        <div>
			<div class="container-fluid" id="XS">
                <div class="row-fluid">
                    <div class="col-xs-12">
                    	<div class="row-fluid">
                            <div class="col-xs-12"><div class="innre-bill-ls-dsh"></div></div>
                        </div>
                        <div><h5 style="text-align: center;"><?php echo ($houses_info["house_code"]); ?>&nbsp;<?php echo ($houses_info["area_name"]); ?>&nbsp;<?php echo ($houses_info["building"]); ?>&nbsp;-&nbsp;<?php echo ($houses_info["floor"]); ?>&nbsp;-&nbsp;<?php echo ($houses_info["door_no"]); ?></h5></div>
                        <div class="row-fluid">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>年</th>
                                        <th>月</th>
                                        <th>状态</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(is_array($ammter_list)): $i = 0; $__LIST__ = $ammter_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tbody>
                                            <tr onclick="window.location='<?php echo U('Home/Steward/type_in',array('amme_id'=>$vo['id'],'house_id'=>$house_id,'input_year'=>$vo['input_year'],'input_month'=>$vo['input_month']));?>'">
                                                <td><?php echo ($vo["input_year"]); ?></td>
                                                <td><?php echo ($vo["input_month"]); ?></td>
                                                <td>
                                                    <?php if($vo['status'] == 1): ?><a class="btn btn-success">已录</a>
                                                        <?php else: ?>
                                                        <a class="btn btn-danger">未录</a><?php endif; ?>
                                                    
                                                </td>
                                            </tr>
                                        </tbody><?php endforeach; endif; else: echo "" ;endif; ?>
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
			</div>          
        </div>  
    </div>