<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>签约信息</title>
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width">
<meta name="format-detection" content="email=no,address=no,telephone=no">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="expires" content="0">
<meta http-equiv="keywords" content="keyword1,keyword2,keyword3">
<meta http-equiv="description" content="This is my page">
<link href="/Public/css/normalize.css" rel="stylesheet" type="text/css">
<link href="/Public/css/bootstrap.min.css" rel="stylesheet">
<link href="/Public/css/bootstrap-theme.min.css" type="text/css">
<link href="/Public/css/peocess.css" rel="stylesheet" type="text/css">
<script src="/Public/js/jquery-1.9.1.js"></script>
<script src="/Public/js/bootstrap.min.js"></script>
<script src="/Public/js/mobiscroll.core-2.5.2.js" type="text/javascript"></script>
<script src="/Public/js/mobiscroll.core-2.5.2-zh.js" type="text/javascript"></script>
<link href="/Public/css/mobiscroll.core-2.5.2.css" rel="stylesheet" type="text/css">
<link href="/Public/css/mobiscroll.animation-2.5.2.css" rel="stylesheet" type="text/css">
<script src="/Public/js/mobiscroll.datetime-2.5.1.js" type="text/javascript"></script>
<script src="/Public/js/mobiscroll.datetime-2.5.1-zh.js" type="text/javascript"></script>
<!-- S 可根据自己喜好引入样式风格文件 -->
<script src="/Public/js/mobiscroll.android-ics-2.5.2.js" type="text/javascript"></script>
<link href="/Public/css/mobiscroll.android-ics-2.5.2.css" rel="stylesheet" type="text/css">
<!-- E 可根据自己喜好引入样式风格文件 -->
<script type="text/javascript">
$(function () {
    $("#endDate").val('').scroller($.extend({preset : 'date'}, { theme: "android-ics light", mode: "scroller", display: 'modal', lang: 'zh',dateFormat : "yy-mm-dd",dateOrder: 'yymmddDD',endYear: (new Date()).getFullYear() + 20})); 
    $("#startDate").scroller($.extend({preset : 'date'}, { theme: "android-ics light", mode: "scroller", display: 'modal', lang: 'zh',dateFormat : "yy-mm-dd",dateOrder: 'yymmddDD',endYear: (new Date()).getFullYear() + 20})); 
    changeRentType();
});
function getTotal(){
  var bookDeposit = parseInt(document.getElementById("bookDeposit").value);
  var rent = parseInt(document.getElementById("rent").value);
  var fee = parseInt(document.getElementById("fee").value);
  var how= document.getElementById("howRent").value;
  var total=0;
  var rentType = new Array();
  rentType = how.split("_");
  var fu = parseInt(rentType[1]);
  var deposit = parseInt(document.getElementById("deposit").value);
  total =deposit+((rent+fee)*fu);
  paytotal =deposit+((rent+fee)*fu)-bookDeposit;
  document.getElementById("total").setAttribute("value", total.toFixed(2));
  document.getElementById("paytotal").setAttribute("value", paytotal.toFixed(2));
}

function changeRentType(){
  var rent = parseInt(document.getElementById("rent").value);
  var how= document.getElementById("howRent").value;
  rentType = how.split("_");
  var ya = parseInt(rentType[0]);
  document.getElementById("deposit").value=rent*ya;
  getTotal();
}

</script>
<style type="text/css">
.money1 h2{display:block; text-align:center;}
.money1 h2 input {
    max-width: 100px;
    padding-left: 5px;
}

</style>
</head>

<body>
<div id="fwxx">
    <div class="container-fluid header">
        <div class="row-fluid">
            <div class="col-xs-4 header-icon1"><a href="javascript:window.history.back(-1)"><div class="retun-db-box"><img src="/Public/images/icon-db-retun.png" class="icon-db-retun"></div></a></div>
            <div class="col-xs-4 header-icon2"></div>
        </div>
    </div>
    <form action="<?php echo U('Home/Steward/order_checkin');?>" method="post" enctype="multipart/form-data" onsubmit="return checkform(this)">
      <div id="wrapper">
        <div>
          <div class="main main_margin_top44">
            <div class="main_hezi">
              <h2>
                <span class="title">签约信息</span>
              </h2>
              <h2>
                <p class="title_jd"><?php echo ($room_info['room_code']); ?><input type="hidden" name="room_id" value="<?php echo ($room_info['id']); ?>"><input type="hidden" name="account_id" value="<?php echo ($account_id); ?>">
                <?php if(!empty($schedule_id)): ?><input type="hidden" name="schedule_id" value="<?php echo ($schedule_id); ?>"><?php endif; ?>
              </p>
              </h2>
            </div>
            <div class="main_ban">
              <ul class="ban_tietu">
                <li class="ban_one zbj20 "><span class="text-letter-spacing-2ys">姓名：</span></li>
                <li class="ban_two">
                  <input type="text" value="<?php echo ($schedule_info['realname']); ?>" name="realName" id="realName" class="shuru">
                </li>
                <li class="xiantiao"></li>
              </ul>
            </div>
            <div class="main_ban">
              <ul class="ban_tietu">
                <li class="ban_one zbj20 "><span class="text-letter-spacing-2ys">电话：</span></li>
                <li class="ban_two">
                  <input type="text" value="<?php echo ($schedule_info['mobile']); ?>" name="mobile" id="mobile" class="shuru">
                </li>
                <li class="xiantiao"></li>
              </ul>
            </div>
            <div class="main_ban">
              <ul class="ban_tietu">
                <li class="ban_one zbj20 ">紧急联系人电话：</li>
                <li class="ban_two">
                  <input type="text" value="<?php echo ($schedule_info['contact2']); ?>" name="contact2" id="contact2" class="shuru">
                </li>
                <li class="xiantiao"></li>
              </ul>
            </div>
            <div class="main_ban">
              <ul class="ban_tietu">
                <li class="ban_one zbj20 "><span class="text-letter-spacing-4ys">身份证号：</span></li>
                <li class="ban_two">
                  <input type="text" value="<?php echo ($schedule_info['card_no']); ?>" class="shuru" name="idNo" id="idNo">
                </li>
                <li class="xiantiao"></li>
              </ul>
            </div>
            <div class="main_ban">
              <ul class="ban_tietu">
                <li class="ban_one zbj20 "><span class="text-letter-spacing-2ys">邮箱：</span></li>
                <li class="ban_two">
                  <input type="text" value="<?php echo ($schedule_info['email']); ?>" name="email" class="shuru" id="email">
                </li>
                <li class="xiantiao"></li>
              </ul>
            </div>
            <div class="main_ban">
              <ul class="ban_tietu">
                <li class="ban_one zbj20 "><span class="text-letter-spacing-2ys">房租：</span></li>
                <li class="ban_two">
                  <input type="text" value="<?php echo ($room_info['rent']); ?>" name="rent" id="rent" class="shuru" onchange="getTotal();">
                </li>
                <li class="xiantiao"></li>
              </ul>
            </div>
            <div class="main_ban">
              <ul class="ban_tietu">
                <li class="ban_one zbj20 "><span class="text-letter-spacing-2ys">人数：</span></li>
                <li class="ban_two">
                  <select class="sl_guanjia" name="personCount">
                    <option value="1">1人</option>
                    <option value="2">2人</option>
                    <option value="3">3人</option>
                    <option value="4">4人</option>
                  </select>
                </li>
                <li class=""></li>
              </ul>
            </div>
            <div class="main_ban">
              <ul class="ban_tietu">
                <li class="ban_one zbj20 "><span class="text-letter-spacing-4ys">合租人姓名：</span></li>
                <li class="ban_two">
                  <input type="text" value="" class="shuru" name="hzRealname" id="idNo">
                </li>
                <li class="xiantiao"></li>
              </ul>
            </div> 
            <div class="main_ban">
              <ul class="ban_tietu">
                <li class="ban_one zbj20 "><span class="text-letter-spacing-4ys">合租人电话：</span></li>
                <li class="ban_two">
                  <input type="text" value="" class="shuru" name="hzMobile" id="idNo">
                </li>
                <li class="xiantiao"></li>
              </ul>
            </div>
            <div class="main_ban">
              <ul class="ban_tietu">
                <li class="ban_one zbj20 "><span class="text-letter-spacing-4ys">合租人身份证：</span></li>
                <li class="ban_two">
                  <input type="text" value="" class="shuru" name="hzCardNo" id="idNo">
                </li>
                <li class="xiantiao"></li>
              </ul>
            </div>
            <div class="main_ban">
              <ul class="ban_tietu">
                <li class="ban_one zbj20 "><span class="text-letter-spacing-3ys">服务费：</span></li>
                <li class="ban_two">
                  <input type="text" value="<?php echo ($room_info['room_fee']); ?>" name="fee" id="fee" class="shuru"  onchange="getTotal();">
                </li>
                <li class="xiantiao"></li>
              </ul>
            </div>
            </div>
            <div class="main_ban">
              <ul class="ban_tietu">
                <li class="ban_one zbj20 "><span class="text-letter-spacing-4ys">付款方式：</span></li>
                <li class="ban_two">
                  <select name="rentType" id="howRent" class="sl_guanjia" onchange="changeRentType();">
                    <option value="2_1">押二付一</option>
                    <option value="1_3">押一付三</option>
                    <option value="2_2">押二付二</option>
                    <option value="2_3">押二付三</option>
                    <option value="2_6">押二付六</option>
                    <option value="0_1">押零付一</option>
                    <option value="2_12">押二付年</option>
                  </select>
                </li>
                <li class=""></li>
              </ul>
            </div>
            <div class="main_ban">
              <ul class="ban_tietu">
                <li class="ban_one zbj20 ">租期开始日：</li>
                <li class="ban_two">
                  <input type="text" id="startDate" name="startDate" class="shuru" value="<?php echo ($today); ?>">
                </li>
                <li class="xiantiao"></li>
              </ul>
            </div>
            <div class="main_ban">
              <ul class="ban_tietu">
                <li class="ban_one zbj20 ">租期截止日：</li>
                <li class="ban_two">
                  <input type="text" value="" id="endDate" name="endDate" class="shuru" readonly="">
                </li>
                <li class="xiantiao"></li>
              </ul>
            </div>
            <!-- <div class="main_ban">
              <ul class="ban_tietu">
                <li class="ban_one zbj20 "><span class="text-letter-spacing-2ys">总水：</span></li>
                <li class="ban_two">
                  <input type="text" value="" name="zS" id="zS" class="shuru">
                </li>
                <li class="xiantiao"></li>
              </ul>
            </div>
            <div class="main_ban">
              <ul class="ban_tietu">
                <li class="ban_one zbj20 "><span class="text-letter-spacing-2ys">总电：</span></li>
                <li class="ban_two">
                  <input type="text" value="" name="zD" id="zD" class="shuru">
                </li>
                <li class="xiantiao"></li>
              </ul>
            </div>
            <div class="main_ban">
              <ul class="ban_tietu">
                <li class="ban_one zbj20 "><span class="text-letter-spacing-2ys">总气：</span></li>
                <li class="ban_two">
                  <input type="text" value="" name="zQ" id="zQ" class="shuru">
                </li>
                <li class="xiantiao"></li>
              </ul>
            </div> -->
            <div class="main_ban">
              <ul class="ban_tietu">
                <li class="ban_one zbj20 "><span class="text-letter-spacing-4ys">房间电表：</span></li>
                <li class="ban_two">
                  <input type="text" value="" name="roomD" id="roomD" class="shuru">
                </li>
                <li class="xiantiao"></li>
              </ul>
            </div>
            <div class="main_ban">
              <ul class="ban_tietu">
                <li class="ban_one zbj20 "><span class="text-letter-spacing-4ys">租客合影：</span></li>
                <li class="ban_two">
                  <input type="file" value="" name="photo[]" accept="image/jpg,image/jpeg,image/png,image/gif" multiple="true"  id="" class="shuru">
                </li>
                <li class="xiantiao"></li>
              </ul>
            </div>
            <div class="main_ban">
              <ul class="ban_tietu">
                <li class="ban_one zbj20 "><span class="text-letter-spacing-2ys">押金：</span></li>
                <li class="ban_two">
                  <input type="text" value="" name="deposit" id="deposit" class="shuru" onblur="getTotal();">
                </li>
                <li class="xiantiao"></li>
              </ul>
            </div>
            <div class="money1">
              <h2 class="money_box1">换房余额抵扣<input type="checkbox" name="" value=""> </h2>
            </div>
            <div class="money1">
              <h2 class="money_box1">合同金额:￥<input id="total" name="total" value="" class="money_box1_shuru1"> </h2>
            </div>
             <div class="money1">
              <h2 class="money_box1">首付:￥<input id="paytotal" name="paytotal" class="money_box1_shuru1" value=""> </h2>
            </div>
            
              <div class="money1">
                <h2 class="money_box1">缴定抵扣:￥<input id="bookDeposit" name="bookDeposit" <?php if(!empty($schedule_info["money"])): ?>value="<?php echo ($schedule_info['money']); ?>" <?php else: ?> value="0"<?php endif; ?> class="money_box1_shuru1" readonly="readonly"> </h2>
              </div>

          </div>
        </div>
      </div>
      <div class="button">
        <div class="button_ds"> <img src="/Public/images/joinus.png" class="button_icon">
          <input type="submit" value="保存" class="button_baoc">
        </div>
      </div>
      </form>
<script type="text/javascript">
            function checkform(obj){
            if (!/^[1-9]{1}[0-9]{14}$|^[1-9]{1}[0-9]{16}([0-9]|[xX])$/.test(document.getElementById("idNo").value)){
                alert('请输入正确的身份证号');
                return false;
            }
            var mobile = document.getElementById("mobile");
            var realName = document.getElementById("realName");
            var reg = new RegExp("^[0-9]+\.{0,1}[0-9]{0,2}$");
            if(! /^1\d{10}$/.test(mobile.value)){
                alert("请输入正确的电话号码！");
                return false;
            }
            if(realName.value=="" || realName.value==null){
                alert("真实姓名不能为空！");
                return false;
            }
            if(document.getElementById("startDate").value == "" || document.getElementById("startDate").value==null){
                alert("开始日期不能为空");
                return false;   
            }
            if(document.getElementById("endDate").value == "" || document.getElementById("endDate").value==null){
                alert("截止日期不能为空");
                return false;   
            }
            
            if(document.getElementById("rent").value == "" || document.getElementById("rent").value==null){
                alert("租金不能为空");
                return false;   
            }
            if(document.getElementById("fee").value == "" || document.getElementById("fee").value==null){
                alert("服务费不能为空");
                return false;   
            }

            if(document.getElementById("roomD").value == "" || document.getElementById("roomD").value==null){
                alert("房间电表不能为空");
                return false;   
            }
            
            if(!reg.test(document.getElementById("rent").value)){
                alert("请重新输入租金(只能输入数字,请不要输入符号)");
                return false;
            }
            
            
            if(!reg.test(document.getElementById("deposit").value)){
                alert("请重新输入押金(只能输入数字,请不要输入符号)");
                return false;
            }
            if(!reg.test(document.getElementById("fee").value)){
                alert("请重新输入服务费(只能输入数字,请不要输入符号)");
                return false;
            }
            if(document.getElementById("contact2").value == "" || document.getElementById("contact2").value==null){
                alert("紧急联系人不能为空");
                return false;   
            }

            /*
            if(document.getElementById("cardZPic").value == "" || document.getElementById("cardZPic").value==null){
                alert("请上传身份证正面!");
                return false;   
            }
            
            if(document.getElementById("cardFPic").value == "" || document.getElementById("cardFPic").value==null){
                alert("请上传身份证反面!");
                return false;   
            }
            
            if(document.getElementById("howpayxj").checked){
                if(document.getElementById("pzimg").value == "" || document.getElementById("pzimg").value==null){
                    alert("付款凭证必须上传");
                    return false;
                }
            }
            if(document.getElementById("ht1").value == "" || document.getElementById("ht1").value==null){
                alert("纸质合同不能为空");
                return false;   
            }
            
            */
            if(!reg.test(document.getElementById("zS").value)){
                alert("请重新输入总水(只能输入数字,请不要输入符号)");
                return false;
            }
            
            if(!reg.test(document.getElementById("zQ").value)){
                alert("请重新输入总电(只能输入数字,请不要输入符号)");
                return false;
            }
            
            if(!reg.test(document.getElementById("zD").value)){
                alert("请重新输入总气(只能输入数字,请不要输入符号)");
                return false;
            }
            
            if(!reg.test(document.getElementById("roomD").value)){
                alert("请重新输入房屋电表(只能输入数字,请不要输入符号)");
                return false;
            }
}
</script>

</body></html>