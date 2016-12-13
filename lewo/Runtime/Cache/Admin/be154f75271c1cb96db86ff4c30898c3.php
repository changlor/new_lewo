<?php if (!defined('THINK_PATH')) exit();?><!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a> <a>房源管理</a> <a>房源列表</a> <a class="current"><?php echo ($house_code); ?>:<?php echo ($year); ?>年<?php echo ($month); ?>月水电气</a></div>
  </div>
<!--End-breadcrumbs-->
<div class="container-fluid">
    <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5><?php echo ($year); ?>年<?php echo ($month); ?>月水电气</h5>
           <a href="<?php echo U('Admin/Houses/bill_list',array('house_id'=>$house_id));?>" class="label label-info pull-left">返回</a>
        </div>
        <div class="widget-content nopadding">
          <form action="" method="post" class="form-horizontal form-inline">
            <div class="control-group">
              <label class="control-label">状态：</label>
              <div class="controls">
                <?php if($ammeter_house["status"] == 1): ?><label class="label label-success">已录入</label>
                  <?php else: ?>
                  <label class="label label-important">未录入</label><?php endif; ?>
                <select name="status">
                  <option value="0">未录入</option>
                  <option value="1" <?php if($ammeter_house['status'] == 1): ?>selected<?php endif; ?>>已录入</option>
                </select>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">总水：</label>
              <div class="controls">
                <input name="zs" type="text" class="span3" value="<?php echo ($ammeter_house["total_water"]); ?>" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">总电 :</label>
              <div class="controls">
                <input name="zd" type="text" class="span3" value="<?php echo ($ammeter_house["total_energy"]); ?>" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">总气 :</label>
              <div class="controls">
                <input name="zq" type="text" class="span3" value="<?php echo ($ammeter_house["total_gas"]); ?>" />
              </div>
            </div>
            <?php if($house_info['type'] == 1): if(is_array($ammeter_room)): $i = 0; $__LIST__ = $ammeter_room;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="control-group">
                <label class="control-label"><?php echo ($vo["room_code"]); ?>房间电表 :</label>
                <div class="controls">
                  <input name="roomd[<?php echo ($vo["id"]); ?>]" type="text" class="span3" value="<?php echo ($vo["room_energy"]); ?>" />
                </div>
              </div><?php endforeach; endif; else: echo "" ;endif; endif; ?>
            <div class="form-actions">
              <input type="hidden" name="year" value="<?php echo ($year); ?>">
              <input type="hidden" name="month" value="<?php echo ($month); ?>">
              <input type="hidden" name="house_id" value="<?php echo ($house_id); ?>">
              <input type="hidden" name="ammeter_house_id" value="<?php echo ($ammeter_house["id"]); ?>">
              <button type="submit" class="btn btn-success">修改</button>
            </div>
          </form>
        </div>
      </div>
  </div>
</div>
<!--main-container-part-->