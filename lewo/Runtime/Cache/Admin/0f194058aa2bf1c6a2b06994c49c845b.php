<?php if (!defined('THINK_PATH')) exit();?><!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a> <a>房源管理</a> <a>房源列表</a> <a class="current">房间/床位列表</a></div>
  </div>
<!--End-breadcrumbs-->
<div class="container-fluid">
    <div class="widget-box">
      <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
        <h5>房屋编号:<?php echo ($house_code); ?></h5>
        <a href="<?php echo U('Admin/Houses/index',array('area_id'=>$_SESSION['area_id']));?>" class="label label-info pull-left">返回</a>
        <a href="<?php echo U('Admin/Houses/add_room',array('add_type'=>'room','house_code'=>$house_code));?>" class="label label-success"><i class="icon-plus"></i>增加房间</a>
        <a href="<?php echo U('Admin/Houses/add_room',array('add_type'=>'bed','house_code'=>$house_code));?>" class="label label-success"><i class="icon-plus"></i>增加床位</a>
      </div>
      <div class="widget-content nopadding">
        <table class="table table-bordered data-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>出租类型</th>
              <th>房间编号</th>
              <th>房间序列</th>
              <th>床位编号</th>
              <th>租金</th>
              <th>合同租金</th>
              <th>状态</th>
              <th>租客</th>
              <th>电话</th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody>
            <?php if(is_array($room_list)): $i = 0; $__LIST__ = $room_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
              <td><?php echo ($vo["id"]); ?></td>
              <td><?php echo ($vo["rent_out_type_name"]); ?></td>
              <td><?php echo ($vo["room_code"]); ?></td>
              <td><?php echo ($vo["room_sort"]); ?></td>
              <td><?php echo ($vo["bed_code"]); ?></td>
              <td><?php echo ($vo["rent"]); ?></td>
              <td><?php echo ($vo["contract_rent"]); ?></td>
              <td>
                <?php switch($vo["status"]): case "0": ?><span class="label label-important">未租</span><?php break;?>
                  <?php case "1": ?><span class="label label-warning">缴定</span><?php break;?>
                  <?php case "2": ?><span class="label label-success">已租</span><?php break; endswitch;?>
              </td>
              <td><?php echo ($vo["realname"]); ?></td>
              <td><?php echo ($vo["mobile"]); ?></td>
              <td>
                <?php if($vo['account_id'] != 0 AND $vo['account_id'] != null ): ?><a href="<?php echo U('Admin/Tenant/contract_info',array('account_id'=>$vo['account_id'],'room_id'=>$vo['id']));?>" class="btn btn-info btn-mini" target="_blank">合同</a><?php endif; ?>
                <a href="<?php echo U('Admin/Houses/update_room',array('id'=>$vo['id'],'house_code'=>$house_code,'rent_out_type'=>$vo['rent_out_type']));?>" class="btn btn-warning btn-mini">修改</a>
                <?php if($vo['status'] != 0): ?><a href="<?php echo U('Admin/Houses/be_empty_room',array('id'=>$vo['id'],'account_id'=>$vo['account_id'],'status'=>$vo['status']));?>" class="btn btn-danger btn-mini" onclick="if(!confirm('确认设置为空房?此操作会将房间设置为未租')) return false;">设置空房</a>
                <?php else: ?>
                  <a href="<?php echo U('Admin/Houses/restore_room',array('id'=>$vo['id']));?>" class="btn btn-danger btn-mini">还原租客</a><?php endif; ?>
                <a href="<?php echo U('Admin/Houses/delete_room',array('id'=>$vo[id]));?>" class="btn btn-danger btn-mini" onclick="if(!confirm('确认删除?')) return false;">删除</a>
              </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!--main-container-part-->