<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>待办</title>
  <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width">
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">    
	<meta http-equiv="keywords" content="keyword1,keyword2,keyword3">
	<meta http-equiv="description" content="This is my page">
	<link href="/Public/css/normalize.css" rel="stylesheet" type="text/css">
	<link href="/Public/css/bootstrap.min.css" rel="stylesheet">
	<link href="/Public/css/bootstrap-theme.min.css" type="text/css">
	<link href="/Public/css/common.css" rel="stylesheet" type="text/css">
	<link href="/Public/css/map-search.css" rel="stylesheet" type="text/css">
	<script src="/Public/js/jquery-1.9.1.js" type="text/javascript"></script>
	<script src="/Public/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="/Public/js/iscroll.js"></script>
	<script type="text/javascript">
		var myScroll;

		function loaded () {
			myScroll = new IScroll('#wrapper', { mouseWheel: true,keyBindings:true,click:true });
		}

		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
			
	</script>
</head>

<body onload="loaded()">
<div id="fwxx">
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
	        <a href="#">
	           <div class="ztfj-soos-icon-dw"></div> 
	        </a>
	    </div>
    </div>
  </div>

  <div id="wrapper">
      <!-- 搜索功能 -->
      <div>
        <ul class="search-wrap">
          <li class="col-xs-3 nav-item">
            <a href="">全部</a>
          </li>
          <li class="col-xs-2 nav-item">
            <a href="">退房(<?php echo ($tf_count); ?>)</a>
          </li>
          <li class="col-xs-2 nav-item">
            <a href="">换房(<?php echo ($hf_count); ?>)</a>
          </li>
          <li class="col-xs-2 nav-item">
            <a href="">转租(<?php echo ($zf_count); ?>)</a>
          </li>
          <li class="col-xs-2 nav-item">
            <a href="">缴定(<?php echo ($jd_count); ?>)</a>
          </li>
        </ul>
      </div>
      <!-- 搜索功能END -->

      <div style="background-color:#fff;">
        <div class="row-fluid">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>类型</th>
                <th>房屋</th>
                <th>房间</th>
                <th>床位</th>
                <th>租客</th>
              </tr>
            </thead>
            <tbody>
              <?php if(is_array($schedule_list)): $i = 0; $__LIST__ = $schedule_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; switch($vo['schedule_type']): case "1": ?><tr onclick="window.location='<?php echo U('Home/Steward/check_ammeter',array('schedule_id'=>$vo['id'],'schedule_type'=>C('schedule_type_tf')));?>'"><?php break;?>
                  <?php case "2": ?><tr onclick="window.location='<?php echo U('Home/Steward/check_ammeter',array('schedule_id'=>$vo['id'],'schedule_type'=>C('schedule_type_zf')));?>'"><?php break;?>
                  <?php case "3": ?><tr onclick="window.location='<?php echo U('Home/Steward/check_ammeter',array('schedule_id'=>$vo['id'],'schedule_type'=>C('schedule_type_hf')));?>'"><?php break;?>
                  <?php case "4": ?><tr onclick="window.location='<?php echo U('Home/Steward/order_checkin',array('schedule_id'=>$vo['id']));?>'"><?php break;?>
                  <?php default: ?><tr><?php endswitch;?>
                      <td><?php echo ($vo["schedule_type_name"]); ?></td>
                      <td><?php echo ($vo["house_code"]); ?></td>
                      <td><?php echo ($vo["room_code"]); ?></td>
                      <?php if(!empty($vo['bed_code'])): ?><td><?php echo ($vo["bed_code"]); ?></td>
                        <?php else: ?>
                        <td></td><?php endif; ?>
                      <td><?php echo ($vo["realname"]); ?></td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>