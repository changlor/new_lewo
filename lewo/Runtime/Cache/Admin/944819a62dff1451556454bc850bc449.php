<?php if (!defined('THINK_PATH')) exit();?><!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a> <a>丁盯门锁管理</a> <a>房间列表</a> <a class="current">添加房间</a></div>
  </div>
<!--End-breadcrumbs-->
<div class="container-fluid">
    <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>添加房间</h5>
          <a href="javascript:window.history.back(-1)" class="label label-info pull-left">返回</a>
        </div>
        <div class="widget-content nopadding">
          <form action="" method="post" class="form-horizontal form-inline">
            <div class="control-group">
              <label class="control-label">房间编码 :</label>
              <div class="controls">
                <input name="room_code" type="text" class="span3" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">uuid :</label>
              <div class="controls">
                <input name="uuid" type="text" class="span3" />
              </div>
            </div>
            <div class="control-group">
            
            <div class="form-actions">
              <button type="submit" class="btn btn-success">添加</button>
            </div>
          </form>
        </div>
      </div>
  </div>
</div>
<!--main-container-part-->