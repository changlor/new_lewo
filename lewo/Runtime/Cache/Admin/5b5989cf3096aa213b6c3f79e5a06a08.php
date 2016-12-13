<?php if (!defined('THINK_PATH')) exit();?><!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a> <a>租客帐号管理</a> <a>帐号列表</a> <a class="current">租客信息</a></div>
  </div>
<!--End-breadcrumbs-->
<div class="container-fluid">
    <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>租客信息</h5>
        </div>
        <div class="widget-content nopadding">
          <form action="" method="post" class="form-horizontal form-inline">
            <div class="control-group">
              <label class="control-label">姓名 :</label>
              <div class="controls">
                <input name="realname" type="text" class="span3" placeholder="真实姓名" value="<?php echo ($account_info["realname"]); ?>" />
              </div>
            </div>
             <div class="control-group">
              <label class="control-label">昵称 :</label>
              <div class="controls">
                <input name="nickname" type="text" class="span3" placeholder="昵称" value="<?php echo ($account_info["nickname"]); ?>" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">电话 :</label>
              <div class="controls">
                <input name="mobile" type="text" class="span3" placeholder="电话" value="<?php echo ($account_info["mobile"]); ?>" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">邮箱 :</label>
              <div class="controls">
                <input name="email" type="text" class="span3" placeholder="邮箱" value="<?php echo ($account_info["email"]); ?>" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">性别 :</label>
              <div class="controls">
                <label><input type="radio" name="sex" value="1" <?php if($account_info["sex"] == 1): ?>checked<?php endif; ?> >男</label>
                <label><input type="radio" name="sex" value="2" <?php if($account_info["sex"] == 2): ?>checked<?php endif; ?> >女</label>

              </div>
            </div>
            <div class="control-group">
              <label class="control-label">身份证 :</label>
              <div class="controls">
                <input name="card_no" type="text" class="span3" placeholder="身份证" value="<?php echo ($account_info["card_no"]); ?>"/>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">生日 :</label>
              <div class="controls">
                <input name="birthday" type="text" class="span3" value="<?php echo ($account_info["birthday"]); ?>"/>
              </div>
            </div>
            <div class="form-actions">
            <input type="hidden" name="id" value="<?php echo ($account_info["id"]); ?>">
              <button type="submit" class="btn btn-success">修改</button>
            </div>
          </form>
        </div>
      </div>
  </div>
</div>
<!--main-container-part-->