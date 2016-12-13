<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
<title>乐窝公寓管理系统</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="/Public/admin/css/bootstrap.min.css" />
<link rel="stylesheet" href="/Public/admin/css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="/Public/admin/css/datepicker.css" />
<link rel="stylesheet" href="/Public/admin/css/uniform.css" />
<link rel="stylesheet" href="/Public/admin/css/select2.css" />
<link rel="stylesheet" href="/Public/admin/css/matrix-style.css" />
<link rel="stylesheet" href="/Public/admin/css/matrix-media.css" />
<link href="/Public/admin/font-awesome/css/font-awesome.css" rel="stylesheet" />
<style>
  .first {
    font-size: 18px;
  }
  .prev {
    font-size: 18px;
  }
  .next {
    font-size: 18px;
  }
  .end {
    font-size: 18px;
  }
  .current {
    padding: 5px;
    color: #fff;
    background-color: #468847;
    text-shadow: 0 -1px 0 rgba(0,0,0,0.25);
    font-weight: bold;
    vertical-align: baseline;
    white-space: nowrap;
    font-size: 18px;
  }
  .num {
    padding: 5px;
    font-size: 18px;
  }
  .contract-table th{
    background:#efefef;
    border-bottom: 1px solid #CDCDCD;
    height: 36px; 
    padding: 5px;
    text-align:center;
  }
  .contract-table td{
    background:#fff;
    border-bottom: 1px solid #CDCDCD;
    height: 36px; 
    padding: 5px;
    text-align:center;
  }
</style>
</head>
<body>
<!--main-container-part-->
<div>
    <div class="widget-box">
      <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
        <h5>日常列表</h5>
        <a href="<?php echo U('Admin/Index/index');?>" class="label label-info pull-left">返回</a>
      </div>
      <div class="widget-content nopadding">
      
      <form action="<?php echo U('Admin/Pay/daily_bill');?>" method="get" class="form-inline">
        <label>
          <a class="btn">房间编号:</a><input type="text" name="room_code" class="span11" style="width:100px; margin-bottom: 0px" placeholder="房间编号" <?php if(!empty($room_code)): ?>value="<?php echo ($room_code); ?>"<?php else: ?>value=""<?php endif; ?>>
        </label>
        <label>
          <a class="btn">姓名:</a><input type="text" name="realname" class="span11" style="width:100px; margin-bottom: 0px" placeholder="姓名" <?php if(!empty($realname)): ?>value="<?php echo ($realname); ?>"<?php else: ?>value=""<?php endif; ?> >
        </label>
        <label>
          <a class="btn">小区:</a>
          <select name="area_id" style="width:100px;">
            <option value="" >请选择</option>
            <?php if(is_array($area_list)): $i = 0; $__LIST__ = $area_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($area_id == $vo['id']): ?>selected<?php endif; ?> ><?php echo ($vo["area_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
           </select>
        </label>
        <label>
          <a class="btn">地区:</a>
          <select name="city_id" style="width:100px;">
            <option value="" >请选择</option>
            <?php if(is_array($city_list)): $i = 0; $__LIST__ = $city_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if($city_id == $key): ?>selected<?php endif; ?> ><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
           </select>
        </label>
        <label>
          <a class="btn">批次:</a>
          <select name="input_year" style="width:80px;">
            <option selected="selected" value="" >请选择</option>
            <option value="2017">2017</option>
            <option value="2016">2016</option>
            <option value="2015">2015</option>
          </select>
          年
          <select name="input_month" style="width:80px;">
            <option selected="selected" value="">请选择</option>
            <option value="12">12</option>
            <option value="11">11</option>
            <option value="10">10</option>
            <option value="9">9</option>
            <option value="8">8</option>
            <option value="7">7</option>
            <option value="6">6</option>
            <option value="5">5</option>
            <option value="4">4</option>
            <option value="3">3</option>
            <option value="2">2</option>
            <option value="1">1</option>
           </select>
           月
        </label>
        <br/>
         <label>
          <a class="btn">发送状态:</a>
          <select name="is_send" style="width:80px;">
            <option selected="selected" value="">请选择</option>
            <option <?php if($is_send === '1'): ?>selected<?php endif; ?> value="1">已发送</option>
            <option <?php if($is_send === '0'): ?>selected<?php endif; ?> value="0">未发送</option>
           </select>
        </label>
        <label>
          <a class="btn">支付状态:</a>
          <select name="pay_status" style="width:80px;">
            <option selected="selected" value="">请选择</option>
            <option <?php if($pay_status === '1'): ?>selected<?php endif; ?> value="1">已支付</option>
            <option <?php if($pay_status === '0'): ?>selected<?php endif; ?> value="0">未支付</option>
           </select>
        </label>
        
        <label>
            <a class="btn">时间搜索:</a>
            <select name="search_time" style="width:80px;">
              <option selected="selected" value="">请选择</option>
              <option <?php if($search_time == 'payDate'): ?>selected<?php endif; ?> value="payDate">支付时间</option>
              <option <?php if($search_time == 'htStartDate'): ?>selected<?php endif; ?> value="htStartDate">租期开始</option>
              <option <?php if($search_time == 'htEndDate'): ?>selected<?php endif; ?> value="htEndDate">租期结束</option>
              <option <?php if($search_time == 'latePay'): ?>selected<?php endif; ?> value="latePay">最迟缴费</option>
            <option <?php if($search_time == 'shouldPay'): ?>selected<?php endif; ?> value="shouldPay">应缴费</option>
            </select>
        </label>
        <label>
            <div data-date="" data-date-format="yyyy-mm-dd" class="input-append date datepicker">
              <input <?php if(!empty($start_time)): ?>value="<?php echo ($start_time); ?>"<?php else: ?>value=""<?php endif; ?> type="text" name="start_time" class="span11" style="width:100px;">
              <span class="add-on"><i class="icon-th"></i></span>
            </div>
            <a class="btn">至</a>
            <div data-date="" data-date-format="yyyy-mm-dd" class="input-append date datepicker">
              <input <?php if(!empty($end_time)): ?>value="<?php echo ($end_time); ?>"<?php else: ?>value=""<?php endif; ?> type="text" name="end_time" class="span11" style="width:100px; margin-bottom: 0px">
              <span class="add-on"><i class="icon-th"></i></span>
            </div>
          
        </label>
        
        <label>
          <a class="btn">缴费方式:</a>
          <select name="pay_type" style="width:120px;">
            <option selected="selected" value="">请选择</option>
            <?php if(is_array($pay_type_list)): $i = 0; $__LIST__ = $pay_type_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if($pay_type == $key): ?>selected<?php endif; ?> ><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
          </select>
        </label>
       
        <label>
          <a class="btn">排序方式:</a>
          <select name="sort_type" style="width:120px;">
            <option selected="selected" value="">请选择</option>
            <option <?php if($sort_type == 'payTime'): ?>selected<?php endif; ?> value="payTime">支付时间</option>
            <option <?php if($sort_type == 'payType'): ?>selected<?php endif; ?> value="payType">类型</option>

            <option <?php if($sort_type == 'latePay'): ?>selected<?php endif; ?> value="latePay">最迟缴费</option>
            <option <?php if($sort_type == 'shouldPay'): ?>selected<?php endif; ?> value="shouldPay">应缴费</option>
            <option <?php if($sort_type == 'inputMonth'): ?>selected<?php endif; ?> value="inputMonth">批次</option>
          </select>
          <select name="sort" style="width:100px;">
            <option <?php if($sort == 'asc'): ?>selected<?php endif; ?> selected="selected" value="1">从小到大</option>
            <option <?php if($sort == 'desc'): ?>selected<?php endif; ?> value="2">从大到小</option>
          </select>
        </label>       
        <br/>           
        <label>
          <label><a class="btn">显示</a><input type="number" name="page_count" <?php if(!empty($page_count)): ?>value="<?php echo ($page_count); ?>"<?php else: ?>value=""<?php endif; ?> placeholder="10"></label> 
          <label><a class="btn">搜索总数:<?php echo ($count); ?></a></label>
          <button type="submit" class="btn btn-success">搜索</button>
          
          <a class="btn btn-info" href="<?php echo U('Admin/Pay/daily_bill');?>">重置</a>
          <button class="btn btn-info">下载</button>
          <a href="<?php echo U('Admin/Pay/delete_bill');?>" class="btn btn-danger">已删除的账单</a>
        </label>
      </form>

        <table class="table-bordered contract-table">
          <thead>
            <tr>
              <th>账单号</th>
              <th>类型</th>
              <th>房间编号</th>
              <th>床位编号</th>
              <th>小区楼层</th>
              <th>承租人</th>
              <th>电话</th>
              <th>租期开始</th>
              <th>租期结束</th>
              <!-- <th>房租到期</th> -->
              <th>最迟缴费</th>
              <th>应缴费</th>
              <th>倒计时</th>
              <!-- <th>缴费周期</th> -->
              <th>批次</th>
              <th>押金</th>
              <th>房租</th>
              <th>服务费</th>
              <th>水电气</th>
              <th>物管</th>
              <th>欠费</th>
              <th>手续费</th>
              <th>实收</th>
              <th>支付时间</th>
              <th>支付方式</th>
              <th>发送状态</th>
              <th>支付状态</th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody>
            <?php if(is_array($charge_bill_list)): $i = 0; $__LIST__ = $charge_bill_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
              <td style="width:20px;"><?php echo ($vo["order_no"]); ?></td>
              <td><?php echo ($vo["type_name"]); ?></td>
              <td><?php echo ($vo["room_code"]); ?></td>
              <td><?php echo ($vo["bed_code"]); ?></td>
              <td><?php echo ($vo["area_name"]); ?></td>
              <td><a href="<?php echo U('Admin/Tenant/contract_info',array('account_id'=>$vo['account_id']));?>" target="_blank" class="btn btn-info btn-mini"><?php echo ($vo["realname"]); ?></a></td>
              <td><?php echo ($vo["mobile"]); ?></td>
              <td><?php echo ($vo["ht_start_date"]); ?></td>
              <td><?php echo ($vo["ht_end_date"]); ?></td>
              <!-- <td><?php echo ($vo["rent_date"]); ?></td> -->
              <td><?php echo ($vo["late_pay_date"]); ?></td>
              <td><?php echo ($vo["should_pay_date"]); ?></td>
              <td>
              <?php if($vo["pay_status"] == 0): if($vo['count_down_days'] <= 0): ?><label class="label label-important"><?php echo ($vo["count_down_days"]); ?></label>
                  <?php else: ?>
                  <label class="label label-success"><?php echo ($vo["count_down_days"]); ?></label><?php endif; endif; ?>
              </td>
              <!-- <td><?php echo ($vo["period"]); ?></td> -->
              <td><?php echo ($vo["input_year"]); ?>年<?php echo ($vo["input_month"]); ?>月</td>
              <td><?php echo ($vo["deposit"]); ?></td>
              <td><?php echo ($vo["rent_fee"]); ?></td>
              <td><?php echo ($vo["service_fee"]); ?></td>
              <td><?php echo ($vo["SDQtotal"]); ?></td>
              <td><?php echo ($vo["wgfee_unit"]); ?></td>
              <td><?php echo ($vo["wx_fee"]); ?></td>
              <td><?php echo ($vo["handling_fee"]); ?></td>
              <td><?php echo ($vo["total_fee"]); ?></td>
              <td><?php echo ($vo["pay_time"]); ?></td>
              <td><?php echo ($vo["pay_type_name"]); ?></td>
              <td id="send<?php echo ($vo["id"]); ?>">
                <?php if($vo["is_send"] == 1): ?><span class="label label-success">已发</span>
                  <?php else: ?>
                  <span class="label label-important">未发</span><?php endif; ?>
              </td>
              <td>
                <?php if($vo["pay_status"] == 1): ?><span class="label label-success">已付</span>
                  <?php else: ?>
                  <span class="label label-important">未付</span><?php endif; ?>
              </td>
              <td>
                <?php if($vo["is_send"] != 1): ?><a data-id="<?php echo ($vo["id"]); ?>" class="btn btn-info btn-mini update-send">发送</a><?php endif; ?>
                <?php if($vo["pay_status"] != 1): ?><a href="<?php echo U('Admin/Pay/edit_pay',array('pro_id'=>$vo['pro_id']));?>" class="btn btn-success btn-mini">修改</a>
                  <?php else: ?>
                  <a href="<?php echo U('Admin/Pay/check_pay_log',array('id'=>$vo['id']));?>" class="btn btn-success btn-mini">查看</a><?php endif; ?>
                <a href="<?php echo U('Admin/Pay/delete_bill',array('id'=>$vo['id']));?>" class="btn btn-danger btn-mini" onclick="if(!confirm('确定删除')) return false;">删除</a>
              </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            <tr><td colspan="27" style="text-align: center;"><?php echo ($page); ?></td></tr>
          </tbody>
        </table>


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
<script src="/Public/admin/js/bootstrap.min.js"></script> 
<script src="/Public/admin/js/bootstrap-datepicker.js"></script> 
<script src="/Public/admin/js/bootstrap-colorpicker.js"></script> 
<script src="/Public/admin/js/jquery.uniform.js"></script> 
<script src="/Public/admin/js/select2.min.js"></script> 
<script src="/Public/admin/js/jquery.ui.custom.js"></script>
<script src="/Public/admin/js/jquery.dataTables.min.js"></script>  
<script src="/Public/admin/js/matrix.js"></script>
<script src="/Public/admin/js/matrix.tables.js"></script>
<script src="/Public/admin/js/matrix.form_common.js"></script>

</body>
</html>
<script>
  $(function(){
    $(".update-send").click(function(){
      if(!confirm('确定发送?\n\n注:短信不会发送给用户,只修改账单的发送状态,\n\n用户可在用户端看到此账单!')) return false;

      var id = $(this).attr("data-id");
      var url = "<?php echo U('Admin/Pay/update_send_bill');?>";
      var send_el = $("#send"+id);

      $.ajax({
           type: "POST",
           url: url,
           data: "id="+id,
           dataType: "json",
           success: function(msg){
             if ( msg.info != undefined ) {
              alert(msg.info);
              return false;
             }
             alert(msg.info);
           }
        });
      $(this).hide();
      $(send_el).html('<span class="label label-success">已发</span>');
    });
  })
</script>