<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
<title>乐窝公寓管理系统</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="/Public/admin/css/bootstrap.min.css" />
<link rel="stylesheet" href="/Public/admin/css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="/Public/admin/css/uniform.css" />
<link rel="stylesheet" href="/Public/admin/css/select2.css" />
<link rel="stylesheet" href="/Public/admin/css/matrix-style.css" />
<link rel="stylesheet" href="/Public/admin/css/matrix-media.css" />
<link href="/Public/admin/font-awesome/css/font-awesome.css" rel="stylesheet" />
<style>
  .input1 {width:30px;}
  .input2 {width:40px;}
  .input3 {width:50px;}
  .input4 {width:60px;}
  .input5 {width:70px;}
  .bill-table th{
    background:#efefef;
    border-bottom: 1px solid #CDCDCD;
    height: 36px; 
    padding: 5px;
    text-align:center;
  }
  .bill-table td{
    background:#fff;
    border-bottom: 1px solid #CDCDCD;
    height: 36px; 
    padding: 1px;
    text-align:center;
    word-wrap:break-word;
  }
</style>
</head>
<body>
<!--main-container-part-->
<div>
<div class="row-fluid">
  <div class="widget-box">
   <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
   <a href="<?php echo (session('P_REFERER')); ?>" class="label label-info pull-left">返回</a>
      <h5><?php echo ($year); ?>-<?php echo ($month); ?>账单&nbsp;&nbsp;房屋：<?php echo ($house_code); ?>&nbsp;&nbsp;小区楼层:<?php echo ($area_info["area_name"]); ?>(<?php echo ($house_info["building"]); ?>-<?php echo ($house_info["floor"]); ?>-<?php echo ($house_info["door_no"]); ?>)</h5>
      
    </div>
    <form action="<?php echo U('Admin/Houses/update_bill');?>" method="post">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>房屋总电&nbsp;阶梯算法:<label class="label label-info"><?php echo ($energy_stair); ?></label></th>
            <th>房屋总水&nbsp;单价:<label class="label label-info"><?php echo ($water_unit); ?></label></th>
            <th>房屋总气&nbsp;单价:<label class="label label-info"><?php echo ($gas_unit); ?></label></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
                <label for="start_energy" class="label label-success">始:</label>
                <input id="start_energy" class="input4" type="number" step="0.01" name="start_energy" value="<?php echo ($charge_house_info['start_energy']); ?>">
                <label for="end_energy" class="label label-success">止:</label>
                <input id="end_energy" class="input4" type="number" step="0.01" name="end_energy" value="<?php echo ($charge_house_info['end_energy']); ?>">
            </td>
            <td>
              <label for="start_water" class="label label-success">始:</label>
              <input id="start_water" class="input4" type="number" step="0.01" name="start_water" value="<?php echo ($charge_house_info['start_water']); ?>">
              <label for="end_water" class="label label-success">止:</label>
              <input id="end_water" class="input4" type="number" step="0.01" name="end_water" value="<?php echo ($charge_house_info['end_water']); ?>">
            </td>
            <td>
              <label for="start_gas" class="label label-success">始:</label>
              <input id="start_gas" class="input4" type="number" step="0.01" name="start_gas" value="<?php echo ($charge_house_info['start_gas']); ?>">
              <label for="end_gas" class="label label-success">止:</label>
              <input id="end_gas" class="input4" type="number" step="0.01" name="end_gas" value="<?php echo ($charge_house_info['end_gas']); ?>">
            </td>
          </tr>
          <tr>
            <td colspan="3">
              <label for="extra_public_energy_fee" class="label label-success">额外公摊电费:</label>
                <input id="extra_public_energy_fee" class="input4" type="number" step="0.01" name="extra_public_energy_fee" value="<?php echo ($charge_house_info['extra_public_energy_fee']); ?>">

                <label for="extra_public_water_fee" class="label label-success">额外公摊水费:</label>
                <input id="extra_public_water_fee" class="input4" type="number" step="0.01" name="extra_public_water_fee" value="<?php echo ($charge_house_info['extra_public_water_fee']); ?>">

                <label for="extra_public_gas_fee" class="label label-success">额外公摊气费:</label>
                <input id="extra_public_gas_fee" class="input4" type="number" step="0.01" name="extra_public_gas_fee" value="<?php echo ($charge_house_info['extra_public_gas_fee']); ?>">

            </td>
          </tr>
          <tr>
            <td colspan="3">
              <label class="label label-info">公共区域电费:<?php echo ($public_energy_fee); ?></label>
              <label class="label label-info">房间总电费:<?php echo ($room_total_energy_fee); ?></label>
              <label class="label label-info">总水费:<?php echo ($public_water_fee); ?></label>
              <label class="label label-info">总气费:<?php echo ($public_gas_fee); ?></label>
              <?php if(!empty($abnormal_bill_list)): ?><label class="date badge badge-important">(注:扣除退房等生成的水电气)</label><?php endif; ?>
              <label class="date badge badge-warning">(警告:修改水电气始止度数会直接修改该月和上个月录入的水电气)</label>
            </td>
          </tr>
        </tbody>

      </table>
      <div>
      <?php if(!empty($abnormal_bill_list)): ?><table class="table table-bordered table-striped with-check">
          <thead>
            <tr>
              <th>类型</th>
              <th>状态</th>
              <th>租客</th>
              <th>人日</th>
              <th>房租</th>
              <th>房租描述</th>
              <th>服务费</th>
              <th>物业费</th>
              <th>个人电费</th>
              <th>公摊电费</th>
              <th>水费</th>
              <th>气费</th>
              <th>燃气垃圾费</th>
              <th>欠款</th>
              <th>欠款描述</th>
             <!--  <th>滞纳金</th> -->
              <th>总金额</th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody>
            <?php if(is_array($abnormal_bill_list)): $i = 0; $__LIST__ = $abnormal_bill_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                  <td>
                    <?php switch($vo["type"]): case "2": ?>退房<?php break;?>
                      <?php case "3": ?>换房<?php break;?>
                      <?php case "4": ?>退房<?php break; endswitch;?>
                  </td>
                  <td>
                      <?php if($vo["is_send"] == 1): ?><label class="label label-success">已发送</label>
                        <?php else: ?>
                        <label class="label label-important">未发送</label><?php endif; ?>
                  </td>
                  <td><a href="<?php echo U('Admin/Tenant/contract_info',array('account_id'=>$vo['account_id'],'room_id'=>$vo['room_id']));?>" target="_blank" class="btn btn-info btn-mini"><?php echo ($vo["realname"]); ?></a></td>
                  <td><?php echo ($vo["person_day"]); ?></td>
                  <td><?php echo ($vo["rent_fee"]); ?></td>
                  <td><?php echo ($vo["rent_des"]); ?></td>
                  <td><?php echo ($vo["service_fee"]); ?></td>
                  <td><?php echo ($vo["wgfee_unit"]); ?></td>
                  <td><?php echo ($vo["room_energy_fee"]); ?></td>
                  <td><?php echo ($vo["energy_fee"]); ?></td>
                  <td><?php echo ($vo["water_fee"]); ?></td>
                  <td><?php echo ($vo["gas_fee"]); ?></td>
                  <td><?php echo ($vo["rubbish_fee"]); ?></td>
                  <td><?php echo ($vo["wx_fee"]); ?></td>
                  <td><?php echo ($vo["wx_des"]); ?></td>
                  <td><?php echo ($vo["total_fee"]); ?></td>
                  <td>
                    <a class="btn btn-info" id="check-info-<?php echo ($vo["id"]); ?>">查看水电气始止</a>
                    <script>
                    window.onload = function(){
                      $("#check-info-<?php echo ($vo["id"]); ?>").click(function(){
                        var msg = "电:始度:"+<?php echo ($vo["start_energy"]); ?>+",止度:"+<?php echo ($vo["end_energy"]); ?>;
                        msg += "\n\n房间电:始度:"+<?php echo ($vo["start_room_energy"]); ?>+",止度:"+<?php echo ($vo["end_room_energy"]); ?>;
                        msg += "\n\n水:始度:"+<?php echo ($vo["start_water"]); ?>+",止度:"+<?php echo ($vo["end_water"]); ?>;
                        msg += "\n\n气:始度:"+<?php echo ($vo["start_gas"]); ?>+",止度:"+<?php echo ($vo["end_gas"]); ?>;
                        alert(msg);
                        
                      });
                    } 
                    </script>
                  </td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
        </table><?php endif; ?>
        
        <table class=" table-bordered bill-table with-check">
          <colgroup>
            <col style="width:4%">
            <col style="width:1%">
            <col style="width:1%">
            <col style="width:10%">
            <col style="width:4%">
            <col style="width:5%">
            <col style="width:4%">
            <col style="width:4%">
            <col style="width:6%">
            <col style="width:4%">
            <col style="width:4%">
            <col style="width:4%">
            <col style="width:4%">
            <col style="width:4%">
            <col style="width:4%">
            <col style="width:4%">
            <col style="width:4%">
            <col style="width:4%">
            <col style="width:4%">
            <col style="width:4%">
            <col style="width:3%">
          </colgroup>
          <thead>
            <tr>
              <th>全选<input type="checkbox" id="title-checkbox" name="title-checkbox" /></th>
              <th>状态</th>
              <th>房间编号</th>
              <th>房间电表</th>
              <th>床位编号</th>
              <th>租客</th>
              <th>人日</th>
              <th>房租</th>
              <th>房租描述</th>
              <th>服务费</th>
              <th>物业费</th>
              <th>个人电费</th>
              <th>公摊电费</th>
              <th>水费</th>
              <th>气费</th>
              <th>燃气垃圾费</th>
              <th>欠款</th>
              <th>欠款描述</th>
              <th>应缴费日</th>
              <th>最迟缴费</th>
             <!--  <th>滞纳金</th> -->
              <th>总金额</th>
            </tr>
          </thead>
          <tbody>
            <?php if(is_array($bill_list)): $i = 0; $__LIST__ = $bill_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["account_id"] != 0): ?><tr>
                  <td> 
                    <?php if($vo["type"] == 1): ?><!-- <input class="charge_id" type="checkbox" name="id" value="<?php echo ($vo["id"]); ?>"/> -->
                      <input class="pro_id" type="checkbox" name="pro_id" value="<?php echo ($vo["pro_id"]); ?>"/><?php endif; ?>
                      <input type="hidden" name="room_list[<?php echo ($vo["id"]); ?>][room_id]" value="<?php echo ($vo["room_id"]); ?>">
                      <input type="hidden" name="room_list[<?php echo ($vo["id"]); ?>][pro_id]" value="<?php echo ($vo["pro_id"]); ?>">
                  </td>
                  <td>
                    <?php if($vo["type"] == 1): if($vo["is_send"] == 1): ?><label class="label label-success">已发送</label>
                        <?php else: ?>
                        <label class="label label-important">未发送</label><?php endif; ?>
                      <?php if($vo["pay_status"] == 1): ?><label class="label label-success">已支付</label>
                        <?php else: ?>
                        <label class="label label-important">未支付</label><?php endif; endif; ?>  
                  </td>
                  <td><?php echo ($vo["room_code"]); ?></td>
                  <td>
                  <label for="start_room_energy<?php echo ($vo["id"]); ?>" class="label label-success">始:</label>
                  <input id="start_room_energy<?php echo ($vo["id"]); ?>" class="input3" type="number" step="0.01" name="room_list[<?php echo ($vo["id"]); ?>][start_room_energy]" value="<?php echo ($vo["start_room_energy"]); ?>"><br/>
                  <label for="end_room_energy<?php echo ($vo["id"]); ?>" class="label label-success">止:</label>
                  <input id="end_room_energy<?php echo ($vo["id"]); ?>" class="input3" type="number" step="0.01" name="room_list[<?php echo ($vo["id"]); ?>][end_room_energy]" value="<?php echo ($vo["end_room_energy"]); ?>"></td>
                  <td><?php echo ($vo["bed_code"]); ?></td>
                  <td><a href="<?php echo U('Admin/Tenant/contract_info',array('account_id'=>$vo['account_id'],'room_id'=>$vo['room_id']));?>" target="_blank" class="btn btn-info btn-mini"><?php echo ($vo["realname"]); ?></a></td>
                  <td><input class="input2 person_day" type="number" step="0.01" name="room_list[<?php echo ($vo["id"]); ?>][person_day]" value="<?php echo ($vo["person_day"]); ?>"></td>
                  <td><input class="input3 rent_fee" type="number" step="0.01" name="room_list[<?php echo ($vo["id"]); ?>][rent_fee]" value="<?php echo ($vo["rent_fee"]); ?>"></td>
                  <td>
                  <textarea name="room_list[<?php echo ($vo["id"]); ?>][rent_des]" style="width:140px;"><?php echo ($vo["rent_des"]); ?></textarea>
                  </td>
                  <td><input class="input2 service_fee" type="number" step="0.01" name="room_list[<?php echo ($vo["id"]); ?>][service_fee]" value="<?php echo ($vo["service_fee"]); ?>"></td>
                  <td><input class="input2 wgfee_unit" type="number" step="0.01" name="room_list[<?php echo ($vo["id"]); ?>][wgfee_unit]" value="<?php echo ($vo["wgfee_unit"]); ?>"></td>
                  <td class="room_energy_fee"><?php echo ($vo["room_energy_fee"]); ?></td>
                  <td class="energy_fee"><?php echo ($vo["energy_fee"]); ?></td>
                  <td class="water_fee"><?php echo ($vo["water_fee"]); ?></td>
                  <td class="gas_fee"><?php echo ($vo["gas_fee"]); ?></td>
                  <td><input class="input2 rubbish_fee" type="number" step="0.01" name="room_list[<?php echo ($vo["id"]); ?>][rubbish_fee]" value="<?php echo ($vo["rubbish_fee"]); ?>"></td>
                  <td><input class="input2 wx_fee" type="number" step="0.01" name="room_list[<?php echo ($vo["id"]); ?>][wx_fee]" value="<?php echo ($vo["wx_fee"]); ?>">
                  </td>
                  <td>
                  <textarea name="room_list[<?php echo ($vo["id"]); ?>][wx_des]" style="width: 55px;"><?php echo ($vo["wx_des"]); ?></textarea>
                  </td>
                  <td>
                      <input value="<?php echo ($vo["should_date"]); ?>" type="text" name="room_list[<?php echo ($vo["id"]); ?>][should_date]" class="span11" style="width:100px; margin-bottom: 0px">
                  </td>
                  <td>
                        <input value="<?php echo ($vo["last_date"]); ?>" type="text" name="room_list[<?php echo ($vo["id"]); ?>][last_date]" class="span11" style="width:100px; margin-bottom: 0px">
                  </td>
                  <td class="total_fee"><?php echo ($vo["price"]); ?></td>
                </tr><?php endif; endforeach; endif; else: echo "" ;endif; ?>
            <tr>
              <td colspan="6"></td>
              <td id="total_day" style="font-size: 15px; font-weight: bold;"></td>
              <td id="total_rent" style="font-size: 15px; font-weight: bold;"></td>
              <td></td>
              <td id="total_service_fee" style="font-size: 15px; font-weight: bold;"></td>
              <td id="total_wgfee_unit" style="font-size: 15px; font-weight: bold;"></td>
              <td id="total_room_energy_fee" style="font-size: 15px; font-weight: bold;"></td>
              <td id="total_energy_fee" style="font-size: 15px; font-weight: bold;"></td>
              <td id="total_water_fee" style="font-size: 15px; font-weight: bold;"></td>
              <td id="total_gas_fee" style="font-size: 15px; font-weight: bold;"></td>
              <td id="total_rubbish_fee" style="font-size: 15px; font-weight: bold;"></td>
              <td id="total_wx_fee" style="font-size: 15px; font-weight: bold;"></td>
              <td></td>
              <td></td>
              <td></td>
              <td id="total_fee" style="font-size: 15px; font-weight: bold;"></td>
            </tr>
            <tr>
            <td colspan="21" style="text-align: center">
              <label class="label label-info">公共区域电费:<?php echo ($public_energy_fee); ?></label>
              <label class="label label-info">房间总电费:<?php echo ($room_total_energy_fee); ?></label>
              <label class="label label-info">总水费:<?php echo ($public_water_fee); ?></label>
              <label class="label label-info">总气费:<?php echo ($public_gas_fee); ?></label>
              <?php if(!empty($abnormal_bill_list)): ?><label class="date badge badge-important">(注:扣除退房等生成的水电气)</label><?php endif; ?>
              <label class="date badge badge-important">(注:因小数点进一，所以总收益会比应收多)</label>
            </td>
          </tr>
            <tr>
              <td colspan="20" style="text-align: center">
              <!-- 生成账单时显示 -->
              <input type="hidden" name="charge_house_id" value="<?php echo ($charge_house_info['id']); ?>">
              <input type="hidden" name="house_id" value="<?php echo ($house_id); ?>">
              <input type="hidden" name="year" value="<?php echo ($year); ?>">
              <input type="hidden" name="month" value="<?php echo ($month); ?>">
                <?php if($is_has_pay == false): ?><input  class="btn btn-danger" type="submit" value="修改"><?php endif; ?>
                <a style="margin-left: 20px;" id="send_bill" class="btn btn-success">发送账单</a>
              <?php if($charge_house_info["is_create"] != 1): ?><a style="margin-left: 20px;" class="btn btn-warning create_bill" data-id="<?php echo ($charge_house_info['id']); ?>" data-houseId="<?php echo ($house_id); ?>" data-year="<?php echo ($year); ?>" data-month="<?php echo ($month); ?>">生成账单</a>
                <?php else: ?>
                <a style="margin-left: 20px;" class="btn btn-warning re_create_bill" data-id="<?php echo ($charge_house_info['id']); ?>" data-houseId="<?php echo ($house_id); ?>" data-year="<?php echo ($year); ?>" data-month="<?php echo ($month); ?>">重新生成</a><?php endif; ?>
              <!-- 生成账单时显示 href="<?php echo U('Admin/Houses/send_bill',array('house_id'=>$house_id,'year'=>$year,'month'=>$month));?>"-->

              <!-- 待办时显示 -->
               <!--  <a href="checkout-pay.html" class="btn btn-success">下一步</a> -->
              <!-- 待办时显示 -->

              <!-- 确认未支付单 -->
               <!--  <a href="" class="btn btn-success">完成</a> -->
              <!-- 确认未支付单 -->
              </td>
            </tr>
          </tbody>
        </table>
      </div>
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
<script>
function getTotalPersonDay(){
  var total_day = 0;
  $.each( $(".person_day"), function( i,n ){
    total_day += Number($(n).val());
  } );
  return total_day.toFixed(2);
}
function getTotalRent(){
  var rent = 0;
  $.each( $(".rent_fee"), function( i,n ){
    rent += Number($(n).val());
  } );
  return rent.toFixed(2);
}
function getTotalServiceFee(){
  var service_fee = 0;
  $.each( $(".service_fee"), function( i,n ){
    service_fee += Number($(n).val());
  } );
  return service_fee.toFixed(2);
}
function getTotalWgfeeUnit(){
  var wgfee_unit = 0;
  $.each( $(".wgfee_unit"), function( i,n ){
    wgfee_unit += Number($(n).val());
  } );
  return wgfee_unit.toFixed(2);
}
function getTotalWxFee(){
  var wx_fee = 0;
  $.each( $(".wx_fee"), function( i,n ){
    wx_fee += Number($(n).val());
  } );
  return wx_fee.toFixed(2);
}
function getTotalRoomEnergyFee(){
  var room_energy_fee = 0;
  $.each( $(".room_energy_fee"), function( i,n ){
    room_energy_fee += Number($(n).html());
  } );
  return room_energy_fee.toFixed(2);
}
function getTotalEnergyFee(){
  var energy_fee = 0;
  $.each( $(".energy_fee"), function( i,n ){
    energy_fee += Number($(n).html());
  } );
  return energy_fee.toFixed(2);
}
function getTotalWaterFee(){
  var water_fee = 0;
  $.each( $(".water_fee"), function( i,n ){
    water_fee += Number($(n).html());
  } );
  return water_fee.toFixed(2);
}
function getTotalGasFee(){
  var gas_fee = 0;
  $.each( $(".gas_fee"), function( i,n ){
    gas_fee += Number($(n).html());
  } );
  return gas_fee.toFixed(2);
}
function getTotalRubbishFee(){
  var rubbish_fee = 0;
  $.each( $(".rubbish_fee"), function( i,n ){
    rubbish_fee += Number($(n).val());
  } );
  return rubbish_fee.toFixed(2);
}
function getTotalFee(){
  var total_fee = 0;
  $.each( $(".total_fee"), function( i,n ){
    total_fee += Number($(n).html());
  } );
  return total_fee.toFixed(2);
}
$("#total_day").html(getTotalPersonDay());
$("#total_rent").html(getTotalRent());
$("#total_service_fee").html(getTotalServiceFee());
$("#total_wgfee_unit").html(getTotalWgfeeUnit());
$("#total_room_energy_fee").html(getTotalRoomEnergyFee());
$("#total_energy_fee").html(getTotalEnergyFee());
$("#total_water_fee").html(getTotalWaterFee());
$("#total_gas_fee").html(getTotalGasFee());
$("#total_rubbish_fee").html(getTotalRubbishFee());
$("#total_wx_fee").html(getTotalWxFee());
$("#total_fee").html(getTotalFee());


$(window).load(function(){
  $("#send_bill").click(function(){
    if(!confirm('是否发送<?php echo ($month); ?>月份账单?')) return false;
    var house_id = $("input[name='house_id']").val();
    var year = $("input[name='year']").val();
    var month = $("input[name='month']").val();
    var url = '<?php echo U("Admin/Houses/send_bill");?>';

    //循环获取id
    var pro_id = [];
    $.each($(".pro_id"),function(i,n){
      if ( $(n).is(":checked") ) {
        pro_id.push($(n).val());
      }
    });
    if (0==pro_id.length) {
      alert("请选择账单");
      return false;
    }
    var arr_str = pro_id.join(",");//拼接成字符串

    $.ajax({
       type: "POST",
       url: url,
       data: "house_id="+house_id+"&year="+year+"&month="+month+"&pro_id="+arr_str,
       dataType: "json",
       success: function(result){
          var name_str = new Array;
          $.each(result,function(i,n){
            name_str.push(n);
          });
          name_str = name_str.join(",");
          alert(name_str+"发送成功!");
       }
    });

    location.reload();
  });
});

//重新生成账单ajax
var url = "<?php echo U('Admin/Houses/create_bill');?>";
var reurl = "<?php echo U('Admin/Houses/re_create_bill');?>";

//生成账单ajax
    $(".create_bill").click(function(){
      var id = $(this).attr("data-id");
      var year = $(this).attr("data-year");
      var month = $(this).attr("data-month");
      var house_id = $(this).attr("data-houseId");
      var bill_status = $("#bill_status"+id);

        $.ajax({
           type: "POST",
           url: url,
           data: "charge_id="+id+"&house_id="+house_id+"&year="+year+"&month="+month,
           dataType: "json",
           success: function(msg){
             if ( msg.info != undefined ) {
              alert(msg.info);
              return false;
             }
             if ( msg.result ) {
              bill_status.removeClass("label-important");
              bill_status.html("已生成");
              bill_status.addClass("label-success");
              alert("生成成功");
              location.reload();
             } else {
              alert("生成失败");
             }
           }
        });
    })
$(".re_create_bill").click(function(){

  if ( !confirm('确定重新生成?') ) return false; 
  if ( !confirm('系统将会删除账单，并重新生成') ) return false; 

  var id = $(this).attr("data-id");
  var year = $(this).attr("data-year");
  var month = $(this).attr("data-month");
  var house_id = $(this).attr("data-houseId");
  var bill_status = $("#bill_status"+id);

    $.ajax({
       type: "POST",
       url: reurl,
       data: "charge_id="+id+"&house_id="+house_id+"&year="+year+"&month="+month,
       dataType: "json",
       success: function(data){
         alert(data.msg);
         if ( data.status ) {
          $.ajax({
             type: "POST",
             url: url,
             data: "charge_id="+id+"&house_id="+house_id+"&year="+year+"&month="+month,
             dataType: "json",
             success: function(msg){
               if ( msg.info != undefined ) {
                alert(msg.info);
                return false;
               }
               if ( msg.result ) {
                alert("重新生成成功");
               } else {
                alert("重新生成失败");
               }
               location.reload();
             }
          });
         }
       }
    });
})
</script>
<?php if($public_energy_fee < 0): ?><script>
    (function(){
      var msg = "┌---------------------------┐\n\n   公共区域电费为负数,请检查 \n\n└---------------------------┘";
      alert(msg);
    }())
  </script><?php endif; ?>