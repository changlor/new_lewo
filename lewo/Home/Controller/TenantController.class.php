<?php
namespace Home\Controller;
use Think\Controller;
class TenantController extends Controller {
	public function __construct(){
		parent::__construct();
		if ( empty($_SESSION['user_id']) && ACTION_NAME != 'register' ) {
			$this->login();
			die();
		}
	}
    public function index(){
        $DAccount = D("account");
        $account_info = $DAccount->getAccountInfoById($_SESSION['user_id']);
        $DRoom = D("room");
        $room_info = $DRoom->getRoomInfoByAcId($_SESSION['user_id']);
        if ( !empty($room_info) ) {
            $DHouses = D("houses");
            $house_info = $DHouses->getHouseAndArea($room_info['house_code']);
        }

        //获取财务退款信息
        $MBackBill = M("back_bill");
        $back_bill = $MBackBill->where(array("account_id"=>$_SESSION['user_id'],"is_affirm"=>2))->select();
        
        //获取确认退房/换房/转房待办账单 status=4为财务发送账单后
        $confirm_back_bill = $MBackBill->where(array("is_finish"=>0,"is_affirm"=>0,"account_id"=>$_SESSION['user_id'],"is_send"=>1))->find();
        //获取schedule_type
        $schedule_type = M("schedule")->where(array("id"=>$confirm_back_bill['schedule_id']))->getField("schedule_type");
        $schedule_type_name = C("schedule_type_arr")[$schedule_type];
        $this->assign("schedule_type_name",$schedule_type_name);

        //获取未支付账单
        /*$DChargeBill = D("charge_bill");
        $notpaylist = $DChargeBill->getBillByAcId($_SESSION['user_id'],0);
        $this->assign("notpaylist",$notpaylist);*/
        //获取未支付合同
        /*$DContract = D("contract");
        $contract_info = $DContract->getNotPayContract($_SESSION['user_id']);*/

        //获取未支付账单,这部分有冗余，主要以$DPay->getPay($param)为主
        $DPay = D('pay');

        $param = array(
                    'account_id'=>$_SESSION['user_id'],
                    'pay_status'=>0,
                    'not_pay_type'=>'9'
                    );
        $not_pay_bill = $DPay->getPay($param);

        $this->assign('not_pay_bill',$not_pay_bill);
        $this->assign("contract_info",$contract_info);
        $this->assign("back_bill",$back_bill);
        $this->assign("confirm_back_bill",$confirm_back_bill);
        $this->assign("room_info",$room_info);
        $this->assign("house_info",$house_info);
        $this->assign('account_info',$account_info);
        $this->assign("name",$_SESSION['user']);
    	$this->display('tenant-index');
    }
    /**
     * [登录]
     **/
    public function login(){
    	if ( !empty($_POST) ) {
    		$where['mobile'] = I("userName");
    		$where['password'] = md5(I("userPass"));
    		$Muser = M("account");
    		$result = $Muser->where($where)->find();

            $login_time['login_time'] = date("Y-m-d H:i:s",time());
            $Muser->where($where)->save($login_time);
    		if ( !is_null($result) ) {
                $_SESSION['user'] = $_POST['userName'];
                $_SESSION['user_id'] = $result['id'];
    			$this->redirect("Home/Tenant/index");
    		} else {
    			$this->error("密码错误,请联系管家");
    		}
    	} else {
    		$this->display('login');
    	}
    }

    /*
     * [退出登录]
     */
    public function login_out(){
        session_destroy();
        $this->success("退出成功",U("Home/Tenant/login"));
    }

    /*
     * [注册]
     */
    /*public function register(){
    	if ( !empty( $_POST ) ) {
    		if ( $_POST['userPass'] !== $_POST['reuserPass'] ){
    			die("两次密码不相同");
    		}
    		$Muser = M('account');
    		$data['mobile'] = $_POST['userName'];
    		$data['password'] = md5($_POST['userPass']);
    		$data['ip'] = get_client_ip();
    		$Muser->add($data);
    		$this->success("注册成功",U("Home/Tenant/login"));
    	} else {
    		$this->display("register");
    	}
    }*/

    /**
     * [我的房间]
     **/
    public function myhouse(){
        $DRoom = D("room");
        $DAccount = D("account");
        $DContract = D("contract");
        $account_info = $DAccount->getAccountInfoById($_SESSION['user_id']);
        $room_info = $DRoom->getRoomInfoByAcId($_SESSION['user_id']);
        if ( !empty($room_info) ) {
            $DHouses = D("houses");
            $contract_info = $DContract->getNewContractById($_SESSION['user_id']); 
            $house_info = $DHouses->getHouseAndArea($room_info['house_code']);
        }

        $account_info = $DAccount->getAccountInfoById($_SESSION['user_id']);

        //获取财务退款信息
        $MBackBill = M("back_bill");
        $back_bill = $MBackBill->where(array("account_id"=>$_SESSION['user_id'],"is_affirm"=>2))->select();

        //获取退房/换房/转房信息
        $DSchedule = D("schedule");
        $TFinfo = $DSchedule->getTFinfo($_SESSION['user_id'],$contract_info['room_id']);
        $HFinfo = $DSchedule->getHFinfo($_SESSION['user_id'],$contract_info['room_id']);
        $ZFinfo = $DSchedule->getZFinfo($_SESSION['user_id'],$contract_info['room_id']);
        $TFstatus = $TFinfo[count($TFinfo)-1]['status'];
        $HFstatus = $HFinfo[count($HFinfo)-1]['status'];
        $ZFstatus = $ZFinfo[count($ZFinfo)-1]['status'];

        $this->assign("back_bill",$back_bill);
        $this->assign("TFstatus",$TFstatus);
        $this->assign("HFstatus",$HFstatus);
        $this->assign("ZFstatus",$ZFstatus);
        $this->assign("TFinfo",$TFinfo);
        $this->assign("HFinfo",$HFinfo);
        $this->assign("ZFinfo",$ZFinfo);
        $this->assign('contract_info',$contract_info);
        $this->assign('account_info',$account_info);
        $this->assign('room_info',$room_info);
        $this->assign("house_info",$house_info);
    	$this->display('my-house');
    }
    /**
     * [费用查询]
     **/
    public function feelist(){
        $account_id = $_SESSION['user_id'];
        $DChargeBill = D("charge_bill");
        $DPay = D('pay');
        $param = array(
                    'account_id' => $account_id,
                    'not_pay_type' => '9'
                );
        $all_bill_list = $DPay->getPay($param);

        /*$bill_list = $DChargeBill->getBillByAcId($account_id);*/
        /*$contract_list = M("contract")->where(array("account_id"=>$account_id))->select();
        $this->assign("contract_list",$contract_list);*/
        $this->assign("bill_list",$all_bill_list);
    	$this->display('fee-list');
    }

    /**
    * [费用详细]
    **/
    public function detail_fee(){
        header("Content-type: text/html; charset=utf-8"); 
        $pro_id = I("pro_id");
        $DArea = D("area");
        $MArea = M("area");
        $DCharge = D("charge_house");
        $DChargeBill = D("charge_bill");
        $DHouses = D("houses");
        $DAmmeterHouse = D("ammeter_house");
        $bill_info = $DChargeBill->getDetailBillById($pro_id);
        $bill_info['total_energy_fee'] = $bill_info['energy_fee'] + $bill_info['room_energy_fee'];
        $bill_info['total_gas_fee'] = $bill_info['gas_fee'] + $bill_info['rubbish_fee'];
        $bill_info['service_fee_des'] = preg_replace('/房租/', '服务费', $bill_info['rent_des']);

        //判断这个账单account_id == $_SESSION['user_id']
        if ( empty($bill_info) ) {
            $this->error('此账单信息保密噢，可以找找管家哥哥了解一下~');
        }
        if ( $bill_info['account_id'] != $_SESSION['user_id']){
            $this->error("不能随便看别人的账单哦~");
        }
        $area_id = $DHouses->getAreaIdByCode($bill_info['house_code']);
        $house_id = $DHouses->getHouseIdByCode($bill_info['house_code']);
        
        $WYfee = $DHouses->getWYfee($bill_info['house_code']);
        $this->assign("WYfee",$WYfee);
        $this->assign("bill_info",$bill_info);
        $this->display("detail-fee");
        
    }
    /**
     * [我的管家]
     **/
    public function mysteward(){
        $DAccount = D("account");
        $DContract = D("contract");
        $DRoom = D("room");
        $account_info = $DAccount->getAccountInfoById($_SESSION['user_id']);
        $contract_info = $DContract->getNewContractById($_SESSION['user_id']); 
        $room_id = $contract_info['room_id'];
        $steward_info = $DRoom->getRoomSteward($room_id);
        $this->assign("account_info",$account_info);
        $this->assign("steward_info",$steward_info);
    	$this->display('my-steward');
    }
    /**
     * [个人资料]
     **/
    public function myinfo(){
        $DAccount = D("account");
        $account_info = $DAccount->getAccountInfoById($_SESSION['user_id']);

        $this->assign("account_info",$account_info);
    	$this->display('my-info');
    }

    /**
    * [修改个人资料]
    **/
    public function edit_myinfo(){
        if ( !empty($_POST) ) {
            switch ( I("type") ) {
                case 'realname':
                    $save['realname'] = I("realname");
                    break;
                case 'sex':
                    $save['sex'] = I("sex");
                    break;
                case 'birthday':
                    $save['birthday'] = I("birthday");
                    break;
                case 'tag':
                    $save['tag'] = I("tag");
                    break;
                case 'password':
                    $password = M("account")->where(array("id"=>$_SESSION['user_id']))->getField("password");

                    if ( $password != md5(I("old")) ) {
                        $this->error("原密码不正确!");
                    } else {
                        if ( I("password") != I("repassword") ) {
                            $this->error("两次密码不相同!");
                        } else {
                            $save['password'] = md5(I("password"));
                        }
                    }
                    break;
            }
            $result = M("account")->where(array("id"=>$_SESSION['user_id']))->save($save);
            if ( $result ) {
                $this->success("修改成功!",U("Home/Tenant/myinfo"));
            } else {
                $this->error("修改失败!",U("Home/Tenant/myinfo"),1);
            }
        } else {
            $type = I("type");
            if ( 'wx' == $type ) {
                $this->error("功能还没开放,请耐心等待~");
            }
            $DAccount = D("account");
            $account_info = $DAccount->getAccountInfoById($_SESSION['user_id']);

            $this->assign("account_info",$account_info);
            $this->assign("type",$type);
            $this->display("edit-myinfo");
        }
    }

    /**
    * [用户申请退房/转房/换房]
    **/ 
    public function checkout(){
        //插入一条待办
        //修改合同状态
        $schedule_type = I("schedule_type");
        $MAccount  = D("account");
        $MSchedule = D("schedule");
        $mobile = $MAccount->getFieldById($_SESSION['user_id'],"mobile");
        $realname = $MAccount->getFieldById($_SESSION['user_id'],"realname");
        if ( !empty($_POST) ) {
            $data['account_id'] = $_SESSION['user_id'];
            $data['create_time'] = date("Y-m-d H:i:s",time());
            $data['mobile'] = $mobile;
            $data['schedule_type'] = $schedule_type;//根据传递过来的值来判断
            $data['status'] = 1;
            $data['room_id'] = I("room_id");
            $data['is_finish'] = 0;
            $data['admin_type'] = C("admin_type_gj");
            $data['msg'] = I("post.msg");
            $data['pay_type'] = I("post.pay_type");
            $data['pay_account'] = I("post.pay_account");
            $data['appoint_time'] = I("post.appoint_time");
            $data['steward_id'] = I("post.steward_id");
            $result = $MSchedule->addOneSchedule($data);
            if ( $result ) {
                $this->success("提交成功，请耐心等待管家",U('Home/Tenant/myhouse'));
            } else {
                $this->error("提交失败");
            } 
        } else {
            //判断是否租屋了
            $room_id = I("room_id");
            if ( empty($room_id) ){
                $this->error("暂未入住");
            }
            $isHas = $MSchedule->where(array("account_id"=>$_SESSION['user_id'],"schedule_type"=>$schedule_type,"is_delete"=>0))->find();
            if ( !empty($isHas) ) {
                $this->success("已经提交了申请，请耐心等待管家",U('Home/Tenant/myhouse'));
            } else {
                
                $DRoom = D("room");
                $room_info = $DRoom->getRoom($room_id);
                $this->assign("room_id",$room_id);
                $this->assign("schedule_type",$schedule_type);
                $this->assign("room_info",$room_info);
                $this->assign("apply_time",date("Y-m-d",time()));
                $this->assign("mobile",$mobile);
                $this->assign("realname",$realname);
                $this->assign("schedule_type_name",C("schedule_type_arr")[$schedule_type]);//换房不需要显示退款方式和退款帐号
                $this->display("check-out");
            }
        }
    }

    /**
    * [显示退房确认未支付账单]
    **/
    public function show_back_bill(){
        //获取未支付账单
        $notpaylist = D("charge_bill")->getNotPay($_SESSION['user_id']);
        $deposit = M("contract")->where(array("account_id"=>$_SESSION['user_id'],"contract_status"=>1))->getField("deposit");

        $sum_total_fee = 0;
        foreach ($notpaylist AS $key=>$val) {
            $sum_total_fee += $val['total_fee'];
        }
        $residue_deposit = $deposit-$sum_total_fee;
        $this->assign("residue_deposit",$residue_deposit);
        $this->assign("deposit",$deposit);
        $this->assign("sum_total_fee",$sum_total_fee);
        $this->assign("notpaylist",$notpaylist);
        $this->display("show-back-bill");
    }

    /**
    * [确认/转房/换房退房账单]
    **/
    public function confirm_bill(){
        $id = I("id");
        $back_bill = M("back_bill")->where(array("id"=>$id))->find();
        $room_id = $back_bill['room_id'];
        $account_id = $back_bill['account_id'];
        
        //插入财务修改支付账单待办
        $DSchedule = D("schedule");
        $schedule_info = $DSchedule->where(array("id"=>$back_bill['schedule_id']))->find();
        $schedule_type = $schedule_info['schedule_type'];
        /*
        if (  C("schedule_type_hf") != $schedule_type ) {
                //如果是换房，无须操作修改打款信息
        }*/
        //租客已确认修改back_bill状态
        M("back_bill")->where(array("id"=>$id))->save(array("is_affirm"=>1));

        //租客确认账单后，插入一条财务查看账单待办
        $result = $DSchedule->addNewSchdule($back_bill['schedule_id'],$schedule_type,5,C("admin_type_cw"));
        if ( $result ) {
            M('schedule')->where(array("id"=>$back_bill['schedule_id']))->save(array("is_finish"=>1));
            $this->success("确认成功，请等待财务审查!",U("Home/Tenant/index"));
        } else {
            $this->error("确认失败，请联系管家",U("Home/Tenant/index"));
        }
    }

    /**
    * [查看合同信息]
    **/
    public function show_contract_bill(){
        $pro_id = I("pro_id");
        $bill_info = M("pay")
                ->alias('p')
                ->field("c.*,p.*,a.realname,r.house_code,area.area_name,h.building,h.floor,h.door_no")
                ->join("lewo_contract c ON p.pro_id=c.pro_id",'left')
                ->join('lewo_room r ON r.id=p.room_id','left')
                ->join('lewo_houses h ON h.house_code=r.house_code','left')
                ->join('lewo_area area ON area.id=h.area_id','left')
                ->join('lewo_account a ON a.id=p.account_id','left')
                ->where(array("p.pro_id"=>$pro_id))
                ->find();
        $rent_type = explode("_",$bill_info['rent_type']);
        $MHouses = M('houses');
        $MArea   = M('area');
        $area_id = $MHouses->where(array("house_code"=>$bill_info['house_code']))->getField("area_id");
        $city_id = $MArea->where(array("id"=>$area_id))->getField("city_id");
        $city_name      = C("city_id")[$city_id];
        $bill_type_arr  = C('bill_type');
        $bill_info['bill_type_name'] = $bill_type_name = $bill_type_arr[$bill_info['bill_type']];

        $realname = preg_replace('/(\/|\.|\%)/','', $bill_info['realname']);

        $bill_info['bill_info'] = $realname.$bill_info['input_year'].'年'.$bill_info['input_month']."月".$bill_type_name.$city_name."账单";
        $bill_info['rent_type'] = $rent_type;

        $this->assign("bill_info",$bill_info);
        $this->display("detail-contract");
    }

    /**
    * [我的合同信息]
    **/
    public function my_contract(){
        $contract_info = M("contract")
        ->alias('c')
        ->join('lewo_pay p ON p.pro_id=c.pro_id')
        ->where(array("c.account_id"=>$_SESSION['user_id'],"c.contract_status"=>1))
        ->find();

        $rent_type = explode("_",$contract_info['rent_type']);
        $contract_info['rent_type'] = $rent_type;
        $this->assign("bill_info",$contract_info);
        $this->display("detail-contract");
    }
}
