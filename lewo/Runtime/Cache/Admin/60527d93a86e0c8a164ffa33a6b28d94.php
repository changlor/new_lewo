<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
<title>乐窝公寓管理系统</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="/Public/admin/css/bootstrap.min.css" />
<link rel="stylesheet" href="/Public/admin/css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="/Public/admin/css/matrix-style.css" />
<link rel="stylesheet" href="/Public/admin/css/matrix-media.css" />
<link href="/Public/admin/font-awesome/css/font-awesome.css" rel="stylesheet" />

</head>
<body>
<!--main-container-part-->
<div>
<div class="widget-box">
  <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
    <h5>例行打款</h5>
    <a href="javascript:window.history.back(-1);" class="label label-info pull-left">返回</a>
    <a href="#" class="label label-info pull-left">下载execl</a>
  </div>
  <form action="<?php echo U('Admin/Task/money_back');?>" method="post">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th></th>
          <th>房屋编号</th>
          <th>房间编号</th>
          <th>床位编号</th>
          <th>租客</th>
          <th>帐号类型</th>
          <th>帐号</th>
          <th>电话</th>
          <th>待打款金额</th>
        </tr>
      </thead>
      <tbody>
        <?php if(is_array($back_bill)): $i = 0; $__LIST__ = $back_bill;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
          <td>
            <?php if($vo['is_finish'] != 1): ?><input type="checkbox" name="id[]" value="<?php echo ($vo["id"]); ?>"><?php endif; ?>
          </td>
          <td>
            <?php echo ($vo["room_info"]["house_code"]); ?>
          </td>
          <td><?php echo ($vo["room_info"]["room_code"]); ?></td>
          <td><?php echo ($vo["room_info"]["bed_code"]); ?></td>
          <td><?php echo ($vo["realname"]); ?></td>
          <td><?php echo ($vo["pay_type_name"]); ?></td>
          <td><?php echo ($vo["pay_account"]); ?></td>
          <td><?php echo ($vo["mobile"]); ?></td>
          <td><?php echo ($vo["money"]); ?></td>
        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        <tr>
          <td colspan="19" style="text-align: center">
          <!-- 确认未支付单 -->
            <input type="submit" class="btn btn-success" value="完成">
            <input type="hidden" name="back_type" value="<?php echo ($back_type); ?>">
          <!-- 确认未支付单 -->
          </td>
        </tr>
      </tbody>
    </table>
    </form>
  </div>
</div>

<!--main-container-part-->