<?php
namespace Home\Controller;
use Think\Controller;
class StewardController extends BaseController {
	public function __construct(){
		parent::__construct();
		if ( empty($_SESSION['steward_id'])) {
			$this->login();
			die();
		}
	}
    public function index(){
        //待办数量
        $steward_id = $_SESSION['steward_id'];
        $DSchedule = D("schedule");
        $TFcount = $DSchedule->getScheduleCount($steward_id,C("schedule_type_tf"),0,C("admin_type_gj"));
        $ZFcount = $DSchedule->getScheduleCount($steward_id,C("schedule_type_zf"),0,C("admin_type_gj"));
        $HFcount = $DSchedule->getScheduleCount($steward_id,C("schedule_type_hf"),0,C("admin_type_gj"));
        $JDcount = $DSchedule->getScheduleCount($steward_id,C("schedule_type_jd"),0,C("admin_type_gj"));
        $_SESSION['steward_schedule_count'] = $TFcount+$ZFcount+$HFcount+$JDcount;

        $today = date("Y-m-d",time());//今天2016-08-19
        $yesterday = date("Y-m-d",strtotime($today." -1 day")); 

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

        $steward_info = M("admin_user")->where(array("id"=>$_SESSION['steward_id']))->find();
        
        $this->assign("steward_info",$steward_info);
        $this->assign("empty_bed_count",$empty_bed_count);
        $this->assign("percent_bed",$percent_bed);
        $this->assign("today_total_money",$today_total_money);
        $this->assign("yesterday_total_money",$yesterday_total_money);
        $this->assign("today_sign_count",$today_sign_count);
        $this->assign("JD_total_money",$JD_total_money);
        $this->display("steward-index");
    }
    /**
    * [重新计算待办未完成数目]
    **/
    public function refesh_schedule_count(){
        //待办数量
        $steward_id = I("steward_id");
        $DSchedule = D("schedule");
        $TFcount = $DSchedule->getScheduleCount($steward_id,C("schedule_type_tf"),0,C("admin_type_gj"));
        $ZFcount = $DSchedule->getScheduleCount($steward_id,C("schedule_type_zf"),0,C("admin_type_gj"));
        $HFcount = $DSchedule->getScheduleCount($steward_id,C("schedule_type_hf"),0,C("admin_type_gj"));
        $JDcount = $DSchedule->getScheduleCount($steward_id,C("schedule_type_jd"),0,C("admin_type_gj"));
        $_SESSION['schedule_count'] = $TFcount+$ZFcount+$HFcount+$JDcount;
        die(json_encode(array("stewrad_schedule_count"=>$_SESSION['stewrad_schedule_count'])));
    }
    /**
     * [登录]
     **/
    public function login(){
    	if ( !empty($_POST) ) {
    		$where['username'] = I("post.userName");
            $where['password'] = md5(I("post.userPass"));
    		$Muser = M("admin_user");
    		$result = $Muser->where($where)->find();
    		if ( !is_null($result) ) {
                $_SESSION['steward_user'] = I("post.userName");
                $_SESSION['steward_id'] = $result['id'];
                $_SESSION['steward_nickname'] = $result['nickname'];
                $this->redirect('Home/Steward/index');
    		} else {
    			$this->error("登录失败");
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
        $this->success("退出成功",U("Home/Steward/login"));
    }

    /**
     * [集中查表]
     **/
    public function checkbill(){
        $where = array();
        $where['steward_id'] = $_SESSION['steward_id'];
        $search = I("search");
        $is_has_flag = strpos($search, '-');
        if ( $is_has_flag && !empty($search)) {
            $search_arr = explode('-',$search);
            if ( !is_null($search_arr['0']) ) {
                $where['building'] = $search_arr['0'];
            }
            if ( !is_null($search_arr['1']) ) {
                $where['floor'] = $search_arr['1'];
            }
            if ( !is_null($search_arr['2']) ) {
                $where['door_no'] = $search_arr['2'];
            }
        } else {
            $where['_string'] = "house_code LIKE '%".$search."%' OR area_name LIKE '%".$search."%'";
        }
        $this->assign("search",$search);
        $DHouses = D("houses");
        $houses = $DHouses->getHouseAndRoom($where);

        $MAmmeter = M("ammeter_house");
        foreach($houses AS $key=>$val){
            $where = array();
            $where['house_id'] = $val['id'];
            $where['input_year'] = date("Y");
            $where['input_month'] = date("m");
            $where['status']  = 1;
            $result = $MAmmeter->where($where)->find();
            if ( !is_null($result) ) {
                $houses[$key]['is_typein'] = true;
            } else {
                $houses[$key]['is_typein'] = false;
            }
        }
        $this->assign("houses", $houses);
        $this->assign("month",date("m"));
        $this->display("check-bill");
        $this->display("Steward/common/footer");
    }

    /**
     * [显示所有集中查表]
     **/
    public function allcheckbill(){
        $where = array();
        $search = I("search");
        $is_has_flag = strpos($search, '-');
        if ( $is_has_flag && !empty($search)) {
            $search_arr = explode('-',$search);
            if ( !is_null($search_arr['0']) ) {
                $where['building'] = $search_arr['0'];
            }
            if ( !is_null($search_arr['1']) ) {
                $where['floor'] = $search_arr['1'];
            }
            if ( !is_null($search_arr['2']) ) {
                $where['door_no'] = $search_arr['2'];
            }
        } else {
            $where['_string'] = "house_code LIKE '%".$search."%' OR area_name LIKE '%".$search."%'";
        }
        $this->assign("search",$search);

        $DHouses = D("houses");
        $houses = $DHouses->getHouseAndRoom($where);
        $MAmmeter = M("ammeter_house");
        foreach($houses AS $key=>$val){
            $where = array();
            $where['house_id'] = $val['id'];
            $where['input_year'] = date("Y");
            $where['input_month'] = date("m");
            $where['status']  = 1;
            $result = $MAmmeter->where($where)->find();
            if ( !empty($result) ) {
                $houses[$key]['is_typein'] = true;
            } else {
                $houses[$key]['is_typein'] = false;
            }
        }
        $this->assign("houses", $houses);
        $this->assign("month",date("m"));
        $this->display("check-bill");
        $this->display("Steward/common/footer");
    }
    /**
     * [显示该房屋的水电气列表]
     **/
    public function show_ammeter(){
        $house_id = I("house_id");
        $DHouses = D("houses");
        $MArea = M("area");
        $houses_info = $DHouses->where(array("id"=>$house_id))->find();
        $houses_info['area_name'] = $MArea->where(array("id"=>$houses_info['area_id']))->getField("area_name");
        $year = date("Y");
        $month = date("m");
        $lastDate = date("Y-m",strtotime($year."-".$month) - 1);
        $lastYear = date("Y",strtotime($lastDate));
        $lastMonth = date("m",strtotime($lastDate));
        $DAmmeter = D("ammeter_house");
        $DAmmeter->checkHouseAmmeterByDate($house_id,$year,$month);//检查当月是否生成水电气数据
        $DAmmeter->checkHouseAmmeterByDate($house_id,$lastYear,$lastMonth);//检查上个月是否生成水电气数据

        //检测房屋是否按间或者按床位，按床位不用电表

        

        $ammter_list = $DAmmeter->getHouseAmmeterById($house_id);
        $this->assign('house_id',$house_id);
        $this->assign('houses_info',$houses_info);
        $this->assign('ammter_list',$ammter_list);
        $this->display("show-ammeter");
        $this->display("Steward/common/footer");    
    }
    /**
    * [写入水电气]
    **/
    public function type_in(){
        if ( !empty($_POST) ) {
            $is_clear = I("is_clear");
            $is_clear = !empty($is_clear)?I("is_clear"):0;
            $amme_id = I("amme_id");
            $house_id = I("house_id");
            $year = I("input_year");
            $month = I("input_month"); 
            $lastDate = date("Y-m",strtotime($year."-".$month) - 1);
            $lastYear = date("Y",strtotime($lastDate));
            $lastMonth = date("m",strtotime($lastDate));

            $DAmmeter = D("ammeter_house");
            $DAmmeterROOM = D("ammeter_room");
            //获取上个月的房屋度数
            $last_ammeter_house_info = $DAmmeter->where(array("house_id"=>$house_id,"input_year"=>$lastYear,"input_month"=>$lastMonth))->find();
            if ( !is_null($last_ammeter_house_info) && $is_clear != 1) {
                //判断录入该月是否小于上个月的度数
                if ( I("zD") < $last_ammeter_house_info['total_energy'] ) {
                    $this->error("录入的电度数:".I("post.zD")."少于最新电度数:".$last_ammeter_house_info['total_energy']."，请检查!","",10);
                }
                if ( I("zS") < $last_ammeter_house_info['total_water'] ) {
                     $this->error("录入的水度数:".I("post.zS")."少于最新水度数:".$last_ammeter_house_info['total_water']."，请检查!","",10);
                }
                if ( I("zQ") < $last_ammeter_house_info['total_gas'] ) {
                    $this->error("录入的气度:".I("post.zQ")."少于最新气度数:".$last_ammeter_house_info['total_gas']."，请检查!","",10);
                }
            }
            
            
            foreach ( I('roomD') AS $key=>$val ) {
                //获取上个月 房间的电表
                $last_ammeter_room_info = $DAmmeterROOM->where(array("room_id"=>$val['room_id'],"house_id"=>$house_id,"input_year"=>$lastYear,"input_month"=>$lastMonth))->find();
                if ( !is_null($last_ammeter_room_info) && $is_clear != 1) {
                    if ( $val['room_energy'] < $last_ammeter_room_info['room_energy'] ) {
                        $this->error($val['room_code']."该房间电度数低于上个月!请重新录入");
                    }
                }
                $DAmmeterROOM->updateAmmeterRoomById($key,$val['room_energy']);
            }

            $return = $DAmmeter->updateAmmeterById($amme_id,$_POST);
            $this->success("保存成功",U('Home/Steward/checkbill'));

        } else {
            $amme_id = I("amme_id");
            $house_id = I("house_id");
            $input_year = I("input_year");
            $input_month = I("input_month");
            $this->assign("house_id",$house_id);
            $this->assign("input_year",$input_year);
            $this->assign("input_month",$input_month);

            $DHouse = D("houses");
            $house_info = $DHouse->getHouseById($house_id);
            $this->assign("house_info",$house_info);

            $DAmmeter = D("ammeter_house");
            $house_ammeter = $DAmmeter->getAmmeterById($amme_id);
            $this->assign("house_ammeter",$house_ammeter);

            $DAmmeterRoom = D("ammeter_room");
            
            $aRoom_list = $DAmmeterRoom->getAmmeterRoom($house_id,$input_year,$input_month);
            $this->assign("aRoom_list",$aRoom_list);
            $this->assign('amme_id',$amme_id);
            $this->display("type-in");
        }
    }

    /**
     * [入住--房屋列表]
     **/
    public function houses(){
        $where = array();
        $_SESSION['stewrad_houses_back_url'] = U('Home/Steward/houses'); 
        $where['steward_id'] = $_SESSION['steward_id'];
        $search = I("search");
        $is_has_flag = strpos($search, '-');
        if ( $is_has_flag && !empty($search)) {
            $search_arr = explode('-',$search);
            if ( !is_null($search_arr['0']) ) {
                $where['building'] = $search_arr['0'];
            }
            if ( !is_null($search_arr['1']) ) {
                $where['floor'] = $search_arr['1'];
            }
            if ( !is_null($search_arr['2']) ) {
                $where['door_no'] = $search_arr['2'];
            }
        } else {
            $where['_string'] = "house_code LIKE '%".$search."%' OR area_name LIKE '%".$search."%'";
        }
        $this->assign("search",$search);
        
        $DHouses = D("houses");
        $this->assign("houses",$DHouses->getHouseAndRoom($where));
        $this->display('houses');
        $this->display("Steward/common/footer");
    }

    public function tenant_info(){
        $account_id = I("account_id");
        $room_id = I("room_id");
        $MAccount = M("account");
        $MRoom = M("room");
        $MHouses = M("houses");
        $MArea = M("area");
        $MAccount->startTrans();
        if ( !empty($_POST) ) {
            //修改租客信息
            $save['realname'] = I("realname"); 
            $save['mobile'] = I("mobile");
            $save['email'] = I("email");
            $save['sex'] = I("sex");
            $save['card_no'] = I("card_no");
            $save['birthday'] = I("birthday");
            $save['sleep_habit'] = I("sleep_habit");
            $save['tag'] = I("tag");
            $save['job'] = I("job");
            $save['hobby'] = I("hobby");
            $save['specialty'] = I("specialty");
            $save['edu_history'] = I("edu_history");
            $result = $MAccount->where(array('id'=>$account_id))->save($save);

            if ($result == 1) {
                $MAccount->commit();
                $this->success("修改成功!");
            } else {
                $MAccount->rollback();
                $this->error("修改失败!");
            }
        } else {
            $account_info = $MAccount->where(array("id"=>$account_id))->find();
            $room_info = $MRoom
                        ->alias('r')
                        ->field('r.id,r.room_code,r.bed_code,h.building,h.floor,h.door_no,area.area_name')
                        ->join('lewo_houses h ON r.house_code=h.house_code','left')
                        ->join('lewo_area area ON area.id=h.area_id','left')
                        ->where(array('r.id'=>$room_id))
                        ->find();

            $this->assign('check_out_type',C('check_out_type'));
            $this->assign("area_name",$area_name);
            $this->assign("account_info",$account_info);
            $this->assign("room_info",$room_info);
            $this->display("tenant-info");
        }
    }

    public function total_daily_room_fee(){
        header("Content-type: text/html; charset=utf-8"); 
        $account_id = I("account_id");
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

        if ( $bill_info['account_id'] != $account_id){
            $this->error("非法查看~");
        }
        $area_id = $DHouses->getAreaIdByCode($bill_info['house_code']);
        $house_id = $DHouses->getHouseIdByCode($bill_info['house_code']);
        
        $WYfee = $DHouses->getWYfee($bill_info['house_code']);
        $this->assign("WYfee",$WYfee);
        $this->assign("bill_info",$bill_info);
        $this->display("detail-fee");
        
    }

    /*
     * author: changle
     */
    public function allbills(){
        //获取账单类别
        //$type = I("select");
        //$_SESSION['stewrad_houses_back_url'] = U('Home/Steward/allhouses'); 
        $where = [];
        $search = trim(I("search"));
        $chips = empty($search) ? '' : explode('-', $search);
        if (isset($chips[0])) {
            $where['building'] = $chips[0];
        }
        if (isset($chips[1])) {
            $where['floor'] = $chips[1];
        }
        if (isset($chips[2])) {
            $where['door_no'] = $chips[2];
        }
        /*
        //$is_has_flag = strpos($search, '-');
        if ( $is_has_flag && !empty($search)) {
            $search_arr = explode('-',$search);
            if ( !is_null($search_arr['0']) ) {
                $where['building'] = $search_arr['0'];
            }
            if ( !is_null($search_arr['1']) ) {
                $where['floor'] = $search_arr['1'];
            }
            if ( !is_null($search_arr['2']) ) {
                $where['door_no'] = $search_arr['2'];
            }
        } else {
            $where['_string'] = "house_code LIKE '%".$search."%' OR area_name LIKE '%".$search."%'";
        }
        */
        //$this->assign("search",$search);
        //输出房屋类别
        //$this->assign("type", $type);
        $type = '';
        $DPay = D('pay');
        $bills = $DPay->getBills($where, $type);
        //echo'<pre>';print_r($bills);
        if (!empty($search)) {
            $this->assign('search_history', $search);
        }
        $this->assign('houses', $bills);
        $this->display('bills');
        $this->display("Steward/common/footer");
    }


    /**
     * [入住--房屋列表]
     **/
    public function allhouses(){
        //获取房屋类别
        $type = I("select");
        $_SESSION['stewrad_houses_back_url'] = U('Home/Steward/allhouses'); 
        $where = array();
        $search = I("search");
        $is_has_flag = strpos($search, '-');
        if ( $is_has_flag && !empty($search)) {
            $search_arr = explode('-',$search);
            if ( !is_null($search_arr['0']) ) {
                $where['building'] = $search_arr['0'];
            }
            if ( !is_null($search_arr['1']) ) {
                $where['floor'] = $search_arr['1'];
            }
            if ( !is_null($search_arr['2']) ) {
                $where['door_no'] = $search_arr['2'];
            }
        } else {
            $where['_string'] = "house_code LIKE '%".$search."%' OR area_name LIKE '%".$search."%'";
        }
        $this->assign("search",$search);
        //输出房屋类别
        $this->assign("type", $type);
        $DHouses = D("houses");
        $this->assign("houses",$DHouses->getHouseAndRoom($where, $type));
        $this->display('houses');
        $this->display("Steward/common/footer");
    }
    /**
     * [入住-房间信息]
     **/
    public function roominfo(){
        $id = I("id");
        $DHouses = D('Houses');
        $info = $DHouses->getRoom($id);
        $otherRoom = $DHouses->getOtherRoom($info['house_code'],$id);
        $this->assign('otherRoom',$otherRoom);
        $this->assign('info',$info);
        $this->display('room-info');
    }
    /**
     * [入住-缴定]
     **/
    public function order(){
        if ( !empty($_POST) ) {
            $appoint_time = I("post.appoint_time");
            $today = date('Y-m-d',time());
            if ( strtotime($appoint_time) < strtotime($today) ) {
                $this->error('约定时间不能少于现在的时间');
            }
            $data['realname'] = I('post.realname');
            $data['password'] = md5("123456");
            $data['mobile'] = I('post.mobile');
            $data['register_time'] = date("Y-m-d H:i:s",time());

            $Maccount = M("account");
            $DPay = D('pay');
            $result = $Maccount->where(array('mobile'=>$data['mobile']))->find();
            if ( $result == null ) {               
                $id = $Maccount->add($data); // 生成帐号后返回ID
            } else {
                $id = $result['id'];
            }
            if ( $id ) {
                //缴定后 插入到待办中 并生成账单
                $sch['room_id'] = I('post.id');
                $sch['money'] = I('post.money');
                $sch['account_id'] = $id;
                $sch['mobile'] = $data['mobile'];
                $sch['schedule_type'] = 4; //缴定
                $sch['status'] = 1;
                $sch['appoint_time'] = $appoint_time;
                $sch['msg'] = I("post.msg");
                $sch['steward_id'] = $_SESSION['steward_id'];
                $sch['admin_type'] = C("admin_type_gj");
                $DSchedule = D("schedule");
                $result2 = $DSchedule->addOneSchedule($sch);

                $param = array(
                            'account_id'=>$id,
                            'room_id'=>$sch['room_id'],
                            'bill_type'=>1,
                            'price'=>$sch['money'],
                            );
                $DPay->create_bill($param);
                //插入lewo_pay bill_type为1 的缴定账单 未做
                if ( $result2 ) {
                    M("room")->where(array("id"=>$sch['room_id']))->save(array("account_id"=>$id,"status"=>1));//将房间修改成缴定状态
                    $this->success("缴定成功！",U("Home/Steward/houses"));
                } else {
                    //缴定失败也把帐号摧毁 待定
                     $this->error("缴定失败！");
                }
            }
        } else {
            $id = I('id');
            $DRomm = D("houses");
            $this->assign('room_info',$DRomm->getRoom($id));
            $this->assign('pay_type_list',C('pay_type'));
            $this->display("order");
        }
    }
    /**
     * [入住-缴定后-待办-签约]
     **/
    public function order_checkin(){
        if ( !empty($_POST) ) {
            $DContract = D("contract");
            $post = $_POST;
            $schedule_id = I("schedule_id");//缴定待办id
            $DContract = D("contract");
            $room_id = I("room_id");
            $house_code = M("room")->where(array("id"=>$room_id))->getField("house_code");
            $end_date = M("houses")->where(array("house_code"=>$house_code))->getField("end_date");//托管结束日
            if ( strtotime($_POST['startDate']) > strtotime($_POST['endDate']) ) {
                //判断租期结束日是否大于房间托管结束日
                $this->error("签约失败,租期开始日:".$_POST['startDate']." 大于 租期结束日:".$_POST['endDate'],'',10);
            }
            if ( strtotime($_POST['endDate']) > strtotime($end_date) ) {
                //判断租期结束日是否大于房间托管结束日
                $this->error("签约失败,租期结束日:".$_POST['endDate']." 大于 房间托管结束日:".$end_date);
            }
            
            if ( !empty($_FILES['photo']['name']['0']) ) {
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize   =     3145728 ;// 设置附件上传大小
                $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
                $upload->savePath  =     ''; // 设置附件上传（子）目录
                
                $info   =   $upload->upload();

                if(count($info) != 0) { 
                    foreach( $info AS $key=>$val ){
                        if ( $val['key'] == 'photo' ) {
                            $post['photo'] = $val['savepath'].$val['savename'];
                        }
                    }
                }
            }

            $result = $DContract->add($post);

            if ( $result['result'] ) {
                M("schedule")->where(array("id"=>$schedule_id))->save(array("is_finish"=>1));
                $this->success("合同生成成功!",U("Home/Steward/check_contract",array("id"=>$result['id'])));
            } else {
                $this->error($result['error_msg'].",签约失败0。0",'',10);
            }
        } else {
            $id = I('schedule_id');//schedule_id待办id
            $DRoom = D("houses");
            $DSchedule = D("schedule");
            $schedule_info = $DSchedule->getScheduleByID($id);
            $this->assign("schedule_id",$id);
            $this->assign("account_id",$schedule_info['account_id']);
            $this->assign('schedule_info',$schedule_info);
            $this->assign('today',date("Y-m-d",time()));
            $this->assign('room_info',$DRoom->getRoom($schedule_info['room_id']));
            $this->display("check-in");
        }
    }

    //催款
    public function press_money($pro_id) {
        $MPay = M('pay');
        $field = [
            // account
            'lewo_account.realname',
            //pay
            'lewo_pay.price', 'lewo_pay.bill_type',
        ];
        $field = implode(',', $field);
        $pay_info = $MPay
        ->field($field)
        ->where(['pro_id' => $pro_id])
        ->join('lewo_account ON lewo_account.id = lewo_pay.account_id', 'left')
        ->find();
        $content = '乐窝小主' . $pay_info['realname'] . '，你有' . $pay_info['price'] . '元的' . C('bill_type')[$pay_info['bill_type']] . '账单' . '还没支付，请到个人页面进行支付 http://' . $_SERVER['HTTP_HOST'];
        parent::sms($pro_id, $content);
        $this->success('成功', U('Steward/allbills'));
    }

    // 管家代收
    public function steward_collection() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 获取订单id
            $pro_id = I('pro_id');
            // 获取模型实例
            $MContract  = M('contract'); $MPay = M('pay'); $DPay = D('pay');
            // 获取支付信息
            $pay_info = $MPay->where(['pro_id' => $pro_id])->find();
            // 所有类型账单通用数据
            $pay_type = I('pay_type');
            $pay_money = I('actual_price');
            $pay_time = date('Y-m-d H:i:s');
            // 合同账单
            $contract_bill = [
                'actual_deposit' => I('actual_deposit'),
                'actual_rent' => I('actual_rent'),
            ];
            // 账单类型
            $bill_type = $pay_info['bill_type'];
            // 租客id
            $account_id = $pay_info["account_id"];
            // 房间id
            $room_id = $pay_info["room_id"];
            // 修改日志
            $modify_log  = $pay_info['modify_log'];
            $modify['修改人'] = '管家(' . $_SESSION['steward_nickname'] . '|' . $_SESSION['steward_user'] . ')时间:' . date('Y-m-d H:i:s') . '代收';
            $modify['支付状态'] = '未支付-><b style="color: green;">已支付</b>';
            $modify['支付方式'] = C('pay_type')[$pay_type];
            $modify['支付金额'] = $pay_info['pay_money'] . '-><b style="color: green;">' . $pay_money . '</b>';
            $modify['支付时间'] = '<b style="color: green;">' . $pay_time . '</b>';
            $modify['备注'] = '管家代收';
            $modify_log = '';
            foreach ($modify as $key => $value) {
                $modify_log .= '<br>' . $key . ': ' . $value;
            }
            // 如果实际收取金额大于支付金额，则返回数据错误
            if ($pay_money > $pay_info['price']) {
                $this->error('输入的金额大于应付金额');
            }
            // 未支付和未付完 则生成欠款
            if ($pay_money < $pay_info['price'] && $pay_status == 1) {           
                switch ($bill_type) {
                    case 2:
                    case 7:
                        $due_bill_type = 7;
                        break;
                    case 3:
                    case 8:
                        $due_bill_type = 8;
                        break;
                    default:
                        $due_bill_type = 9;
                        break;
                }

                $due_price = $pay_info['price'] - $pay_money;
                $lewo_pay_due = [
                    'account_id' => $account_id,
                    'room_id' => $room_id,
                    'bill_type' => $due_bill_type,
                    'input_year' => $pay_info['input_year'],
                    'input_month' => $pay_info['input_month'],
                    'should_date' => $pay_info['should_date'],
                    'last_date' => $pay_info['last_date'],
                    'price' => $due_price,
                    'is_send' => 1,
                ];
                $res = $DPay->create_bill($lewo_pay_due);
                if (!$res) {
                    $this->error('欠款账单生成失败!');
                }
            }
            // 修改房屋合同信息
            switch ($bill_type) {
                case 2:
                    // 合同
                    $DRoom = D('room');
                    // roomstatus
                    // 0 未租, 1 缴纳定金, 2 已住
                    $DRoom->setRoomStatus($room_id, 2);
                    $DRoom->setRoomPerson($room_id, $account_id);
                    //修改合同正常
                    $lewo_contract = [
                        'actual_rent' => $contract_bill['actual_rent'],
                        'actual_deposit' => $contract_bill['actual_deposit'],
                        'contract_status' => 1,
                    ];
                    M('contract')->where(['pro_id' => $pro_id])->save($lewo_contract);
                    break;
                case 3:
                    // 日常
                    // 修改合同信息
                    $charge_info = M('charge_bill')->where(['pro_id' => $pro_id])->find();
                    $rent_date = $charge_info['rent_date_to']; //房租到期日
                    $MContract->where([
                        'account_id' => $account_id, 
                        'room_id' => $room_id, 
                        'contract_status'=>1
                    ])->save(['rent_date' => $rent_date]);
                    break;
            }
            // lewo_pay表修改内容
            $lewo_pay = [
                'pay_type' => $pay_type,
                'pay_status' => 1,
                'pay_time' => $pay_time,
                'pay_money' => $pay_money,
                'modify_log' => $modify_log,
            ];
            
            $res = M('pay')->where(['pro_id' => $pro_id])->save($lewo_pay);
            $res == false
            ? $this->error('修改账单失败!', U('Steward/steward_collection', ['pro_id' => $pro_id]))
            : $this->success('成功', U('Steward/allbills'));
        } else {
            $pro_id = I('pro_id');
            $field = [
                // account
                'lewo_account.realname',
                // area
                'lewo_area.area_name', 'lewo_area.id',
                // contract
                'lewo_contract.pro_id',
                'lewo_contract.deposit', 'lewo_contract.rent',
                'lewo_contract.fee', 'lewo_contract.wg_fee',
                //
                'lewo_charge_bill.pro_id',
                'lewo_charge_bill.water_fee',
                'lewo_charge_bill.room_energy_fee',
                'lewo_charge_bill.wx_fee',
                'lewo_charge_bill.rubbish_fee',
                'lewo_charge_bill.energy_fee',
                'lewo_charge_bill.gas_fee',
                'lewo_charge_bill.rent_fee',
                'lewo_charge_bill.wgfee_unit',
                'lewo_charge_bill.service_fee',
                // room
                'lewo_room.room_code', 'lewo_room.house_code',
                //houses
                'lewo_houses.area_id',
                //pay
                'lewo_pay.price', 'lewo_pay.bill_type',
            ];
            $field = implode(',', $field);
            $filters = ['lewo_pay.pro_id' => $pro_id];
            $MPay = M('pay');
            $pay_list = $MPay
            ->field($field)
            ->join('lewo_contract ON lewo_pay.pro_id = lewo_contract.pro_id', 'left')
            ->join('lewo_account ON lewo_account.id = lewo_pay.account_id', 'left')
            ->join('lewo_room ON lewo_room.id = lewo_pay.room_id', 'left')
            ->join('lewo_charge_bill ON lewo_charge_bill.pro_id = lewo_pay.pro_id', 'left')
            ->join('lewo_houses ON lewo_houses.house_code = lewo_room.house_code', 'left')
            ->join('lewo_area ON lewo_houses.area_id = lewo_area.id', 'left')
            ->where($filters)
            ->find();

            $pay_classify['合同'] = [
                'actual_deposit' => ['押金', $pay_list['deposit'], 'need_modify'],
                'actual_rent' => ['房租', $pay_list['rent'], 'need_modify'],
                'fee' => ['服务费', $pay_list['fee']],
                'wg_fee' => ['物业费', $pay_list['wg_fee']],
                'price' => ['总金额', $pay_list['price']],
            ];
            $pay_classify['日常'] = [
                'rent_fee' => ['房租', $pay_list['rent_fee']],
                'total_daily_room_fee' => [
                    '水电气',
                    $pay_list['room_energy_fee'] +
                    $pay_list['water_fee'] +
                    $pay_list['energy_fee'] +
                    $pay_list['gas_fee'] +
                    $pay_list['rubbish_fee'],
                ],
                'wx_fee' => ['维修费', $pay_list['wx_fee']],
                'wgfee_unit' => ['物管费', $pay_list['wgfee_unit']],
                'service_fee' => ['服务费', $pay_list['service_fee']],
                'should_price' => ['总金额', $pay_list['price']],
                'actual_price' => ['实收金额', $pay_list['price'], 'need_modify'],
            ];
            $pay_classify['others'] = [
                'should_price' => ['应收金额', $pay_list['price']],
                'actual_price' => ['实收金额', $pay_list['price'], 'need_modify'],
            ];

            $pay_type = [
                '支付宝(转账)' => 2,
                '微信(转账)' => 4,
                '现金' => 6,
                '银行卡' => 7,
            ];

            isset($pay_classify[C('bill_type')[$pay_list['bill_type']]])
            ? $pay_list['pay_classify'] = $pay_classify[C('bill_type')[$pay_list['bill_type']]]
            : $pay_list['pay_classify'] = $pay_classify['others'];
            
            $this->assign('pay_list',$pay_list);
            $this->assign('pay_type', $pay_type);
            $this->assign('pro_id', $pro_id);
            $this->display('steward-collection');
        }
    }

    /**
     * [入住-签约]
     **/
    public function checkin(){
        if ( !empty($_POST) ) {   
            $DContract = D("contract");
            $post = $_POST;         
            $room_id = I("room_id");
            $house_code = M("room")->where(array("id"=>$room_id))->getField("house_code");
            $end_date = M("houses")->where(array("house_code"=>$house_code))->getField("end_date");//托管结束日
            if ( strtotime($_POST['startDate']) > strtotime($_POST['endDate']) ) {
                //判断租期结束日是否大于房间托管结束日
                $this->error("签约失败,租期开始日:".$_POST['startDate']." 大于 租期结束日:".$_POST['endDate'],'',10);
            }
            if ( strtotime($_POST['endDate']) > strtotime($end_date) ) {
                //判断租期结束日是否大于房间托管结束日
                $this->error("签约失败,租期结束日:".$_POST['endDate']." 大于 房间托管结束日:".$end_date);
            }

            if ( !empty($_FILES['photo']['name']['0']) ) {
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize   =     3145728 ;// 设置附件上传大小
                $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
                $upload->savePath  =     ''; // 设置附件上传（子）目录
                
                $info   =   $upload->upload();

                if(count($info) != 0) { 
                    foreach( $info AS $key=>$val ){
                        if ( $val['key'] == 'photo' ) {
                            $post['photo'] = $val['savepath'].$val['savename'];
                        }
                    }
                }
            }
            

            $result = $DContract->add($post);
            if ( $result['result'] ) {
                //显示合同详情信息
                $this->success("合同生成成功!",U("Home/Steward/check_contract",array("id"=>$result['id'])));
                /*$this->success("合同生成成功,请到个人页面进行支付",U("Home/Steward/houses"));*/
            } else {
                $this->error($result['error_msg']);
            }
        } else {
            $id = I('id');//room_id
            $DRoom = D("houses");
            $this->assign('today',date("Y-m-d",time()));
            $this->assign('room_info',$DRoom->getRoom($id));
            $this->display("check-in");
        }
    }

    /**
    * [查看合同账单]
    **/
    public function check_contract(){
        $id = I("id");
        $MPay = M("pay");
        $contract_info=$MPay
                    ->field('a.*,c.*,p.*')
                    ->alias('p')
                    ->join('lewo_contract c ON p.pro_id=c.pro_id')
                    ->join('lewo_account a ON a.id=c.account_id')
                    ->where(array('p.id'=>$id))
                    ->find();
        
        $rent_type = explode("_",$contract_info['rent_type']);

        $contract_info['rent_type'] = $rent_type;
        $this->assign("contract_info",$contract_info);
        $this->display("detail-contract");
    }

    //管家查看的租客合同 自有管家才能进
    public function tenant_contract(){
        $account_id = I('account_id');
        $room_id    = I('room_id');
        $pro_id = I('pro_id');
        $filters = empty($pro_id)
        ? ['c.account_id' => $account_id, 'c.room_id' => $room_id]
        : ['c.pro_id' => $pro_id];

        $MContract = M('contract');
        $contract_list = $MContract
        ->alias('c')
        ->field('a.mobile,a.realname,c.deposit,c.book_deposit,c.balance,c.period,c.rent_type,c.contract_status,c.actual_end_time,c.start_time,c.end_time,c.rent_date,c.rent,c.fee,c.contact2,c.person_count,c.photo,c.wg_fee,c.total_fee,c.pro_id,r.room_code,r.house_code,h.area_id,area.area_name,p.price')
        ->join('lewo_pay p ON p.pro_id = c.pro_id')
        ->join('lewo_account a ON a.id = c.account_id')
        ->join('lewo_room r ON r.id = c.room_id')
        ->join('lewo_houses h ON h.house_code = r.house_code')
        ->join('lewo_area area ON h.area_id = area.id')
        ->where($filters)
        ->select();
        foreach ($contract_list as $key => $val) {
            $contract_list[$key]['rent_type'] = explode('_', $val['rent_type']);
        }

        $this->assign('contract_list',$contract_list);
        $this->display('tenant-contract');
    }

     /**
    * [发送立刻缴费]
    **/
    public function send_contract(){
        $pro_id = I("pro_id");
        $pay_info = M("pay")->where(array("pro_id"=>$pro_id))->find();
        $mobile = M("account")->where(array("id"=>$pay_info['account_id']))->getField("mobile");
        M("pay")->where(array("pro_id"=>$pro_id))->save(array('is_send'=>1));
        Vendor('ChuanglanSms.chuanglanSmsApi');
        $clapi  = new \ChuanglanSmsApi();

        $msg = '欢迎你来到乐窝~请到个人页面进行支付 http://'.$_SERVER['HTTP_HOST'];
        $result = $clapi->sendSMS($mobile, $msg,'true');
        $result = $clapi->execResult($result);
        $send_time = date("Y-m-d H:i:s",time());
        M("sms_log")->add(array("mobile"=>$mobile,"message"=>$msg,"send_time"=>$send_time,"account_id"=>$pay_info['account_id'],"room_id"=>$pay_info['room_id'],"result"=>serialize($result)));
        if($result[1]==0){
            $this->success("发送成功");
        } else {
            $this->success("发送失败");
        }
    }
    

    /**
     * [管家待办]
     **/
    public function stewardtasks(){
        $DSchedule = D("schedule");
        $schedule_list = $DSchedule->getScheduleBySteward($_SESSION['steward_id']);
        $this->assign('schedule_list',$schedule_list);
        $tf_count = $DSchedule->getScheduleCount($_SESSION['steward_id'],C("schedule_type_tf"),0,C("admin_type_gj"));
        $zf_count = $DSchedule->getScheduleCount($_SESSION['steward_id'],C("schedule_type_zf"),0,C("admin_type_gj"));
        $hf_count = $DSchedule->getScheduleCount($_SESSION['steward_id'],C("schedule_type_hf"),0,C("admin_type_gj"));
        $jd_count = $DSchedule->getScheduleCount($_SESSION['steward_id'],C("schedule_type_jd"),0,C("admin_type_gj"));
        
        $this->assign("tf_count",$tf_count);
        $this->assign("zf_count",$zf_count);
        $this->assign("hf_count",$hf_count);
        $this->assign("jd_count",$jd_count);
        $this->display('steward-tasks');
        $this->display("Steward/common/footer");
    }

    /**
    * [退房管家抄水电气]
    **/
    public function check_ammeter(){
        if ( !empty($_POST) ) {
            $schedule_type = I("schedule_type");
            $schedule_id = I("schedule_id");
            $MSchedule = M("schedule");
            $schedule_info = $MSchedule->where(array("id"=>$schedule_id))->find();
            $MRoom = M("room");
            $MHouses = M("houses");
            $house_code = $MRoom->where(array("id"=>$schedule_info['room_id']))->getField("house_code");
            $house_id = $MHouses->where(array("house_code"=>$house_code))->getField("id");
            $DAmmeter = D("ammeter_house");
            $ammeter_house = $DAmmeter->getFirstInfoByHouseId($house_id); //获取一条最新的水电气信息
            //判断录入的水电气是否低于最新录入的水电气
            $not_judge = I("not_judge");
            if ( empty($not_judge) && $not_judge != 1 ) {
                if ( I("post.zS") < $ammeter_house['total_water'] ) {
                    $this->error("录入的水度数:".I("post.zS")."少于最新水度数:".$ammeter_house['total_water']."，请检查!","",10);
                }
                if ( I("post.zD") < $ammeter_house['total_energy'] ) {
                    $this->error("录入的电度数:".I("post.zD")."少于最新电度数:".$ammeter_house['total_energy']."，请检查!","",10);
                }
                if ( I("post.zQ") < $ammeter_house['total_gas'] ) {
                    $this->error("录入的气度:".I("post.zQ")."少于最新气度数:".$ammeter_house['total_gas']."，请检查!","",10);
                }
            }

            $data['account_id'] = $schedule_info['account_id'];
            $data['create_time'] = date("Y-m-d H:i:s",time());
            $data['create_date'] = $schedule_info['create_date'];
            $data['mobile'] = $schedule_info['mobile'];
            $data['schedule_type'] = $schedule_type;
            $data['status'] = 2;//已经录入完水电气
            $data['room_id'] = $schedule_info['room_id'];
            $data['pay_account'] = $schedule_info['pay_account'];
            $data['pay_type'] = $schedule_info['pay_type'];
            $data['appoint_time'] = $schedule_info['appoint_time'];
            $data['msg'] = $schedule_info['msg'];
            $data['is_break'] = $schedule_info['is_break'];
            $data['steward_id'] = $schedule_info['steward_id'];
            $data['admin_type'] = C("admin_type_cw");
            $data['check_out_type'] = I("check_out_type");
            $data['zS'] = I("zS");
            $data['zD'] = I("zD");
            $data['zQ'] = I("zQ");
            $data['roomD'] = I("roomD");
            $data['wx_fee'] = I("wx_fee");
            $data['wx_des'] = I("wx_des");
            $data['last_schedule_id'] = $schedule_id;//上一个schedule_id
            //将租客申请的待办改成完成
            $MSchedule->where(array("id"=>$schedule_id))->save(array("is_finish"=>1));
            //插入一条新的待办
            $result = $MSchedule->add($data);
            if ( $result ) {
                $this->success("提交成功",U("Home/Steward/index"));
            } else {
                $this->error("提交失败");
            }
        } else {
            $schedule_type = I("schedule_type");
            $schedule_id = I("schedule_id");

            $DSchedule = D("schedule");
            $schedule_info = $DSchedule->getScheduleByID($schedule_id);
            $this->assign("schedule_info",$schedule_info);

            $DAccount = D("account");
            $account_info = $DAccount->getAccountInfoById($schedule_info['account_id']);
            $this->assign("account_info",$account_info);

            $DRoom = D("room");
            $room_info = $DRoom->getRoom($schedule_info['room_id']);
            $this->assign("room_info",$room_info);
            $this->assign("schedule_type",$schedule_type);
            $this->assign("schedule_type_name",C("schedule_type_arr")[$schedule_type]);
            $this->assign("schedule_id",$schedule_id);
            $this->display("check-ammeter");
        }
    }

    /**
    * [还原密码]
    **/
    public function recovery(){

        $account_id = I('account_id');
        $MAccount   = M('account');
        $MAccount->startTrans();

        $where['id'] = $account_id;
        $save['password'] = md5('123456');
        $result = $MAccount->where($where)->save($save);
        if ($result){
            // 成功后返回客户端新增的用户ID，并返回提示信息和操作状态
            $MAccount->commit();
            $result['msg'] = '修改成功';
            $this->ajaxReturn($result,'JSON');
        }else{
            // 错误后返回错误的操作状态和提示信息
            $result['msg'] = '修改失败';
            $MAccount->rollback();
            $this->ajaxReturn($result,'JSON');
        }
    }

    /**
    * [管家处理租客退房]
    **/
    public function check_out(){
        $steward_id = $_SESSION['steward_id'];
        $account_id = I('account_id');
        $room_id    = I('room_id');
        $type       = I('type');
        $MAccount   = M('account');
        $MRoom      = M('room');
        $DSchedule  = D('schedule');
        $DAmmeter   = D("ammeter_house");
        $DAmmeterRoom = D("ammeter_room");
        $flag       = true;
        switch ($type) {
            case 2:
                $schedule_type = 1;//退房
                break;
            case 3:
                $schedule_type = 2;//换房
                break;
            case 4:
                $schedule_type = 3;//转房
                break;
            case 5:
                $schedule_type = 1;//退房
                $param['check_out_type'] = 1;//违约退租
                break;
            default:
                $this->error('type丢失');
                break;
        }
        M()->startTrans();
        if ( empty($account_id) || empty($room_id) ) $this->error('数据丢失');
        if ( !empty($_POST) ) {
            if ( I('is_success') != 1 ) $this->error('未验房');
            //判断是否已经提交退房请求

            $check_item_keys  = array_fill_keys(array_keys(C('check_item')),'0');
            $check_item_data  = serialize(array_replace($check_item_keys, I('check_item')));

            $house_id = $MRoom->alias('r')->join('lewo_houses h ON r.house_code=h.house_code')->where(array('r.id'=>$room_id))->getField('h.id');
            //判断录入的水电气是否低于最新录入的水电气
            $ammeter_house = $DAmmeter->getFirstInfoByHouseId($house_id); //获取一条最新的水电气信息
            if ( I("post.zS") < $ammeter_house['total_water'] ) {
                $this->error("录入的水度数:".I("post.zS")."少于最新水度数:".$ammeter_house['total_water']."，请检查!","",10);
            }
            if ( I("post.zD") < $ammeter_house['total_energy'] ) {
                $this->error("录入的电度数:".I("post.zD")."少于最新电度数:".$ammeter_house['total_energy']."，请检查!","",10);
            }
            if ( I("post.zQ") < $ammeter_house['total_gas'] ) {
                $this->error("录入的气度:".I("post.zQ")."少于最新气度数:".$ammeter_house['total_gas']."，请检查!","",10);
            }
            foreach ( I('roomD') AS $key=>$val ) {
                //获取上个月 房间的电表
                $last_ammeter_room_info = $DAmmeterRoom->getFirstInfoByRoomId($key);
                if ( !is_null($last_ammeter_room_info) ) {
                    if ( $val['room_energy'] < $last_ammeter_room_info['room_energy'] ) {
                        $this->error($val['room_code']."该房间电度数低于上个月!请重新录入");
                    }
                }
            }
            $roomD = serialize(i_array_column(I('roomD'), 'room_energy'));
            $mobile = M('account')->where(['id'=>$account_id])->getField('mobile');
            $param = [
                'steward_id'    => $_SESSION['steward_id'],
                'account_id'    => $account_id,
                'mobile'        => $mobile,
                'room_id'       => $room_id,
                'schedule_type' => $schedule_type,
                'check_item'    => $check_item_data,
                'pay_account'   => I('pay_account'),
                'pay_type'      => I('pay_type'),
                'msg'           => I('check_out_msg'),
                'admin_type'    => C('admin_type_cn'),//出纳
                'zS'            => I('zS'),
                'zD'            => I('zD'),
                'zQ'            => I('zQ'),
                'roomD'         => $roomD,
                'wx_fee'        => I('wx_fee'),
                'wx_des'        => I('check_out_msg'),
                'status'        => 2,
                'check_out_goods' => serialize(I('goods')),
            ];

            //将数据放入schedule表中，展示给后台看
            $result = $DSchedule->create_new_schedule($param);
            if ( !$result ) $this->error('插入待办有误，请联系管理员!');
exit;
            //执行退房修改操作
            //修改房间状态
            $room_result = $MRoom->where(array('id'=>$room_id))->save(array('account_id'=>0,'status'=>0));
            if ( $room_result != 1 ) $flag = false;
            if ( $flag ) {
                /*M()->commit();*/
                $this->success('提交成功');
            } else {
                M()->rollback();
                $this->error('提交失败');
            }
        } else {
            $DAccount = D("account");
            $DRoom    = D("room");
            $account_info = $DAccount->getAccountInfoById($account_id);
            $room_info = $DRoom->getRoom($room_id);
            $room_list = $MRoom->field('id,room_code')->where(array('house_id'=>$room_info['house_id'],'is_show'=>1))->select();


            $this->assign('room_list',$room_list);
            $this->assign('check_item',C('check_item'));
            $this->assign('check_out_goods',C('check_out_goods'));
            $this->assign('account_id',$account_id);
            $this->assign('room_id',$room_id);
            $this->assign('type',$type);            
            $this->assign('refund_type',C('refund_type'));
            $this->assign('check_out_type',C('check_out_type'));
            $this->assign('now_day',date('Y-m-d',time()));
            $this->assign("account_info",$account_info);
            $this->assign("room_info",$room_info);
            $this->display("check-ammeter");
        }
    }
}