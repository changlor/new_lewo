<?php
namespace Admin\Controller;
use Think\Controller;
class SmsController extends Controller {
    public function __construct(){
        parent::__construct();
        if ( empty($_SESSION['username']) && ACTION_NAME != 'register') {
            header("Location:".U("Admin/Index/login"));
            die();
        }
    }

    public function log (){

        $Msms = M("sms_log");
        $MRoom = M('room');
        $MAccount = M('account');
        $where = array();
        $order = "id desc,send_time desc";
        $list = $Msms->where($where)->order($order)->select();
        foreach($list AS $key=>$val){
            $list[$key]['room_code'] = $MRoom->where(array('id'=>$val['room_id']))->getField('room_code');
            if ( empty($val['realname']) ) {
                $list[$key]['realname'] = $MAccount->where(array('id'=>$val['account_id']))->getField('realname');
            }
        }
        $this->assign("list",$list);
        $this->display("Common/header");
        $this->display("Common/nav");
        $this->display("sms_log");
        $this->display("Common/footer");

    }
}