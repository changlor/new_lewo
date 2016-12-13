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
      <h5>修改房屋信息</h5>
      <a href="<?php echo U('Admin/Houses/index');?>" class="label label-info pull-left">返回</a>
    </div>
    <form action="<?php echo U('Admin/Houses/update_house');?>" method="post" enctype="multipart/form-data" class="form-horizonta form-inline" onsubmit="return checkAddHouse();">
      <!-- 房屋信息 -->
      <table class="table table-condensed">
        <tbody>
          <tr>
            <td colspan="2"><h4 class="text-center">房屋信息</h4></td>
          </tr>
          <tr>
            <td width="130"><i class="icon-home"></i>房屋编号:</td>
            <td><input name="house_code" type="text" class="span2" value="<?php echo ($house_code); ?>" readonly></td>
          </tr>
          <tr>
            <td><i class="icon-home"></i>房屋类型</td>
            <td>
              <label><input name="type" type="radio" name="type" value="1" <?php if($type == 1): ?>checked<?php endif; ?> >合租按间</label>
              <label><input name="type" type="radio" name="type" value="2" <?php if($type == 2): ?>checked<?php endif; ?>>合租按床</label>
              <!-- <label><input name="type" type="radio" name="type" value="3">整租</label> -->
            </td>
          </tr>
          <tr>
            <td><i class="icon-home"></i>所在小区:</td>
            <td>
              <select name="area_id">
                  <option>请选择</option>
                <?php if(is_array($areaList)): $i = 0; $__LIST__ = $areaList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($area_id == $vo['id']): ?>selected<?php endif; ?>><?php echo ($vo["area_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
              </select>
            </td>
          </tr>
          <tr>
            <td><i class="icon-home"></i>栋-层-房号:</td>
            <td>
              <input name="building" type="text" class="span1" value="<?php echo ($building); ?>" placeholder="栋">-
              <input name="floor" type="text" class="span1" value="<?php echo ($floor); ?>" placeholder="层">-
              <input name="door_no" type="text" class="span1" value="<?php echo ($door_no); ?>" placeholder="房号">
            </td>
          </tr>
          <tr>
            <td><i class="icon-home"></i>所在地区:</td>
            <td>
              <select name="region_id">
                  <option>请选择</option>
                <?php if(is_array($region_list)): $i = 0; $__LIST__ = $region_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($region_id == $vo['id']): ?>selected<?php endif; ?> ><?php echo ($vo["region_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
              </select>
            </td>
          </tr>
          <tr>
            <td><i class="icon-home"></i>物业费:</td>
            <td><input name="fee" type="number" class="span2" value="<?php echo ($fee); ?>"></td>
          </tr>
          <tr>
            <td><i class="icon-home"></i>初始化水表:</td>
            <td><input name="init_water" type="number" class="span2" value="<?php echo ($init_water); ?>"></td>
          </tr>
          <tr>
            <td><i class="icon-home"></i>初始化电表:</td>
            <td><input name="init_energy" type="number" class="span2" value="<?php echo ($init_energy); ?>"></td>
          </tr>
          <tr>
            <td><i class="icon-home"></i>初始化气表:</td>
            <td><input name="init_gas" type="number" class="span2" value="<?php echo ($init_gas); ?>"></td>
          </tr>
          <tr>
            <td><i class="icon-home"></i>管家:</td>
            <td>
              <select name="steward_id">
                    <option>请选择</option>
                  <?php if(is_array($steward_list)): $i = 0; $__LIST__ = $steward_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($steward_id == $vo['id']): ?>selected<?php endif; ?>><?php echo ($vo["username"]); ?>-<?php echo ($vo["nickname"]); ?>-<?php echo ($vo["mobile"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
              </select>
            </td>
          </tr>
        </tbody>
      </table>
      <!-- 业主信息 -->
      <table class="table table-condensed">
        <tbody>
          <tr>
            <td colspan="2"><h4 class="text-center">业主信息</h4></td>
          </tr>
          <tr>
            <td width="130"><i class="icon-user"></i>房东姓名：</td><td><input type="text" class="span2" name="house_owner" value="<?php echo ($house_owner); ?>" placeholder=""></td>
          </tr>
          <tr>
            <td><i class="icon-phone"></i>房东手机：</td><td><input type="tel" class="span2" name="house_mobile" value="<?php echo ($house_mobile); ?>" placeholder=""></td>
          </tr>
          <tr>
            <td><i class="icon-time"></i>托管开始日期：</td>
            <td>
              <div  data-date="" class="input-append date datepicker"  data-date-format="yyyy-mm-dd">
                  <input name="start_date" type="text" value="<?php echo ($start_date); ?>" class="span3" >
                  <span class="add-on"><i class="icon-th"></i></span>
              </div>
            </td>
          </tr>
          <tr>
            <td><i class="icon-time"></i>托管结束日期：</td>
            <td>
              <div  data-date="" class="input-append date datepicker" data-date-format="yyyy-mm-dd">
                  <input name="end_date" type="text" value="<?php echo ($end_date); ?>" class="span3" >
                  <span class="add-on"><i class="icon-th"></i></span>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <!-- 周边环境(选填) -->
      <table class="table table-condensed">
        <tbody>
          <tr>
            <td colspan="2"><h4 class="text-center">周边环境(选填)</h4></td>
          </tr>
          <tr>
            <td width="130"><i class="icon-map-marker"></i>周边配套：</td><td><input type="text" class="span2" name="area_description" value="<?php echo ($area_description); ?>" placeholder=""></td>
          </tr>
          <tr>
            <td><i class="icon-heart"></i>房屋特色：</td><td><input type="text" class="span2" name="house_description" value="<?php echo ($house_description); ?>" placeholder=""></td>
          </tr>
          <tr>
            <td><i class="icon-truck"></i>公交路线：</td><td><input type="text" class="span2" name="subway" value="<?php echo ($subway); ?>" placeholder=""></td>
          </tr>
          <tr>
            <td><i class="icon-home"></i>详细地址：</td><td><input type="text" class="span8" name="address" value="<?php echo ($address); ?>" placeholder=""></td>
          </tr>
        </tbody>
      </table>
      <div class="text-center">
        <input type="submit"  class="btn btn-success" value="修改">
      </div>
    </form>
  </div>
</div>

<!--main-container-part-->