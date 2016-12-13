<?php if (!defined('THINK_PATH')) exit();?><!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a> <a>丁盯门锁管理</a> <a class="current">房间管理</a></div>
  </div>
<!--End-breadcrumbs-->
<div class="container-fluid">
    <div class="widget-box">
      <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
        <h5>房间管理</h5>
        <a href="<?php echo U('Admin/dding/add_room');?>" class="label label-success"><i class="icon-plus"></i>添加房间</a>
      </div>
      <div class="widget-content nopadding">
        <table class="table table-bordered data-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>房间编码</th>
              <th>uuid</th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody>
            <?php if(is_array($room_list)): $i = 0; $__LIST__ = $room_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
              <td><?php echo ($vo["id"]); ?></td>
              <td><?php echo ($vo["room_code"]); ?></td>
              <td><?php echo ($vo["uuid"]); ?></td>
              <td>
                <a href="<?php echo U('Admin/dding/update_room',array('id'=>$vo['id']));?>" class="btn btn-info btn-mini">修改</a>
                <a href="<?php echo U('Admin/dding/delete_room',array('id'=>$vo['id']));?>" class="btn btn-danger btn-mini" onclick="if(!confirm('是否删除')) return false;">删除</a>
              </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!--main-container-part-->