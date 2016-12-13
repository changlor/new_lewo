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
            <h5>修改账单</h5>
          </div>
          <div class="widget-content nopadding">
            <form action="<?php echo U('Admin/Pay/edit_pay');?>" method="post" class="form-horizontal">
              <div class="control-group">
                <label class="control-label">请选择支付状态</label>
                <div class="controls">
                  <select name="pay_status">
                    <option value="0" <?php if($pay_info["pay_status"] != 1): ?>selected="selected"<?php endif; ?>>未支付</option>
                    <option value="1" <?php if($pay_info["pay_status"] == 1): ?>selected="selected"<?php endif; ?>>已支付</option>
                  </select>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">最迟缴费日</label>
                <div class="controls" >
                  <div data-date="<?php echo ($pay_info["last_date"]); ?>" data-date-format="yyyy-mm-dd" class="input-append date datepicker">
                    <input name="last_date" <?php if($pay_info['last_date'] != 0): ?>value="<?php echo ($pay_info['last_date']); ?>"<?php endif; ?> type="text" data-date-format="yyyy-mm-dd" class="span11" >
                    <span class="add-on"><i class="icon-th"></i></span> </div>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">应缴费日</label>
                <div class="controls" >
                  <div data-date="<?php echo ($pay_info["should_date"]); ?>" data-date-format="yyyy-mm-dd" class="input-append date datepicker">
                    <input name="should_date" <?php if($pay_info['should_date'] != 0): ?>value="<?php echo ($pay_info['should_date']); ?>"<?php endif; ?> type="text" data-date-format="yyyy-mm-dd" class="span11" >
                    <span class="add-on"><i class="icon-th"></i></span> </div>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">账单描述:</label>
                <div class="controls">
                  <input type="text" name="bill_des" value="<?php echo ($pay_info['bill_des']); ?>" class="span11"/>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">添加日志</label>
                <div class="controls">
                  <textarea name="modify_log" rows="5" style="width:400px;"></textarea>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">租客电话:</label>
                <div class="controls">
                  <input type="text" value="<?php echo ($pay_info['mobile']); ?>" class="span11"/>
                </div>
              </div>
                <div class="control-group" id="pay_type" <?php if($pay_info['pay_status'] != 1): ?>style="display: none"<?php endif; ?> >
                  <label class="control-label">支付方式</label>
                  <div class="controls">
                    <select name="pay_type">
                      <option value="0">请选择</option>
                      <?php if(is_array($pay_type)): $i = 0; $__LIST__ = $pay_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($key != '1') AND ($key != '3') ): ?><option value="<?php echo ($key); ?>" <?php if($pay_info['pay_type'] == $key): ?>selected<?php endif; ?>><?php echo ($vo); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                  </div>
                </div>
              <div class="control-group" id="pay_money" <?php if($pay_info['pay_status'] != 1): ?>style="display: none"<?php endif; ?>>
                <label class="control-label">支付金额:</label>
                <div class="controls">
                  <input name="pay_money" type="text" data-money="<?php echo ($pay_info['price']); ?>" <?php if($pay_info['pay_status'] == 1): ?>value="<?php echo ($pay_info['pay_money']); ?>" <?php else: ?> value="0"<?php endif; ?> class="span11"/>
                </div>
              </div>
              <div class="control-group" id="pay_time" <?php if($pay_info['pay_status'] != 1): ?>style="display: none"<?php endif; ?> >
                <label class="control-label">支付时间</label>
                <div class="controls" >
                  <div data-date="<?php echo ($time); ?>" data-date-format="yyyy-mm-dd" class="input-append date datepicker">
                    <input name="pay_time" data-time="<?php echo ($time); ?>" <?php if($pay_info['pay_time'] != 0): ?>value="<?php echo ($pay_info['pay_time']); ?>"<?php else: ?>value="0000-00-00 00:00:00"<?php endif; ?> type="text" data-date-format="yyyy-mm-dd" class="span11" >
                    <span class="add-on"><i class="icon-th"></i></span> </div>
                </div>
              </div>   
              <div class="control-group">
                <label class="control-label">日志</label>
                <div class="controls">
                  <?php echo ($pay_info['modify_log']); ?>
                </div>
              </div> 
              <div class="form-actions">
                <button type="submit" class="btn btn-success">保存</button>
                <input type="hidden" name="pro_id" value="<?php echo ($pro_id); ?>">
              </div>
            </form>
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
      $('input[name="pay_money"]').val( $('input[name="pay_money"]').eq(0).attr('data-money') );

      $('#pay_time').show();
      $('input[name="pay_time"]').val( $('input[name="pay_time"]').eq(0).attr('data-time') );
    } else {
      $('#pay_type').hide();
      $('#pay_money').hide();

      $('input[name="pay_money"]').val( 0 );
      $('#pay_time').hide();
      $('input[name="pay_time"]').val( 0 );
    }
  })

 })
</script>