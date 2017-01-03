<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function __construct(){
        parent::__construct();
        if ( empty($_SESSION['username']) && ACTION_NAME != 'register') {
            $this->login();
            die();
        }
    }
    /**
     * [后台首页]
     **/
    public function index(){
        $today = date("Y-m-d",time());//今天
        $yesterday = date("Y-m-d",strtotime($today." -1 day"));

        //待办数量
        $DSchedule = D("schedule");
        $schedule_list = $DSchedule->getScheduleList();
        $TFcount = $DSchedule->getScheduleCount(C("schedule_type_tf"),0,C("admin_type_cw"));
        $ZFcount = $DSchedule->getScheduleCount(C("schedule_type_zf"),0,C("admin_type_cw"));
        $HFcount = $DSchedule->getScheduleCount(C("schedule_type_hf"),0,C("admin_type_cw"));
        $LXDKcount = D("back_bill")->where(array("is_finish"=>0,"is_affirm"=>1,"back_type"=>1))->count();
        $LXDKYEcount = D("back_bill")->where(array("is_finish"=>0,"is_affirm"=>1,"back_type"=>2))->count();
        $_SESSION['schedule_count'] = $TFcount+$ZFcount+$HFcount+$LXDKcount+$LXDKYEcount;

        $today_total_money = getTotalMoney($today);//今日总金额：今日新签合同收入的总金额
        $yesterday_total_money = getTotalMoney($yesterday);//昨天总金额：昨天签合同收入的总金额
        $today_sign_count = getSignCount($today);//签约个数：今天签约的个数
        $JD_total_money = getJDMoney($today);//缴定金额：今天缴定金额
        //空房间
        $room_count = getRoomCount();//总房间数量
        $empty_room_count = getEmptyRoomCount();//空房间数量
        $percent_room = $empty_room_count / $room_count;
        $percent_room = $percent_room? ceil($percent_room*100) : 0;
        $this->assign("empty_room_count",$empty_room_count);
        $this->assign("percent_room",$percent_room);
        //空床位
        $bed_count = getBedCount();//总房间数量
        $empty_bed_count = getEmptyBedCount();//空房间数量
        $percent_bed = $empty_bed_count / $bed_count;
        $percent_bed = $percent_bed? ceil($percent_bed*100) : 0;
        $this->assign("empty_bed_count",$empty_bed_count);
        $this->assign("percent_bed",$percent_bed);
        $this->assign("today_total_money",$today_total_money);
        $this->assign("yesterday_total_money",$yesterday_total_money);
        $this->assign("today_sign_count",$today_sign_count);
        $this->assign("JD_total_money",$JD_total_money);
        $this->assign("username",$_SESSION['username']);
    	$this->display("Common/header");
    	$this->display("Common/nav");
    	$this->display("index");
    	$this->display("Common/footer");

    }

    /*
     * [登录]
     */
    public function login(){
    	if ( !empty( $_POST ) ) {
    		$Muser = M('admin_user');
    		$where['username']  = $_POST['username'];
            $where['password']  = md5($_POST['password']);
            $allow_enter_type   = C('allow_enter_type');

    		$result = $Muser->where($where)->find();
            
    		if ( !is_null($result) ) {
                if ( !in_array($result['admin_type'], $allow_enter_type) ) {
                    $this->error('该类型限制登录!');
                }
                $_SESSION['username']       = $result['username'];
                $_SESSION['admin_nickname'] = $result['nickname'];
                $_SESSION['username_id']    = $result['id'];
                $_SESSION['admin_type']     = $result['admin_type'];
                $_SESSION['admin_type_name']= C('admin_type_arr')[$result['admin_type']];
                $this->redirect("Admin/Index/index");
    		} else {
    			$this->error("登录失败");
    		}
    	} else {
    		$this->display("login");
    	}
    }
    /*
     * [注册]
     */
    public function register(){
    	if ( !empty( $_POST ) ) {
    		if ( $_POST['password'] !== $_POST['repassword'] ){
    			die("两次密码不相同");
    		}
    		$Muser = M('admin_user');
    		$data['username'] = $_POST['username'];
    		$data['password'] = md5($_POST['password']);
    		$data['ip'] = get_client_ip();
            $result = $Muser->where(array('username'=>$data['username']))->find();
            if ( empty($result) ) {
                $Muser->add($data);
                $this->success("注册成功",U("Admin/Index/login"));
            } else {
                $this->error("注册失败，帐号已存在");
            }
    	} else {
    		$this->display("register");
    	}
    }
    /*
     * [退出登录]
     */
    public function login_out(){
        session_destroy();
    	$this->success("退出成功",U("Admin/Index/login"));
    }
}