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
      <h5>添加小区</h5>
      <a href="javascript:window.history.back(-1)" class="label label-info pull-left">返回</a>
    </div>
    <form action="<?php echo U('Admin/Houses/update_area');?>" method="post" enctype="multipart/form-data" class="form-horizontal form-inline" onsubmit="return checkAddArea();">
      <!-- 房屋信息 -->
      <table class="table table-condensed">
        <tbody>
          <tr>
            <td colspan="2"><h4 class="text-center">添加<?php echo ($title); ?>信息<input type="hidden" name="house_code" value="<?php echo ($house_code); ?>"></h4></td>
          </tr>
          <tr>
            <td width="130"><i class="icon-home"></i>小区名字：</td>
            <td><input name="area_name" type="text" class="span2" value="<?php echo ($area_info['area_name']); ?>">
              <input type="hidden" name="id" value="<?php echo ($area_info['id']); ?>">
            </td>
          </tr>
          <tr>
            <td width="130"><i class="icon-home"></i>小区简介：</td>
            <td><input name="area_description" type="text" class="span2" value="<?php echo ($area_info['area_description']); ?>"></td>
          </tr>
          <tr>
            <td width="130"><i class="icon-home"></i>地区：</td>
            <td>
              <select name="city_id">
                  <option value="0">请选择</option>
                <?php if(is_array($city_id)): $i = 0; $__LIST__ = $city_id;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if($area_info['city_id'] == $key): ?>selected<?php endif; ?>><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
              </select>
            </td>
          </tr>
          <tr>
            <td width="130"><i class="icon-home"></i>阶梯电费:</td>
            <td><input name="energy_stair" type="text" class="span2" value="<?php echo ($area_info['energy_stair']); ?>"><span class="label label-important">格式：开始度数-结束度数-电费(0-200-1.5,200-400-3)</span></td>
          </tr>
          <tr>
            <td width="130"><i class="icon-home"></i>水费单价:</td>
            <td><input name="water_unit" type="text" class="span2" value="<?php echo ($area_info['water_unit']); ?>"></td>
          </tr>
          <tr>
            <td width="130"><i class="icon-home"></i>气费单价:</td>
            <td><input name="gas_unit" type="text" class="span2" value="<?php echo ($area_info['gas_unit']); ?>"></td>
          </tr>
          <tr>
            <td width="130"><i class="icon-home"></i>燃气垃圾费:</td>
            <td><input name="rubbish_fee" type="text" class="span2" value="<?php echo ($area_info['rubbish_fee']); ?>"></td>
          </tr>
          
        </tbody>
      </table>

      <div class="text-center">
        <input class="btn btn-success" type="submit" value="保存结束"> 
        <a href="<?php echo U('Admin/Houses/index');?>" class="btn btn-danger">放弃操作</a>
      </div>
    </form>
  </div>
</div>