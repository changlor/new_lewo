<?php if (!defined('THINK_PATH')) exit();?><!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a> <a>租客帐号管理</a> <a>帐号列表</a> <a class="current">合同信息</a></div>
  </div>
<!--End-breadcrumbs-->
<div class="container-fluid">
    <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>合同信息</h5>
          <a href="javascript:window.history.go(-1)" class="label label-info pull-left">返回</a>
        </div>
        <div class="widget-content nopadding">
          <form action="" method="post" class="form-horizontal form-inline">
            <div class="control-group">
              <label class="control-label">真实姓名 :</label>
              <div class="controls">
                <a href="<?php echo U('Admin/Tenant/tenant_info',array('account_id'=>$contract_info['account_info']['id']));?>" class="btn btn-mini btn-info"><?php echo ($contract_info["account_info"]["realname"]); ?></a>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">电话 :</label>
              <div class="controls">
                <?php echo ($contract_info["account_info"]["mobile"]); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">身份证 :</label>
              <div class="controls">
                <?php echo ($contract_info["account_info"]["card_no"]); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">小区楼层 :</label>
              <div class="controls">
                <?php echo ($contract_info["house_info"]["area_name"]); ?>(<?php echo ($contract_info["house_info"]["building"]); ?>-<?php echo ($contract_info["house_info"]["floor"]); ?>-<?php echo ($contract_info["house_info"]["door_no"]); ?>)
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">房间编码 :</label>
              <div class="controls">
                <?php echo ($contract_info["room_info"]["room_code"]); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">房位编码 :</label>
              <div class="controls">
                <?php echo ($contract_info["room_info"]["bed_code"]); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">账单号 :</label>
              <div class="controls">
                <?php echo ($contract_info["pro_id"]); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">押金 :</label>
              <div class="controls">
                <input type="number" value="<?php echo ($contract_info["deposit"]); ?>" name="deposit" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">缴定金额 :</label>
              <div class="controls">
                <?php echo ($contract_info["book_deposit"]); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">使用的余额 :</label>
              <div class="controls">
                <?php echo ($contract_info["balance"]); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">房租类型 :</label>
              <div class="controls">
                <?php echo ($contract_info["rent_type_name"]); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">缴费周期 :</label>
              <div class="controls">
                <?php echo ($contract_info["period"]); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">合同开始日 :</label>
              <div data-date="<?php echo ($contract_info["start_time"]); ?>" data-date-format="yyyy-mm-dd"  class="input-append date datepicker" style="margin-left:10px;">
                <input type="text" name="start_time" value="<?php echo ($contract_info["start_time"]); ?>"  class="span11" style="width:100px;">
                <span class="add-on"><i class="icon-th"></i></span>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">合同结束日 :</label>
              <div data-date="<?php echo ($contract_info["end_time"]); ?>" data-date-format="yyyy-mm-dd"  class="input-append date datepicker" style="margin-left:10px;">
                <input type="text" name="end_time" value="<?php echo ($contract_info["end_time"]); ?>"  class="span11" style="width:100px;">
                <span class="add-on"><i class="icon-th"></i></span>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">房租到期日 :</label>
              <div data-date="<?php echo ($contract_info["rent_date"]); ?>" data-date-format="yyyy-mm-dd"  class="input-append date datepicker" style="margin-left:10px;">
                <input type="text" name="rent_date" value="<?php echo ($contract_info["rent_date"]); ?>"  class="span11" style="width:100px;">
                <span class="add-on"><i class="icon-th"></i></span>
              </div>
              <labe><input type="checkbox" name="is_can_create_bill" value="1">是否生成未支付的房租账单</labe>
            </div>
            <div class="control-group">
              <label class="control-label">实际退房日 :</label>
              <div data-date="" data-date-format="yyyy-mm-dd"  class="input-append date datepicker" style="margin-left:10px;">
                <input type="text" name="actual_end_time" value="<?php echo ($contract_info["actual_end_time"]); ?>"  class="span11" style="width:100px;">
                <span class="add-on"><i class="icon-th"></i></span>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">租金 :</label>
              <div class="controls">
                <input type="text" value="<?php echo ($contract_info["rent"]); ?>" name="rent" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">服务费 :</label>
              <div class="controls">
                <input type="text" value="<?php echo ($contract_info["fee"]); ?>" name="fee" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">人数 :</label>
              <div class="controls">
                <input type="number" value="<?php echo ($contract_info["person_count"]); ?>" name="person_count" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">租期开始水表 :</label>
              <div class="controls">
                <input type="number" value="<?php echo ($contract_info["zs"]); ?>" name="zs" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">租期开始电表 :</label>
              <div class="controls">
                <input type="number" value="<?php echo ($contract_info["zd"]); ?>" name="zd" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">租期开始气表 :</label>
              <div class="controls">
                <input type="number" value="<?php echo ($contract_info["zq"]); ?>" name="zq" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">租期开始房间电表 :</label>
              <div class="controls">
                <input type="number" value="<?php echo ($contract_info["roomd"]); ?>" name="roomd" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">合同总金额 :</label>
              <div class="controls">
                <?php echo ($contract_info["total_fee"]); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">合同状态 :</label>
              <div class="controls">
                <select name="contract_status">
                <?php if(is_array($contract_status_arr)): $i = 0; $__LIST__ = $contract_status_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if($contract_info['contract_status'] == $key): ?>selected<?php endif; ?>><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">添加日志 :</label>
              <div class="controls">
                <textarea name="modify_log" cols="40" rows="3"></textarea>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">日志 :</label>
              <div class="controls">
                <?php echo ($pay_info["modify_log"]); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">支付金额 :</label>
              <div class="controls">
                <?php echo ($pay_info["pay_money"]); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">支付帐号 :</label>
              <div class="controls">
                <input type="text" value="<?php echo ($pay_info["buyer_email"]); ?>" disabled />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">支付状态 :</label>
              <div class="controls">
                <select disabled>
                  <option value="0" <?php if($pay_info['pay_status'] == 0): ?>selected<?php endif; ?>>未支付</option>
                  <option value="1" <?php if($pay_info['pay_status'] == 1): ?>selected<?php endif; ?>>已支付</option>
                </select>
              </div>
            </div>
            
            <div class="control-group">
              <label class="control-label">支付时间 :</label>
              <div class="controls">
                <input type="text" disabled value="<?php echo ($pay_info["pay_time"]); ?>"  class="span11" style="width:200px;">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">
                <input type="hidden" name="account_id" value="<?php echo ($account_id); ?>">
                <input type="hidden" name="room_id" value="<?php echo ($room_id); ?>">
                <input type="hidden" name="id" value="<?php echo ($contract_info["id"]); ?>">
                <input type="hidden" name="pro_id" value="<?php echo ($contract_info["pro_id"]); ?>">
                <button type="submit" class="btn btn-success" onclick="if(!confirm('\n\t确认修改?\n')){return false;}">修改</button>
              </label>
            </div>
            <!-- <div class="form-actions">
              <button type="submit" class="btn btn-success">添加</button>
            </div> -->
          </form>
        </div>
      </div>
  </div>
</div>
<!--main-container-part-->