<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
<title>乐窝公寓管理系统</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="/Public/admin/css/bootstrap.min.css" />
<link rel="stylesheet" href="/Public/admin/css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="/Public/admin/css/colorpicker.css" />
<link rel="stylesheet" href="/Public/admin/css/datepicker.css" />
<link rel="stylesheet" href="/Public/admin/css/uniform.css" />
<link rel="stylesheet" href="/Public/admin/css/select2.css" />
<link rel="stylesheet" href="/Public/admin/css/matrix-style.css" />
<link rel="stylesheet" href="/Public/admin/css/matrix-media.css" />
<link href="/Public/admin/font-awesome/css/font-awesome.css" rel="stylesheet" />

</head>
<body>
<!--main-container-part-->
<div>
  <div class="widget-box">
  <a href="javascript:window.history.back(-1)" class="btn btn-success">返回</a>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span6"> 
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>查看账单</h5>
          </div>
          <div class="widget-content nopadding">
              <div class="control-group">
                <label class="control-label">日志</label>
                <div class="controls">
                  <?php echo ($pay_info['modify_log']); ?>
                </div>
              </div> 
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!--main-container-part-->

<!--Footer-part-->
<div class="row-fluid">
  <div id="footer" class="span12"> 2016 &copy; 广州百城网络科技有限公司 </div>
</div>
<!--end-Footer-part-->
<script src="/Public/admin/js/jquery.min.js"></script> 
<script src="/Public/admin/js/jquery.ui.custom.js"></script> 
<script src="/Public/admin/js/bootstrap.min.js"></script> 
<script src="/Public/admin/js/bootstrap-colorpicker.js"></script> 
<script src="/Public/admin/js/bootstrap-datepicker.js"></script> 
<!-- <script src="/Public/admin/js/jquery.toggle.buttons.html"></script>  -->
<script src="/Public/admin/js/masked.js"></script> 
<script src="/Public/admin/js/jquery.uniform.js"></script> 
<script src="/Public/admin/js/select2.min.js"></script> 
<script src="/Public/admin/js/matrix.js"></script> 
<script src="/Public/admin/js/matrix.form_common.js"></script> 
<script src="/Public/admin/js/wysihtml5-0.3.0.js"></script> 
<script src="/Public/admin/js/jquery.peity.min.js"></script> 
<script src="/Public/admin/js/bootstrap-wysihtml5.js"></script> 
</body>
</html>
<script type="text/javascript">
 $(window).load(function(){

  $("select[name='pay_status']").change(function(){
    if ( '1' == $(this).val() ) {
      $('#pay_type').show();
      $('#pay_money').show();
      $('#pay_time').show();
    } else {
      $('#pay_type').hide();
      $('#pay_money').hide();
      $('#pay_time').hide();
    }
  })

 })
</script>