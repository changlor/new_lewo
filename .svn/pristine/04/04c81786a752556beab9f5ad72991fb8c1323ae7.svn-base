<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
<title>乐窝公寓管理系统</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="/Public/admin/css/bootstrap.min.css" />
<link rel="stylesheet" href="/Public/admin/css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="/Public/admin/css/datepicker.css" />
<link rel="stylesheet" href="/Public/admin/css/uniform.css" />
<link rel="stylesheet" href="/Public/admin/css/select2.css" />
<link rel="stylesheet" href="/Public/admin/css/matrix-style.css" />
<link rel="stylesheet" href="/Public/admin/css/matrix-media.css" />
<link href="/Public/admin/font-awesome/css/font-awesome.css" rel="stylesheet" />
<script src="/Public/admin/js/check.form.js"></script>
</head>
<body>
<!--main-container-part-->
<div>
  <div class="widget-box">
   <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
      <h5>修改待办信息</h5>
      <a href="<?php echo U('Admin/Task/index');?>" class="label label-info pull-left">返回</a>
    </div>
    <form action="<?php echo U('Admin/Task/update_schedule');?>" method="post" enctype="multipart/form-data" class="form-horizontal form-inline">
      <!-- 房屋信息 -->
      <table class="table table-condensed">
        <tbody>
          <tr>
            <td width="130"><i class="icon-home"></i>退房截至水表：</td>
            <td><input name="zs" type="text" class="span2" value="<?php echo ($schedule_info["zs"]); ?>"></td>
          </tr> 
          <tr>
            <td width="130"><i class="icon-home"></i>退房截至电表：</td>
            <td><input name="zd" type="text" class="span2" value="<?php echo ($schedule_info["zd"]); ?>"></td>
          </tr>   
          <tr>
            <td width="130"><i class="icon-home"></i>退房截至气表：</td>
            <td><input name="zq" type="text" class="span2" value="<?php echo ($schedule_info["zq"]); ?>"></td>
          </tr>   
          <tr>
            <td width="130"><i class="icon-home"></i>退房截至房间电表：</td>
            <td><input name="roomd" type="text" class="span2" value="<?php echo ($schedule_info["roomd"]); ?>"></td>
          </tr>  
          <tr>
            <td width="130"><i class="icon-home"></i>维修费：</td>
            <td><input name="wx_fee" type="text" class="span2" value="<?php echo ($schedule_info["wx_fee"]); ?>"></td>
          </tr> 
          <tr>
            <td width="130"><i class="icon-home"></i>维修信息：</td>
            <td><input name="wx_des" type="text" class="span2" value="<?php echo ($schedule_info["wx_des"]); ?>"></td>
          </tr> 
        </tbody>
      </table>

      <div class="text-center">
        <input type="hidden" name="schedule_id" value="<?php echo ($schedule_id); ?>">
        <input class="btn btn-success" type="submit" value="保存结束"> 
        <a href="<?php echo U('Admin/Task/index');?>" class="btn btn-danger">放弃操作</a>
      </div>
    </form>
  </div>
</div>