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
      <h5>房屋信息->添加<?php echo ($title); ?>信息</h5>
      <a href="javascript:window.history.back(-1)" class="label label-info pull-left">返回</a>
    </div>
    <form action="<?php echo U('Admin/Houses/add_room');?>" method="post" enctype="multipart/form-data" class="form-horizontal form-inline" onsubmit="return checkAddRoom();">
      <!-- 房屋信息 -->
      <table class="table table-condensed">
        <tbody>
          <tr>
            <td colspan="2"><h4 class="text-center"><?php echo ($house_info["area_name"]); ?>(<?php echo ($house_info["building"]); ?>-<?php echo ($house_info["floor"]); ?>-<?php echo ($house_info["door_no"]); ?>)&nbsp;<?php echo ($house_info["house_code"]); ?>&nbsp;添加<?php echo ($title); ?>信息<input type="hidden" name="house_code" value="<?php echo ($house_code); ?>"></h4></td>
          </tr>
          <tr>
            <td width="130"><i class="icon-home"></i>房间编号:</td>
            <td><input name="room_code" type="text" class="span2" value=""></td>
          </tr>
          <?php if($add_type == 'bed'): ?><tr>
              <td width="130"><i class="icon-home"></i>床位编号:</td>
              <td><input name="bed_code" type="text" class="span2" value=""></td>
            </tr><?php endif; ?>
          <tr>
            <td><i class="icon-home"></i>房间序列:</td>
            <td>
              <select name="room_sort">
                <option value="">请选择</option>
                <?php if(is_array($room_sort_arr)): $i = 0; $__LIST__ = $room_sort_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
              </select>
            </td>
          </tr>
          <tr>
            <td><i class="icon-home"></i>房间别名:</td>
            <td>
              <input name="room_nickname" type="text" class="span2" value="">
            </td>
          </tr>
          <tr>
            <td><i class="icon-home"></i>房间介绍:</td>
            <td>
              <input name="room_introduce" type="text" class="span2" value="">
            </td>
          </tr>
          <tr>
            <td><i class="icon-home"></i>房间面积:</td>
            <td><input name="room_area" type="number" class="span2" value=""></td>
          </tr>
          <?php if($add_type == 'room'): ?><tr>
              <td><i class="icon-home"></i>入住人数:</td>
              <td>
                <select name="person_count">
                    <option>请选择</option>
                  <?php if(is_array($person_count_arr)): $i = 0; $__LIST__ = $person_count_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
              </td>
            </tr><?php endif; ?>
          <tr>
            <td><i class="icon-home"></i><?php echo ($title); ?>租金:</td>
            <td><input name="rent" type="number" class="span2" value=""></td>
          </tr>
          <tr>
            <td><i class="icon-home"></i>服务费:</td>
            <td><input name="room_fee" type="number" class="span2" value=""></td>
          </tr>
          <tr>
            <td><i class="icon-home"></i>房间设施:</td>
            <td>
                <label><input type="checkbox" name="room_parameter[yangtai]" value="1">阳台</label>
                <label><input type="checkbox" name="room_parameter[piaochuang]" value="1">飘窗</label>
                <label><input type="checkbox" name="room_parameter[duwei]" value="1">独卫</label>
                <label><input type="checkbox" name="room_parameter[kongtiao]" value="1">空调</label>
            </td>
          </tr>
        </tbody>
      </table>
      <!-- 房间预览 -->
      <table class="table table-condensed">
        <tbody>
          <tr>
            <td colspan="2"><h4 class="text-center">房间预览</h4></td>
          </tr>
          <tr>
            <td width="130"><i class="icon-file"></i>房间头像图片：</td><td><input type="file" class="span2" name="room_head_images[]" value="" placeholder=""></td>
          </tr>
          <tr>
            <td width="130"><i class="icon-file"></i>户型图：</td><td><input type="file" class="span2" name="house_type_image[]" value="" placeholder=""></td>
          </tr>
          <tr>
            <td width="130"><i class="icon-file"></i>房间图片01：</td><td><input type="file" class="span2" name="room_images_01[]" value="" placeholder=""></td>
          </tr>
          <tr>
            <td width="130"><i class="icon-file"></i>房间图片02：</td><td><input type="file" class="span2" name="room_images_02[]" value="" placeholder=""></td>
          </tr>
          <tr>
            <td width="130"><i class="icon-file"></i>房间图片03：</td><td><input type="file" class="span2" name="room_images_03[]" value="" placeholder=""></td>
          </tr>
          <tr>
            <td width="130"><i class="icon-file"></i>房间图片04：</td><td><input type="file" class="span2" name="room_images_04[]" value="" placeholder=""></td>
          </tr>
        </tbody>
      </table>
      <div class="text-center">
        <input class="btn btn-info " type="submit" name="saveAndAdd" value="保存并继续添加"> 
        <input class="btn btn-success" type="submit" value="保存结束"> 
        <a href="<?php echo U('Admin/Houses/index');?>" class="btn btn-danger">放弃操作</a>
      </div>
    </form>
  </div>
</div>