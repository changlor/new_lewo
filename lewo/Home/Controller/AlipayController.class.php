<?php
namespace Home\Controller;
use Think\Controller;
class AlipayController extends Controller {
    var $alipay_config = array();

    public function __construct(){
        parent::__construct();
        if ( is_weixin() ) {
            $this->browser_info();
            die();
        }  
        header("Content-type:text/html;charset=utf-8");
        $this->alipay_config['partner'] = '2088421636193269';
        $this->alipay_config['seller_id'] = '2088421636193269';
        $this->alipay_config['key'] = 'xa8l7wy0z7gso8jxx3ti5wfbkj5k8i40';
        $this->alipay_config['notify_url'] = "http://".$_SERVER['HTTP_HOST'].U("Home/Alipay/notify_url");
        $this->alipay_config['return_url'] = "http://".$_SERVER['HTTP_HOST'].U("Home/Alipay/return_url");
        $this->alipay_config['sign_type'] = strtoupper('MD5');
        $this->alipay_config['input_charset'] = strtolower('utf-8');
        $this->alipay_config['cacert'] = getcwd().'\\cacert.pem';
        $this->alipay_config['transport'] = 'http';
        $this->alipay_config['payment_type'] = '1';
        $this->alipay_config['service'] = 'alipay.wap.create.direct.pay.by.user';
    }
    public function browser_info(){
        $this->display("/browser_info");
    }

    public function pay(){
        import("Vendor.Alipay.lib.alipay_submit");

        /**************************请求参数**************************/

        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = I("WIDout_trade_no");

        //订单名称，必填
        $subject = I("WIDsubject");

        //付款金额，必填
        $total_fee = I("WIDtotal_fee");

        //收银台页面上，商品展示的超链接，必填
        /*$show_url = I("WIDshow_url");*/
        $show_url = "http://".$_SERVER['HTTP_HOST'];

        //商品描述，可空
        $body = I("WIDbody");

        /************************************************************/

        //构造要请求的参数数组，无需改动
        $parameter = array(
                "service"       => $this->alipay_config['service'],
                "partner"       => $this->alipay_config['partner'],
                "seller_id"  => $this->alipay_config['seller_id'],
                "payment_type"  => $this->alipay_config['payment_type'],
                "notify_url"    => $this->alipay_config['notify_url'],
                "return_url"    => $this->alipay_config['return_url'],
                "_input_charset"    => trim(strtolower($this->alipay_config['input_charset'])),
                "out_trade_no"  => $out_trade_no,
                "subject"   => $subject,
                "total_fee" => $total_fee,
                "show_url"  => $show_url,
                "app_pay"   => "Y",//启用此参数能唤起钱包APP支付宝
                "body"  => $body,
                //其他业务参数根据在线开发文档，添加参数.文档地址:https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.2Z6TSk&treeId=60&articleId=103693&docType=1
                //如"参数名"    => "参数值"   注：上一个参数末尾需要“,”逗号。
                
        );

        //建立请求
        $alipaySubmit = new \AlipaySubmit($this->alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"post", "确认");
        echo $html_text;
    }

    /**
    *  [同步]
    **/
    public function return_url(){
        import("Vendor.Alipay.lib.alipay_notify");
        //计算得出通知验证结果
        $alipayNotify = new \AlipayNotify($this->alipay_config);
        $verify_result = $alipayNotify->verifyReturn();
        if($verify_result) {
            $out_trade_no   = $_GET['out_trade_no'];//商户订单号
            $trade_no       = $_GET['trade_no'];//支付宝交易号         
            $trade_status   = $_GET['trade_status'];//交易状态
            $total_fee      = $_GET['total_fee'];//本次交易支付的订单金额，单位为人民币（元）

            if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {

                $MPay           = M("pay");
                $pay_info       = $MPay->where(array("pro_id"=>$out_trade_no))->find();
                $price          = $pay_info['price'];
                
                if ( $price == $total_fee) {
                    $this->success("支付成功!",U("Home/Tenant/index"));
                } else {
                    $this->error("支付失败!金额不符合",U("Home/Tenant/index"));
                }

            }
            else {
              $this->error("支付失败!",U("Home/Tenant/index"));
            }
        }
        else {
            //验证失败
            //如要调试，请看alipay_notify.php页面的verifyReturn函数
            $this->success("验证失败!",U("Home/Tenant/index"));
        }
    }

    /**
    * [异步]
    **/
    public function notify_url(){
        import("Vendor.Alipay.lib.alipay_notify");
        //计算得出通知验证结果
        $alipayNotify = new \AlipayNotify($this->alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        if($verify_result) {//验证成功
            $out_trade_no   = $_POST['out_trade_no'];//商户订单号
            $trade_no       = $_POST['trade_no'];//支付宝交易号
            $trade_status   = $_POST['trade_status'];//交易状态
            $total_fee      = $_POST['total_fee'];//
            $buyer_email    = $_POST['buyer_email'];//买家支付宝账号
            $seller_email   = $_POST['seller_email'];//卖家支付宝账号

            if($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS') {
 
                $MPay           = M("pay");
                $MContract      = M("contract");
                $MPchargeBill   = M("charge_bill");
                $charge_info    = $MPchargeBill->where(array("pro_id"=>$out_trade_no))->find();
                $pay_info       = $MPay->where(array("pro_id"=>$out_trade_no))->find();
                $price          = $pay_info['price'];
                $pro_id         = $pay_info['pro_id'];
                
                if ( $price == $total_fee) {

                    switch ($pay_info['bill_type']) {
                        case 1://定金
                            # code...
                            break;
                        case 2://合同
                            $contract_info  = $MContract->where(array("pro_id"=>$out_trade_no))->find();
                            $account_id  = $contract_info['account_id'];
                            $room_id     = $contract_info['room_id'];
                            $start_time  = $contract_info['start_time']; //合同开始日
                            $end_time    = $contract_info['end_time']; //租房结束日期
                            $DRoom = D("room");
                            //修改房屋信息
                            $DRoom->setRoomStatus($room_id,2);
                            $DRoom->setRoomPerson($room_id,$account_id);
                            $contract_save['contract_status'] = 1;
                            //修改合同的状态
                            $MContract->where(array("pro_id"=>$out_trade_no))->save($contract_save);
                            //发送门锁密码
                            $MAccount = M("account");
                            $account_info = $MAccount->where(array("id"=>$account_id))->find();
                            $MRoom = M("room");
                            $room_code = $MRoom->where(array("id"=>$room_id))->getField("room_code");
                            $contract_info = $MContract->field("start_time,end_time")->where(array("pro_id"=>$out_trade_no))->find();
                            import("Vendor.DDing.DDing");
                            $DDing = new \DDing();
                            $opt['room_code'] = $room_code;
                            $opt['mobile'] = $account_info['mobile'];
                            $opt['name'] = $account_info['realname'];
                            $opt['permission_begin'] = strtotime($contract_info['start_time']);
                            $opt['permission_end'] = strtotime($contract_info['end_time']);
                            $result = $DDing->add_password($opt);
                            $DDing->logResult($result);
                            break;
                        case 3://日常
                            $account_id     = $charge_info['account_id'];
                            $room_id        = $charge_info['room_id'];
                            $rent_date      = $charge_info['rent_date_to']; //房租到期日
                            if ( !empty($rent_date) ) {
                                $MContract->where(array("room_id"=>$room_id,"account_id"=>$account_id))->save(array("rent_date"=>$rent_date));
                            }
                            break;
                    }

                    
                    
                    $save['pay_status']     = 1;
                    $save['pay_money']      = $total_fee;
                    $save['buyer_email']    = $buyer_email;
                    $save['seller_email']   = $seller_email;
                    $save['pay_type']       = 1;//支付宝在线
                    $save['transaction_id'] = $trade_no;
                    $save['pay_time']       = date("Y-m-d H:i:s",time());
                    //修改pay表的信息
                    $MPay->where(array("pro_id"=>$out_trade_no))->save($save);
                }               
            }

            //调试用，写文本函数记录程序运行情况是否正常
            logResult("时间:".date("Y-m-d H:i:s",time()).",订单号:".$out_trade_no.",支付宝交易号:".$trade_no.",交易状态:".$trade_status.",异步信息:".json_encode($_POST));

            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
                
            echo "success";     //请不要修改或删除
            
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }
        else {
            //验证失败
            echo "fail";

            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        }
    }
}
