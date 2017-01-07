<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>系统发生错误</title>
<style type="text/css">
*{ padding: 0; margin: 0; }
body {
	background-color: #4AEC6C;
}
.content {
	width: 100%;
	height: 700px;
	background: url('/Public/images/error_page.jpg') center center;
	background-size:  100%;
}
</style>
</head>
<body>
<div class="error">
	<div class="content">
		<h1><?php echo strip_tags($e['message']);?></h1>
		<?php if(isset($e['file'])) {?>
			<div class="info">
				<div class="title">
					<h3>错误位置</h3>
				</div>
				<div class="text">
					<p>FILE: <?php echo $e['file'] ;?> &#12288;LINE: <?php echo $e['line'];?></p>
				</div>
			</div>
		<?php }?>
		<?php if(isset($e['trace'])) {?>
			<div class="info">
				<div class="title">
					<h3>TRACE</h3>
				</div>
				<div class="text">
					<p><?php echo nl2br($e['trace']);?></p>
				</div>
			</div>
		<?php }?>
	</div>
</div>
</body>
</html>
