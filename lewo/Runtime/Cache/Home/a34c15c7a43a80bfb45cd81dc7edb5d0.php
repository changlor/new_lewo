<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>乐窝微网站</title>
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width"><meta name="format-detection" content="email=no,address=no,telephone=no">

	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">    
	<meta http-equiv="keywords" content="keyword1,keyword2,keyword3">
	<meta http-equiv="description" content="This is my page">
	<link href="/Public/css/login.css" rel="stylesheet" type="text/css">
	<script src="/Public/js/hm.js"></script><script>
		var _hmt = _hmt || [];
		(function() {
		  var hm = document.createElement("script");
		  hm.src = "//hm.baidu.com/hm.js?5bd301619c3b3f6d0480aa7f29d26439";
		  var s = document.getElementsByTagName("script")[0]; 
		  s.parentNode.insertBefore(hm, s);
		})();
			
		function submit(){
			document.getElementById("login").submit();
		}
	</script>
	
	
  </head>
  
  <body>
<div id="denglu">
	<div><a href="<?php echo U('Home/steward/login');?>" class="but_color">我是管家</a></div>
	<div class="denglu_inner">
		<img src="/Public/images/logo.png" class="logo">
    	<h2 class="lewo">乐窝</h2>
   		<span class="loveto">loveto.co</span>	
    </div>
    <div class="inner_icon">
    	<form action="<?php echo U('Home/Tenant/login');?>" method="post" id="login">
			<input type="hidden" style="width:400px" name="wxOpenId" value="">
			<div class="icon_pic">
				<div class="icon_pic_box">
					<img src="/Public/images/id.png" class="icon">
            		<input type="text" placeholder="帐号" name="userName" id="userName" class="address">
            	</div>
            </div>
			<div class="icon_pic">
				<div class="icon_pic_box">
					<img src="/Public/images/idmima.png" class="icon">
            		<input type="password" placeholder="密码" name="userPass" id="userPass" class="address">
            	</div>
            </div>
           	<div class="button">
            	<a href="javascript:submit();"><p class="but_color">登录</p></a>
            </div>
    	</form>
	</div>
</div>
</body>
</html>