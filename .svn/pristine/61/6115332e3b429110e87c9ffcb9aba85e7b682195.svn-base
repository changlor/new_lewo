<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
<title>乐窝公寓管理系统</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="/Public/admin/css/bootstrap.min.css" />
<link rel="stylesheet" href="/Public/admin/css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="/Public/admin/css/matrix-style.css" />
<link rel="stylesheet" href="/Public/admin/css/matrix-media.css" />
<link href="/Public/admin/font-awesome/css/font-awesome.css" rel="stylesheet" />

</head>
<body>
<!--main-container-part-->
<div>
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
      <h5>退房确认</h5>
      <a href="javascript:window.history.back(-1);" class="label label-info pull-left">返回</a>
    </div>
    <form action="<?php echo U('Admin/Task/confirm_checkout_pay');?>" method="post">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>类型</th>
          <th>房间编号</th>
          <th>床位编号</th>
          <th>小区楼层</th>
          <th>承租人</th>
          <th>电话</th>
          <th>租期开始</th>
          <th>租期结束</th>
          <th>房租到期</th>
          <th>最迟缴费</th>
          <th>倒计时</th>
          <th>缴费周期</th>
          <th>批次</th>
          <th>押金</th>
          <th>房租</th>
          <th>服务费</th>
          <th>水电气</th>
          <th>物管</th>
          <th>欠费</th>
          <th>实收</th>
          <th>支付时间</th>
          <th>支付方式</th>
          <th>支付状态</th>
          <!-- <th>操作</th> -->
        </tr>
      </thead>
      <tbody>
         <?php if(is_array($notpaylist)): $i = 0; $__LIST__ = $notpaylist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
              <td>
                <?php echo ($vo["type_name"]); ?>
                <input type="hidden" name="id[]" value="<?php echo ($vo["id"]); ?>">
              </td>
              <td><?php echo ($vo["room_code"]); ?></td>
              <td><?php echo ($vo["bed_code"]); ?></td>
              <td><?php echo ($vo["area_name"]); ?></td>
              <td><?php echo ($vo["realname"]); ?></td>
              <td><?php echo ($vo["mobile"]); ?></td>
              <td><?php echo ($vo["ht_start_date"]); ?></td>
              <td><?php echo ($vo["ht_end_date"]); ?></td>
              <td><?php echo ($vo["late_pay_date"]); ?></td>
              <td><?php echo ($vo["should_pay_date"]); ?></td>
              <td>
                <?php if($vo['count_down_days'] <= 0): ?><label class="label label-important"><?php echo ($vo["count_down_days"]); ?></label>
                  <?php else: ?>
                  <label class="label label-success"><?php echo ($vo["count_down_days"]); ?></label><?php endif; ?>
              </td>
              <td><?php echo ($vo["period"]); ?></td>
              <td><?php echo ($vo["input_year"]); ?>年<?php echo ($vo["input_month"]); ?>月</td>
              <td><?php echo ($vo["deposit"]); ?></td>
              <td><?php echo ($vo["rent_fee"]); ?></td>
              <td><?php echo ($vo["service_fee"]); ?></td>
              <td><?php echo ($vo["SDQtotal"]); ?></td>
              <td><?php echo ($vo["wgfee_unit"]); ?></td>
              <td><?php echo ($vo["wx_fee"]); ?></td>
              <td><?php echo ($vo["total_fee"]); ?></td>
              <td><?php echo ($vo["pay_time"]); ?></td>
              <td><?php echo ($vo["pay_type_name"]); ?></td>
              <td>
                <?php if($vo["pay_status"] == 1): ?><span class="label label-success">已付</span>
                  <?php else: ?>
                  <span class="label label-important">未付</span><?php endif; ?>
              </td>
              <!-- <td>
                <a href="<?php echo U('Admin/Pay/edit_pay',array('id'=>$vo['id']));?>" class="btn btn-success btn-mini">修改</a>
                <a href="#" class="btn btn-danger btn-mini">删除</a>
              </td> -->
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        
        <!-- no-repeat -->
        <tr>
          <td colspan="24">
            <h3>总金额：￥<?php echo ($sum_total_fee); ?></h3>
            <h3>押金抵扣余额：￥<?php echo ($residue_deposit); ?></h3>
            <span class="label label-important">因押金抵扣￥<?php echo ($sum_total_fee); ?>，抵扣后金额剩余￥<?php echo ($residue_deposit); ?>。</span>
          </td>          
        </tr>
        <tr>
          <td colspan="24" style="text-align:center">
          <input type="hidden" name="schedule_id" value="<?php echo ($schedule_id); ?>">
          <input type="submit" class="btn btn-success" value="确认">
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<!--main-container-part-->

<!--Footer-part-->
<div class="row-fluid">
  <div id="footer" class="span12"> 2016 &copy; 广州百城网络科技有限公司 </div>
</div>
<!--end-Footer-part-->
<script src="/Public/admin/js/jquery.min.js"></script> 
<script src="/Public/admin/js/bootstrap.min.js"></script> 
<script src="/Public/admin/js/jquery.uniform.js"></script> 
<script src="/Public/admin/js/select2.min.js"></script> 
<script src="/Public/admin/js/jquery.ui.custom.js"></script>
<script src="/Public/admin/js/jquery.dataTables.min.js"></script>  
<script src="/Public/admin/js/matrix.js"></script>
<script src="/Public/admin/js/matrix.tables.js"></script>
</body>
</html>