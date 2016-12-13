<?php if (!defined('THINK_PATH')) exit();?><!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a> <a>后台帐号管理</a> <a class="current">帐号列表</a></div>
  </div>
<!--End-breadcrumbs-->
<div class="container-fluid">
    <div class="widget-box">
      <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
        <h5>帐号列表</h5>
        <a href="<?php echo U('Admin/Steward/add_account');?>" class="label label-success"><i class="icon-plus"></i>添加帐号</a>
      </div>
<!--       <form action="#" method="post" class="form-inline">
  <table class="table-condensed">
    <tbody>
      <tr>
        <td width="30">区域:</td>
        <td>
          <select name="" id="" style="width:80px">
            <option selected="selected">请选择</option>
            <option value="1">1</option>
            <option value="1">1</option>
            <option value="1">1</option>
          </select>
        </td>
      </tr>
    </tbody>
  </table>   
</form> -->
      <div class="widget-content nopadding">
        <table class="table table-bordered data-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>帐号</th>
              <th>类型</th>
              <th>昵称</th>
              <th>电话</th>
              <th>创建时间</th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody>
            <?php if(is_array($account)): $i = 0; $__LIST__ = $account;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
              <td><a href="detail-house.html"><?php echo ($vo["id"]); ?></a></td>
              <td><?php echo ($vo["username"]); ?></td>
              <td><?php echo ($vo["admin_type_name"]); ?></td>
              <td><?php echo ($vo["nickname"]); ?></td>
              <td><?php echo ($vo["mobile"]); ?></td>
              <td><?php echo ($vo["create_time"]); ?></td>
              <td>
                <a href="<?php echo U('Admin/Steward/update_account',array('id'=>$vo['id']));?>" class="btn btn-info btn-mini">修改密码</a>
                <a href="<?php echo U('Admin/Steward/delete_account',array('id'=>$vo['id']));?>" class="btn btn-danger btn-mini" onclick="if (!confirm('确认删除?')) return false; ">删除</a>
              </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!--main-container-part-->