<?php if (!defined('THINK_PATH')) exit();?><!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a></div>
  </div>
<!--End-breadcrumbs-->
<!--info-->
  <div class="container-fluid">
    <div class="quick-actions_homepage">
      <ul class="quick-actions">
        <li><a href="<?php echo U('Admin/Tool/php_v');?>" class="btn btn-info">查看php版本</a><label>php_v</label></li>
        <li><a href="<?php echo U('Admin/Tool/check_is_two_account');?>" class="btn btn-info">检测帐号是否存在两次</a><label>check_is_two_account</label></li>
        <li><a href="<?php echo U('Admin/Tool/update_zhouqi');?>" class="btn btn-info">修改缴费周期</a><label>update_zhouqi</label></li>
        <li><a href="<?php echo U('Admin/Tool/check_room_is_in');?>" class="btn btn-info">遍历房间，获取相应的正常合同，如没有正常合同则是空房</a><label>check_room_is_in</label></li>
        <li><a href="<?php echo U('Admin/Tool/create_pay_data');?>" class="btn btn-info">生成pay表中关联的数据</a><label>create_pay_data</label></li>
        <li><a href="<?php echo U('Admin/Tool/update_pro_id');?>" class="btn btn-info">将order_no 移植到 pro_id</a><label>update_pro_id</label></li>
        <li><a href="<?php echo U('Admin/Tool/update_charge_sdq');?>" class="btn btn-info">遍历日常账单，修改水电气数据</a><label>update_charge_sdq</label></li>
        <li><a href="<?php echo U('Admin/Tool/check_ht_is_in_pay');?>" class="btn btn-info">判断是否有的合同表中没有在pay表中的</a><label>check_ht_is_in_pay</label></li>
        <li><a href="<?php echo U('Admin/Tool/update_houses_is_create_bill');?>" class="btn btn-info">判断是否有则生成的修改房屋为已生成</a><label>update_houses_is_create_bill</label></li>
       
      </ul>
    </div>
<!--End-info boxes-->    
  </div>
</div>

<!--end-main-container-part-->