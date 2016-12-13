<?php if (!defined('THINK_PATH')) exit();?><!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a> <a>工作台</a> <a class="current">待办列表</a></div>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
    <div class="widget-box">
      <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
        <h5>待办列表</h5>
      </div>
      <div class="widget-content nopadding">
        <form action="#">
          <table class="table">
            <tbody>
              <tr>
                <td>
                  <a class="date badge badge-important">退房(<?php echo ($TFcount); ?>)</a>
                  <a class="date badge badge-warning">转租(<?php echo ($ZFcount); ?>)</a>
                  <a class="date badge badge-info">换房(<?php echo ($HFcount); ?>)</a>
                  <a href="<?php echo U('Admin/Task/money_back',array('back_type'=>1,'is_finish'=>1));?>" class="btn btn-success icon-reorder">例行打款(<?php echo ($LXDKcount); ?>)</a>
                  <a href="<?php echo U('Admin/Task/money_back',array('back_type'=>2,'is_finish'=>1));?>" class="btn btn-info icon-reorder">例行打款到余额(<?php echo ($LXDKYEcount); ?>)</a>
                  <a id="refesh_schedule_count" class="icon-refresh btn btn-success ">刷新</a>
                </td>
              </tr>
            </tbody>
          </table>
        </form>
        <table class="table table-bordered data-table">
          <thead>
            <tr>
              <th>类型</th>
              <th>房屋</th>
              <th>房间</th>
              <th>床位</th>
              <th>租客</th>
              <th>创建时间</th>
            </tr>
          </thead>
          <tbody>
          <?php if(is_array($schedule_list)): $i = 0; $__LIST__ = $schedule_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
              <td><?php echo ($vo["schedule_type_name"]); ?></td>
              <td><?php echo ($vo["room_info"]["house_code"]); ?></td>
              <td><?php echo ($vo["room_info"]["room_code"]); ?></td>
              <td><?php echo ($vo["room_info"]["bed_code"]); ?></td>
              <td><?php echo ($vo["realname"]); ?></td>
              <td><?php echo ($vo["create_time"]); ?></td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!--main-container-part-->