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
  .contract-table {
    table-layout:fixed;
    width: 100%;
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
    padding: 1px;
    text-align:center;
    word-wrap:break-word;
  }
  #top {
    position: fixed;
    right: 30px;
    bottom:30px;
    z-index: 9;
    cursor: pointer;
  }
  #down {
    position: fixed;
    right: 27px;
    bottom:10px;
    z-index: 9;
    cursor: pointer;
  }
</style>
</head>
<body>
<!--main-container-part-->
<div id="top"><span class="badge badge-inverse icon-arrow-up">Top</span></div>
<div id="down"><span class="badge badge-inverse icon-arrow-down">Down</span></div>
<div>
    <div class="widget-box">
      <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
        <h5>账单列表</h5>
        <a href="<?php echo U('Admin/Index/index');?>" class="label label-info pull-left">首页</a>
      </div>
      <div class="widget-content nopadding">
      
     <form action="<?php echo U('Admin/Pay/all_bill');?>" method="get" class="form-inline">
        <label>
          <a class="btn">关键字:</a><input type="text" name="search" class="span11" style="width:100px; margin-bottom: 0px" placeholder="关键字" <?php if(!empty($search)): ?>value="<?php echo ($search); ?>"<?php else: ?>value=""<?php endif; ?> >
        </label>
        <label>
          <a class="btn">房间编号:</a><input type="text" name="room_code" class="span11" style="width:100px; margin-bottom: 0px" placeholder="房间编号" <?php if(!empty($room_code)): ?>value="<?php echo ($room_code); ?>"<?php else: ?>value=""<?php endif; ?> >
        </label>
        <label>
          <a class="btn">租客信息:</a><input type="text" name="account_key" class="span11" style="width:100px; margin-bottom: 0px" placeholder="姓名/电话" <?php if(!empty($account_key)): ?>value="<?php echo ($account_key); ?>"<?php else: ?>value=""<?php endif; ?> >
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
            <?php if(is_array($year_list)): $i = 0; $__LIST__ = $year_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if($input_year == $key): ?>selected<?php endif; ?>><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
          </select>
          年
          <select name="input_month" style="width:80px;">
            <option selected="selected" value="">请选择</option>
            <?php if(is_array($month_list)): $i = 0; $__LIST__ = $month_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if($input_month == $key): ?>selected<?php endif; ?>><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
           </select>
           月
        </label>
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
        <br/>
        <label>
          <a class="btn">类型:</a>
          <select name="bill_type[]" multiple style="width:200px;">
            <?php if(is_array($bill_type_list)): $i = 0; $__LIST__ = $bill_type_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if(in_array($key,$bill_type)): ?>selected<?php endif; ?> ><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
           </select>
        </label>
        <label>
            <a class="btn">时间搜索:</a>
            <select name="search_time" style="width:100px;">
              <option selected="selected" value="">请选择</option>
              <option <?php if($search_time == 'payDate'): ?>selected<?php endif; ?> value="payDate">支付时间</option>
              <option <?php if($search_time == 'htStartDate'): ?>selected<?php endif; ?> value="htStartDate">合同开始</option>
              <option <?php if($search_time == 'htEndDate'): ?>selected<?php endif; ?> value="htEndDate">合同结束</option>
              <option <?php if($search_time == 'rentDate'): ?>selected<?php endif; ?> value="rentDate">房租到期</option>
              <option <?php if($search_time == 'zcht'): ?>selected<?php endif; ?> value="zcht">正常合同</option>
            </select>
        </label>
        <label>
            <div data-date="" data-date-format="yyyy-mm-dd" class="input-append date datepicker">
              <input <?php if(!empty($start_time)): ?>value="<?php echo ($start_time); ?>"<?php else: ?>value=""<?php endif; ?> type="text" name="start_time" class="span11" style="width:100px;" >
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
            <option <?php if($sort_type == 'roomCode'): ?>selected<?php endif; ?> value="roomCode">房间编号</option>
            <option <?php if($sort_type == 'startDate'): ?>selected<?php endif; ?> value="startDate">租期开始</option>
            <option <?php if($sort_type == 'rentDate'): ?>selected<?php endif; ?> value="rentDate">缴费到期</option>
            <option <?php if($sort_type == 'tbq'): ?>selected<?php endif; ?> value="tbq">到期天数</option>
            <option <?php if($sort_type == 'inputMonth'): ?>selected<?php endif; ?> value="inputMonth">批次</option>
          </select>
          <select name="sort" style="width:100px;">
            <option <?php if($sort == 'asc'): ?>selected<?php endif; ?> selected="selected" value="1">从小到大</option>
            <option <?php if($sort == 'desc'): ?>selected<?php endif; ?> value="2">从大到小</option>
          </select>
        </label>  
        <br/>
        <label>
          <labe><a class="btn">显示数目:</a><input type="number" style="width:100px" name="page_count" <?php if(!empty($page_count)): ?>value="<?php echo ($page_count); ?>"<?php else: ?>value=""<?php endif; ?> placeholder="默认100条"></labe> 
          <label for=""><a class="btn">搜索总数:<?php echo ($count); ?></a></label>
          <button type="submit" class="btn btn-success">搜索</button>
          <a class="btn btn-info" href="<?php echo U('Admin/Pay/all_bill');?>">重置</a>
          <!-- <a type="submit" href="<?php echo U('Admin/Pay/all_bill',array('is_download'=>1));?>" class="btn btn-info">下载</a> -->
          <input type="submit" name="is_download" class="btn btn-info" value="下载">
          <a href="<?php echo U('Admin/Pay/all_bill',array('is_show'=>0));?>" class="btn btn-danger">已删除的账单</a>
        </label> 
      </form>
        <table class=" table-bordered contract-table" id="table-thead-02" style="display: none; position: fixed; top:0px; " >
            <colgroup>
              <col style="width:3%">
              <col style="width:5%">
              <col style="width:4%">
              <col style="width:7%">
              <col style="width:7%">
              <col style="width:7%">
              <col style="width:7%">
              <col style="width:6%">
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
              <col style="width:5%">
              <col style="width:5%">
              <col style="width:5%">
              <col style="width:6%">
              <col style="width:4%">
              <col style="width:4%">
              <col style="width:4%">
            </colgroup>
          <thead>
            <tr>
              <th>类型</th>
              <th>房间编号</th>
              <th>床位编号</th>
              <th>小区楼层</th>
              <th>姓名</th>
              <th>电话</th>
              <th>合同时间</th>
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
              <th>优惠</th>
              <th>应付</th>
              <th>实收</th>
              <th>支付时间</th>
              <th>支付方式</th>
              <!-- <th>发送状态</th> -->
              <th>支付状态</th>
              <th>操作</th>
            </tr>
          </thead>
        </table>
        <table class=" table-bordered contract-table">
            <colgroup>
              <col style="width:3%">
              <col style="width:5%">
              <col style="width:4%">
              <col style="width:7%">
              <col style="width:7%">
              <col style="width:7%">
              <col style="width:7%">
              <col style="width:6%">
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
              <col style="width:5%">
              <col style="width:5%">
              <col style="width:5%">
              <col style="width:6%">
              <col style="width:4%">
              <col style="width:4%">
              <col style="width:4%">
            </colgroup>
          <thead id="table-thead">
            <tr>
              <th>类型</th>
              <th>房间编号</th>
              <th>床位编号</th>
              <th>小区楼层</th>
              <th>姓名</th>
              <th>电话</th>
              <th>合同时间</th>
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
              <th>优惠</th>
              <th>应付</th>
              <th>实收</th>
              <th>支付时间</th>
              <th>支付方式</th>
              <th>支付状态</th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody>
            <?php if(is_array($contract_list)): $i = 0; $__LIST__ = $contract_list;if( count($__LIST__)==0 ) : echo "没有搜索到数据......" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
              <td><?php echo ($vo["bill_type_name"]); ?></td>
              <td><?php echo ($vo["room_code"]); ?></td>
              <td><?php echo ($vo["bed_code"]); ?></td>
              <td><a class="tip-bottom" data-original-title="管家：<?php echo ($vo["gj_nickname"]); ?>-<?php echo ($vo["gj_realname"]); ?>"><?php echo ($vo["area_name"]); ?><br/>(<?php echo ($vo["building"]); ?>-<?php echo ($vo["floor"]); ?>-<?php echo ($vo["door_no"]); ?>)</a></td>
              <td><a href="<?php echo U('Admin/Tenant/contract_info',array('account_id'=>$vo['account_id'],'room_id'=>$vo['room_id']));?>" target="_blank" <?php if($vo["contract_status"] != 1): ?>class="btn btn-inverse btn-mini"<?php else: ?> class="btn btn-info btn-mini"<?php endif; ?> ><?php echo ($vo["realname"]); ?></a></td>
              <td><?php echo ($vo["mobile"]); ?></td>
              <td><a class="tip-bottom" data-original-title="开始"><?php echo ($vo["start_time"]); ?></a><br/><a class="tip-bottom" data-original-title="结束"><?php echo ($vo["end_time"]); ?></a></td>
              <td><?php echo ($vo["rent_date_to"]); ?></td>
              <td><a style="display: block" class="tip-bottom" data-original-title="租客应缴费时间:<?php echo ($vo["should_date"]); ?>"><?php echo ($vo["last_date"]); ?></a></td>
              <td>
                <?php if($vo["count_down_days"] < 0): ?><label class="label label-important"><?php echo ($vo["count_down_days"]); ?></label>
                  <?php elseif($vo["count_down_days"] < 5): ?>
                  <label class="label label-warning"><?php echo ($vo["count_down_days"]); ?></label>
                  <?php else: ?>
                  <label class="label label-success"><?php echo ($vo["count_down_days"]); ?></label><?php endif; ?>
              </td>
              <td><?php echo ($vo["period"]); ?></td>
              <td>
                <a <?php if($vo['bill_type'] == 3): ?>href="<?php echo U('Admin/Houses/check_bill',array('house_id'=>$vo['house_id'],'year'=>$vo['input_year'],'month'=>$vo['input_month']));?>"<?php endif; ?>><?php echo ($vo["input_year"]); ?>年<br/><?php echo ($vo["input_month"]); ?>月</a>
              </td>
              <td><?php if(!empty($vo["ht_deposit"])): echo ($vo["ht_deposit"]); endif; ?></td>
              <td><?php if(!empty($vo["rent"])): echo ($vo["rent"]); endif; ?></td>
              <td><?php if(!empty($vo["service_fee"])): echo ($vo["service_fee"]); endif; ?></td>
              <td><?php if(!empty($vo["SDQtotal"])): ?><a style="display: block" class="tip-bottom" data-original-title="<?php echo ($vo["SDQtotal_des"]); ?>"><?php echo ($vo["SDQtotal"]); ?></a><?php endif; ?></td>
              <td><?php if(!empty($vo["wg_fee"])): echo ($vo["wg_fee"]); endif; ?></td>
              <td><?php if($vo["wx_fee"] != 0): ?><a style="display: block" class="tip-bottom" data-original-title="<?php echo ($vo["wx_des"]); ?>"><?php echo ($vo["wx_fee"]); ?></a><?php endif; ?></td>
              <td><?php if($vo["favorable"] != 0): ?><a style="display: block" class="tip-bottom" data-original-title="<?php echo ($vo["favorable_des"]); ?>">-<?php echo ($vo["favorable"]); ?></a><?php endif; ?></td>
              <td><a class="tip-bottom" <?php if($vo['bill_des'] != ''): ?>data-original-title="账单描述：<?php echo ($vo["bill_des"]); ?>"<?php endif; ?>><?php echo ($vo["price"]); ?></a></td>
              <td><?php echo ($vo["pay_money"]); ?></td>
              <td><?php if($vo['pay_time'] != 0): echo ($vo["pay_time"]); endif; ?></td>
              <td><?php echo ($vo["pay_type_name"]); ?></td>
              <!-- <td id="send<?php echo ($vo["id"]); ?>">
                <?php if($vo["is_send"] == 1 ): ?><span class="label label-success">已发</span>
                  <?php else: ?>
                  <span class="label label-important">未发</span><?php endif; ?>
              </td> -->
              <td>
                <?php if($vo["pay_status"] == 1): ?><span class="label label-success">已付</span>
                  <?php else: ?>
                  <span class="label label-important">未付</span><?php endif; ?>
              </td>
              <td>
<?php if(($_SESSION['admin_type'] == 99) OR ($_SESSION['admin_type'] == 4) ): if($vo["is_show"] == 1): ?><a href="<?php echo U('Admin/Pay/edit_pay',array('pro_id'=>$vo['pro_id']));?>" class="btn btn-success btn-mini">修改</a>
               <?php if($vo["pay_status"] != 1): if($vo["is_send"] != 1): ?><a data-id="<?php echo ($vo["pro_id"]); ?>" class="btn btn-info btn-mini update-send">发送</a><?php endif; ?>
                <a href="<?php echo U('Admin/Pay/delete_pay',array('pro_id'=>$vo['pro_id']));?>" class="btn btn-danger btn-mini" onclick="if(!confirm('确定删除')) return false;">删除</a><?php endif; ?>
              <?php else: ?>
                <a href="<?php echo U('Admin/Pay/back_pay',array('pro_id'=>$vo['pro_id']));?>" class="btn btn-success btn-mini">恢复</a>
                <a href="<?php echo U('Admin/Pay/clear_pay',array('pro_id'=>$vo['pro_id']));?>" class="btn btn-danger btn-mini" onclick="if(!confirm('确定删除')) return false;">彻底删除</a><?php endif; ?>
<?php else: ?>
              <a href="<?php echo U('Admin/Pay/check_log',array('pro_id'=>$vo['pro_id']));?>" class="btn btn-success btn-mini tip-bottom" <?php if($vo['bill_des'] != ''): ?>data-original-title="账单描述：<?php echo ($vo["bill_des"]); ?>"<?php endif; ?>>查看</a><?php endif; ?>
              </td>

            </tr><?php endforeach; endif; else: echo "没有搜索到数据......" ;endif; ?>
            <tr>
              <td colspan="12"></td>
              <td><b><?php echo ($total_deposit); ?></b></td>
              <td><b><?php echo ($total_rent); ?></b></td>
              <td><b><?php echo ($total_service_fee); ?></b></td>
              <td><b><?php echo ($total_sdq); ?></b></td>
              <td><b><?php echo ($total_wg_fee); ?></b></td>
              <td><b><?php echo ($total_wx_fee); ?></b></td>
              <td><b><?php echo ($total_favorable_fee); ?></b></td>
              <td><b><?php echo ($total_price); ?></b></td>
              <td><b><?php echo ($total_pay_money); ?></b></td>
              <td colspan="5"></td>
            </tr>
            <tr><td colspan="26" style="text-align: center;"><?php echo ($page); ?></td></tr>
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
<script src="/Public/admin/js/jquery.uniform.js"></script> 
<script src="/Public/admin/js/select2.min.js"></script> 
<script src="/Public/admin/js/jquery.ui.custom.js"></script>
<script src="/Public/admin/js/jquery.dataTables.min.js"></script>  
<script src="/Public/admin/js/matrix.js"></script>
<script src="/Public/admin/js/matrix.tables.js"></script>
<script src="/Public/admin/js/matrix.form_common.js"></script>

</body>
</html>
<script type="text/javascript">
  $(window).scroll(function(){
    var s= $(window).scrollTop();
    var el = $('#table-thead');
    var el2 = $('#table-thead-02');
    el_top = el.offset().top;
    if ( s >= el_top) {
      el2.show();
    } else {
      el2.hide();
    }
  })
  $('#top').click(function(){
    $('body').stop(0,0).animate({scrollTop: '0px'}, 1000);
  });
  $('#down').click(function(){
    var b_top = $(document).height();
    $('body').stop(0,0).animate({scrollTop: b_top+'px'}, 1000);
  });
  $(window).keydown(function(event){
    switch(event.keyCode) {
      case 13:
        $("button[type=submit]").click();
      break;
    }
  });
</script>
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
           data: "pro_id="+id,
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