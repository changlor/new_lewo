<?php if (!defined('THINK_PATH')) exit();?><!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a> <a>帐号管理</a> <a>帐号列表</a> <a class="current">添加帐号</a></div>
  </div>
<!--End-breadcrumbs-->
<div class="container-fluid">
    <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>添加帐号</h5>
        </div>
        <div class="widget-content nopadding">
          <form action="<?php echo U('Admin/Steward/add_account');?>" method="post" class="form-horizontal">
            <div class="control-group">
              <label class="control-label">帐号 :</label>
              <div class="controls">
                <input name="username" type="text" class="span3" placeholder="帐号" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">电话 :</label>
              <div class="controls">
                <input name="mobile" type="text" class="span3" placeholder="电话" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">类型 :</label>
              <div class="controls">
                <select name="admin_type">
                  <option>请选择</option>
                  <?php if(is_array($admin_type_arr)): $i = 0; $__LIST__ = $admin_type_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">昵称 :</label>
              <div class="controls">
                <input name="nickname" type="text" class="span3" placeholder="昵称" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">密码 :</label>
              <div class="controls">
                <input name="password" type="password"  class="span3" placeholder="密码"  />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">确认密码 :</label>
              <div class="controls">
                <input name="repassword" type="password"  class="span3" placeholder="确认密码"  />
              </div>
            </div>
            <div class="form-actions">
              <button type="submit" class="btn btn-success">添加</button>
            </div>
          </form>
        </div>
      </div>
  </div>
</div>
<!--main-container-part-->