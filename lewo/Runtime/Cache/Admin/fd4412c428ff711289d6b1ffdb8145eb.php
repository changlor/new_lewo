<?php if (!defined('THINK_PATH')) exit();?><!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a> <a>短信管理</a> <a class="current">操作日志</a></div>
  </div>
<!--End-breadcrumbs-->
<div class="container-fluid">
    <div class="widget-box">
      <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
        <h5>操作日志</h5>
      </div>
      <div class="widget-content nopadding">
        <table class="table table-bordered data-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>姓名</th>
              <th>电话</th>
              <th>房间</th>
              <th>发送信息</th>
              <th>发送回执</th>
              <th>发送时间</th>
            </tr>
          </thead>
          <tbody>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
              <td><a href="detail-house.html"><?php echo ($vo["id"]); ?></a></td>
              <td><?php echo ($vo["realname"]); ?></td>
              <td><?php echo ($vo["mobile"]); ?></td>
              <td><?php echo ($vo["room_code"]); ?></td>
              <td><?php echo ($vo["message"]); ?></td>
              <td><?php echo ($vo["result"]); ?></td>
              <td><?php echo ($vo["send_time"]); ?></td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!--main-container-part-->