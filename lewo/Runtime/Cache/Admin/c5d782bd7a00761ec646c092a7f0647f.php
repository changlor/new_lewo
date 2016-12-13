<?php if (!defined('THINK_PATH')) exit();?><!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a> <a>房源管理</a> <a class="current">小区列表</a></div>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
      <div class="widget-box">
      <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
        <h5>小区列表</h5>
        <a href="<?php echo U('Admin/Houses/add_area');?>" class="label label-success"><i class="icon-plus"></i>添加小区</a>
      </div>
      <div class="widget-content nopadding">
        <table class="table table-bordered data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>小区名字</th>
                <!-- <th>电费单价</th> -->
                <th>水费单价</th>
                <th>气费单价</th>
                <th>阶梯电费</th>
                <th>燃气垃圾费</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              <?php if(is_array($areaList)): $i = 0; $__LIST__ = $areaList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td><?php echo ($vo["id"]); ?></td>
                <td><?php echo ($vo["area_name"]); ?></td>
                <!-- <td><?php echo ($vo["energy_unit"]); ?></td> -->
                <td><?php echo ($vo["water_unit"]); ?></td>
                <td><?php echo ($vo["gas_unit"]); ?></td>
                <td><?php echo ($vo["energy_stair"]); ?></td>
                <td><?php echo ($vo["rubbish_fee"]); ?></td>
                <td>
                  <a href="<?php echo U('Admin/Houses/index',array('area_id'=>$vo['id']));?>" class="btn btn-info btn-mini">查看小区房源</a>
                  <a href="<?php echo U('Admin/Houses/update_area',array('area_id'=>$vo['id']));?>" class="btn btn-info btn-mini">修改</a>
                  <a href="<?php echo U('Admin/Houses/delete_area',array('area_id'=>$vo['id']));?>" class="btn btn-danger btn-danger btn-mini" onclick="if(!confirm('是否删除')) return false;">删除</a>
                </td>
              </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
          </table>
      </div>
    </div>
  </div>
</div>
<!--main-container-part-->