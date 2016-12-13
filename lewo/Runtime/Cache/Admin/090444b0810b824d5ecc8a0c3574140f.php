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
<style>
  .input1 {width:30px;}
  .input2 {width:40px;}
  .input3 {width:50px;}
  .input4 {width:60px;}
  .input5 {width:70px;}
</style>
</head>
<body>
<!--main-container-part-->
<div>
<div class="row-fluid">
  <div class="widget-box">
   <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
      <h5>退房办理 &nbsp;&nbsp;房屋：<?php echo ($house_code); ?></h5>
      <a href="<?php echo U('Admin/Task/index');?>" class="label label-info pull-left">返回</a>
      <a class="label label-info pull-left">抄表时间：<?php echo ($schedule_info["create_time"]); ?></a>
      <a class="label label-info pull-left">该月范围：<?php echo ($person_day_area_date); ?></a>
    </div>
    <form action="<?php echo U('Admin/Task/dispose_bill');?>" method="post">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>房屋总水</th>
            <th>房屋总电</th>
            <th>房屋总气</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <?php echo ($ammeter_house['total_energy']); ?>~<?php echo ($schedule_info['zd']); ?>
              <input type="hidden" name="total_energy" value="<?php echo ($schedule_info['zd']); ?>">
              <label class="label label-important"><?php echo ($add_energy); ?>*<?php echo ($energy_unit); ?>=<?php echo ($public_energy_fee); ?></label>
            </td>
            <td>
              <?php echo ($ammeter_house['total_water']); ?>~<?php echo ($schedule_info['zs']); ?>
              <input type="hidden" name="total_water" value="<?php echo ($schedule_info['zs']); ?>">
              <label class="label label-important"><?php echo ($add_water); ?>*<?php echo ($water_unit); ?>=<?php echo ($public_water_fee); ?></label>
            </td>
            <td>
              <?php echo ($ammeter_house['total_gas']); ?>~<?php echo ($schedule_info['zq']); ?>
              <input type="hidden" name="total_gas" value="<?php echo ($schedule_info['zq']); ?>">
              <label class="label label-important"><?php echo ($add_gas); ?>*<?php echo ($gas_unit); ?>=<?php echo ($public_gas_fee); ?></label>
            </td>
          </tr>
          <tr>
            <td colspan="3">房间阶梯算法规则:<label class="label label-important"><?php echo ($energy_stair); ?></label></td>
          </tr>

        </tbody>
      </table>
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>房间编号</th>
            <th>房间电表</th>
            <th>床位编号</th>
            <th>租客</th>
            <th>人日</th>
            <th>个人电费</th>
            <th>公摊电费</th>
            <th>水费</th>
            <th>气费</th>
            <th>欠款</th>
            <th>欠款描述</th>
           <!--  <th>滞纳金</th> -->
            <th>总金额</th>
          </tr>
        </thead>
        <tbody>
          <?php if(is_array($room_list)): $i = 0; $__LIST__ = $room_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo['account_id'] != 0): if($schedule_info['account_id'] == $vo['account_id']): ?><tr>
                <td>
                  <?php echo ($vo["room_code"]); ?>
                  <input type="hidden" name="room_id" value="<?php echo ($vo["id"]); ?>"/>
                  <input type="hidden" name="house_code" value="<?php echo ($vo["house_code"]); ?>"/>
                  <input type="hidden" name="account_id" value="<?php echo ($vo["account_id"]); ?>"/>
                </td>
                <td><?php echo ($vo["end_room_energy"]); ?>~<?php echo ($vo["start_room_energy"]); ?>
                <input type="hidden" name="end_room_energy" value="<?php echo ($vo["end_room_energy"]); ?>">
                <input type="hidden" name="start_room_energy" value="<?php echo ($vo["start_room_energy"]); ?>">
                <input type="hidden" name="room_energy_add" value="<?php echo ($vo["add_room_energy"]); ?>">

                  <?php if($vo['start_room_energy'] < $vo['end_room_energy']): ?><label class="label label-important">电表有误</label><?php endif; ?>
                </td>
                <td><?php echo ($vo["bed_code"]); ?></td>
                <td><?php echo ($vo["realname"]); ?></td>
                <td><input class="span6" type="text" name="person_day" value="<?php echo ($vo["person_day"]); ?>"/></td>
                <td><input class="span6" type="text" name="room_energy_fee" value="<?php echo ($vo["room_energy_fee"]); ?>"/></td>
                <td><input class="span6" type="text" name="energy_fee" value="<?php echo ($vo["energy_fee"]); ?>"/></td>
                <td><input class="span6" type="text" name="water_fee" value="<?php echo ($vo["water_fee"]); ?>"/></td>
                <td><input class="span6" type="text" name="gas_fee" value="<?php echo ($vo["gas_fee"]); ?>"/></td>
                <td><input class="span6" type="text" name="wx_fee" value="<?php echo ($vo["wx_fee"]); ?>"/></td>
                <td><input class="span6" type="text" name="wx_des" value="<?php echo ($vo["wx_des"]); ?>"/></td>
                <!-- <td>￥0</td> -->
                <td><input class="span6" type="text" name="total_fee" value="<?php echo ($vo["total_fee"]); ?>"/></td>
              </tr>
              <?php else: ?>
              <tr>
                <td><?php echo ($vo["room_code"]); ?></td>
                <td><?php echo ($vo["end_room_energy"]); ?>~<?php echo ($vo["start_room_energy"]); if($vo['start_room_energy'] < $vo['end_room_energy']): ?><label class="label label-important">电表有误</label><?php endif; ?></td>
                <td><?php echo ($vo["bed_code"]); ?></td>
                <td><?php echo ($vo["realname"]); ?></td>
                <td><?php echo ($vo["person_day"]); ?></td>
                <td><?php echo ($vo["room_energy_fee"]); ?></td>
                <td><?php echo ($vo["energy_fee"]); ?></td>
                <td><?php echo ($vo["water_fee"]); ?></td>
                <td><?php echo ($vo["gas_fee"]); ?></td>
                <td><?php echo ($vo["wx_fee"]); ?></td>
                <td><?php echo ($vo["msg"]); ?></td>
                <!-- <td>￥0</td> -->
                <td>￥<?php echo ($vo["total_fee"]); ?></td>
              </tr><?php endif; endif; endforeach; endif; else: echo "" ;endif; ?>
          <tr>
            <td colspan="19" style="text-align: center">
            <input type="hidden" name="schedule_id" value="<?php echo ($schedule_info["id"]); ?>">
            <input type="hidden" name="sum_person_day" value="<?php echo ($sum_person_day); ?>">
            <input type="submit" class="btn btn-success" value="生成退房水电气">

            <!-- 确认未支付单 -->
             <!--  <a href="" class="btn btn-success">完成</a> -->
            <!-- 确认未支付单 -->
            </td>
          </tr>
        </tbody>
      </table>
    </form>
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
<script src="/Public/admin/js/bootstrap.min.js"></script> 
<script src="/Public/admin/js/jquery.uniform.js"></script> 
<script src="/Public/admin/js/select2.min.js"></script> 
<script src="/Public/admin/js/jquery.ui.custom.js"></script>
<script src="/Public/admin/js/jquery.dataTables.min.js"></script>  
<script src="/Public/admin/js/matrix.js"></script>
<script src="/Public/admin/js/matrix.tables.js"></script>
</body>
</html>