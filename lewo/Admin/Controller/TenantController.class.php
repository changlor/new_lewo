<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * [租客帐号]
 **/
class TenantController extends Controller {
    public function __construct(){
        parent::__construct();
        if ( empty($_SESSION['username']) && ACTION_NAME != 'register') {
            header("Location:".U("Admin/Index/login"));
            die();
        }
    }
    /**
     * [租客列表]
     **/
    public function account(){
        $Mtenant = M('account');
        $account_arr = $Mtenant
                        ->alias('a')
                        ->field("a.id,a.ip,a.mobile,a.login_time,a.register_time,a.realname,r.id AS room_id")
                        ->join('lewo_room r ON r.account_id=a.id','left')
                        ->select();
        $this->assign('account',$account_arr);
    	$this->display("Common/header");
    	$this->display("Common/nav");
    	$this->display("tenant-account");
    	$this->display("Common/footer");
    }
    public function update_account(){
        if ( !empty($_POST) ) {
            $id = I("id");
            $password = I("password");
            $repassword = I("repassword");
            if ( empty($password) ) {
                $this->error("密码不能为空!");
            }
            if ( $password != $repassword ) {
                $this->error("两次密码不相同");
            }
            $result = M("account")->where(array("id"=>$id))->save(array("password"=>md5(I("password"))));
            if ( $result ) {
                $this->success("修改成功",U('Admin/Tenant/account'));
            } else {
                $this->error("修改失败");
            }
        } else {
            $id = I("id");
            $this->assign("id",$id);
            $this->display("Common/header");
            $this->display("Common/nav");
            $this->display('update-account');
            $this->display("Common/footer");
        }
    }
    /**
     * [添加租客帐号]
     **/
    public function add_account(){
        if ( !empty($_POST) ) {
            if ( $_POST['password'] !== $_POST['repassword'] ){
                die("两次密码不相同");
            }
            $Mtenant = M('account');
            $data['password'] = md5(I('post.password'));
            $data['mobile'] = I('post.mobile');
            $data['email'] = I('post.email');
            $data['sex'] = I('post.sex');
            $data['card_no'] = I('post.card_no');
            $data['sex'] = I('post.sex');
            $data['register_time'] = date('Y-m-d H:i:s');
            $data['ip'] = get_client_ip();
            $result = $Mtenant->where(array('mobile'=>$data['mobile']))->find();
            if ( empty($result) ) {
                $Mtenant->add($data);
                $this->success("注册成功",U("Admin/Tenant/account"));
            } else {
                $this->error("注册失败，电话已存在");
            }
        } else {
            $this->display("Common/header");
            $this->display("Common/nav");
            $this->display('add-account');
            $this->display("Common/footer");
        }
    }

    /**
    * [合同]
    **/
    public function contract_info(){
        if ( !empty($_POST) ) {
            $MContract  = M('contract');
            $MPay       = M('pay');
            $DPay       = D('pay');
            $MRoom      = M('room');
            $msg        = '';
            $pro_id     = I("pro_id");
            $room_id    = I("room_id");
            $account_id = I("account_id");
            $is_can_create_bill = I("is_can_create_bill");
            $where['pro_id']        = $pro_id;
            
            $save['start_time']     = I("start_time");
            $save['end_time']       = I("end_time");
            $save['rent_date']      = I("rent_date");
            $save['actual_end_time'] = I("actual_end_time");
            $save['deposit']        = I('deposit');
            $save['rent']           = I("rent");
            $save['fee']            = I("fee");
            $save['person_count']   = I("person_count");
            $save['zS']             = I("zs");
            $save['zD']             = I("zd");
            $save['zQ']             = I("zq");
            $save['roomD']          = I("roomd");
            $save['total_fee']      = I("total_fee");
            $save['contract_status']= I("contract_status");
            $save['contract_number']= I("contract_number");

            M()->startTrans();

            //如果修改了房租到期日，并是往后修改日期的，则生成一个月的房租和服务费
            $contract_info = $MContract
                            ->field('id,account_id,room_id,deposit,period,rent_date,rent,fee,pro_id')
                            ->where($where)
                            ->find();
            if ( $is_can_create_bill == 1 && strtotime(I("rent_date")) > strtotime($contract_info['rent_date']) ){
                //生成一笔账单
                $param = array(
                    'account_id'=>$account_id,
                    'room_id'=>$room_id,
                    'bill_type'=>8,
                    'price'=>$contract_info['rent'] + $contract_info['fee']
                    );
                $DPay->create_bill($param);
            }

            $modify_log = $this->modify_log($pro_id,$save,I('modify_log'));

            $psave['modify_log'] = $modify_log;

            $result     = $MContract->where($where)->save($save); 
            $result2    = $MPay->where($where)->save($psave);

            if ( $result ) {    
                //如果合同修改不是正常，则修改房间状态
                $ht_zc = C('ht_zc');
                if ( I("contract_status") != $ht_zc ) {
                    $update_room_result = $MRoom->where(array('id'=>$room_id))->save(array('account_id'=>0,'status'=>0));
                    if ( $update_room_result === 1 ) {
                        $msg = '修改房间状态为未租成功,';
                    } else {
                        $msg = '修改房间状态失败!';
                    }
                }
            }

            if( $result && $result2 ){
                M()->commit();
                $this->success($msg."修改成功!");
            } else {
                M()->rollback();
                $this->error("修改失败!");
            }
        } else {

            $account_id = I("account_id");
            $room_id = I("room_id");
            $contract_info = M("contract")->where(array("account_id"=>$account_id,'room_id'=>$room_id))->order("id desc")->find();

            $contract_info['account_info'] = M("account")->field('id,realname,mobile,card_no,email')->where(array("id"=>$account_id))->find();
            $contract_info['room_info'] = M("room")->field('room_code,bed_code,house_code,rent,room_fee')->where(array("id"=>$contract_info['room_id']))->find();
            $contract_info['house_info'] = M("houses")->alias('h')->field('area.area_name,h.building,h.floor,h.door_no')->join('lewo_area area ON area.id=h.area_id')->where(array("h.house_code"=>$contract_info['room_info']['house_code']))->find();
            $pay_info = M("pay")->where(array("pro_id"=>$contract_info['pro_id']))->find();

            $rent_type = explode("_",$contract_info['rent_type']);
            $contract_info['rent_type_name'] = "押".$rent_type[0]."付".$rent_type[1];

            $this->assign("contract_info",$contract_info);
            $this->assign("pay_info",$pay_info);
            $this->assign("account_id",$account_id);
            $this->assign("room_id",$room_id);
            $this->assign("pay_type",C("pay_type"));
            $this->assign("contract_status_arr",C("contract_status_arr"));
            $this->display("Common/header");
            $this->display("Common/nav");
            $this->display("contract_info");
            $this->display("Common/footer");
        }
    }

    /**
    * [修改日志]
    * @param $pro_id 账单号
    * @param $save 保存的数据
    * @param $contract_info 原来合同的数据
    * @param $extra_modify_log 额外手动的日志
    * @return 修改文本
    **/
    public function modify_log($pro_id,$save,$extra_modify_log){
        $MContract  = M('contract');
        $MPay       = M('pay');
        $where['pro_id'] = $pro_id;
        $contract_info  = $MContract->where($where)->find();
        $modify_log     = $MPay->where($where)->getField('modify_log');
        $modify_log .= '<br/>修改人:'.$_SESSION['admin_type_name'].$_SESSION['username'].' 时间:'.date('Y-m-d H:i:s').' <br/>';
        //合同状态
        if ( $save['contract_status'] != $contract_info['contract_status'] ) {
            $modify_log .= C('contract_status_arr')[$contract_info['contract_status']].'--> <b style="color:green">'.C('contract_status_arr')[$save['contract_status']].'</b>;<br/>';
        }
        //开始日期
        if ( $save['contract_number'] != $contract_info['contract_number'] ) {
            $modify_log .= '合同编号:'.$contract_info['contract_number'].'--> <b style="color:green">'.$save['contract_number'].'</b>;<br/>';
        }
        //开始日期
        if ( $save['start_time'] != $contract_info['start_time'] ) {
            $modify_log .= '合同开始日期:'.$contract_info['start_time'].'--> <b style="color:green">'.$save['start_time'].'</b>;<br/>';
        }
        //结束日期
        if ( $save['end_time'] != $contract_info['end_time'] ) {
            $modify_log .= '合同结束日期:'.$contract_info['end_time'].'--> <b style="color:green">'.$save['end_time'].'</b>;<br/>';
        }
        if ( $save['rent_date'] != $contract_info['rent_date'] ) {
            $modify_log .= '房租到期日期:'.$contract_info['rent_date'].'--> <b style="color:green">'.$save['rent_date'].'</b>;<br/>';
        }
        //实际退房日
        if ( $save['actual_end_time'] != $contract_info['actual_end_time'] ) {
            $modify_log .= '实际退房日期:'.$contract_info['actual_end_time'].'--> <b style="color:green">'.$save['actual_end_time'].'</b>;<br/>';
        }
        //房租
        if ( $save['rent'] != $contract_info['rent'] ) {
            $modify_log .= '房租金额:'.$contract_info['rent'].'--> <b style="color:green">'.$save['rent'].'</b>;<br/>';
        }
        //服务
        if ( $save['fee'] != $contract_info['fee'] ) {
            $modify_log .= '服务费:'.$contract_info['fee'].'--> <b style="color:green">'.$save['fee'].'</b>;<br/>';
        }
        if ( $save['deposit'] != $contract_info['deposit'] ) {
            $modify_log .= '押金:'.$contract_info['deposit'].'--> <b style="color:green">'.$save['deposit'].'</b>;<br/>';
        }
        if ( $save['person_count'] != $contract_info['person_count'] ) {
            $modify_log .= '人数:'.$contract_info['person_count'].'--> <b style="color:green">'.$save['person_count'].'</b>;<br/>';
        }

        if ( !empty($extra_modify_log) ) {
            $modify_log .= '备注:'.$extra_modify_log.'</b>;<br/>';
        }

        return $modify_log;
    }

    /**
    * [租客信息]
    **/
    public function tenant_info(){
        $MAccount = M('account');
        if ( !empty($_POST) ) {
            $account_id = I('id');
            $save['realname'] = I('realname');
            $save['nickname'] = I('nickname');
            $save['mobile'] = I('mobile'); 
            $save['email']  = I('email');
            $save['sex' ] = I('sex');
            $save['card_no'] = I('card_no');
            $save['birthday'] = I('birthday');

            $result = $MAccount->where(array('id'=>$account_id))->save($save);
            if ( $result ) {
                $this->success('修改成功',U('Admin/Tenant/account'));
            } else {
                $this->error('修改失败');
            }
        } else {
            $account_id = I('account_id');
            if ( is_null($account_id) )  $this->error('id丢失');

            $account_info = $MAccount->where(array('id'=>$account_id))->find();

            $this->assign('account_info',$account_info);
            $this->display("Common/header");
            $this->display("Common/nav");
            $this->display('tenant_info');
            $this->display("Common/footer");
        }
    }
}