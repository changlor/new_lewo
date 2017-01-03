<?php
namespace Admin\Controller;
use Think\Controller;
class TaskController extends BaseController {
    public function __construct(){
        parent::__construct();
        if ( empty($_SESSION['username']) && ACTION_NAME != 'register') {
            header("Location:".U("Admin/Index/login"));
            die();
        }
    }
    
    public function index(){
        $DSchedule = D("schedule");
        $DBackBill = D("back_bill");
        $schedule_list = $DSchedule->getScheduleList();
        $TFcount = $DSchedule->getScheduleCount(C("schedule_type_tf"),0);
        $ZFcount = $DSchedule->getScheduleCount(C("schedule_type_zf"),0);
        $HFcount = $DSchedule->getScheduleCount(C("schedule_type_hf"),0);
        $LXDKcount = $DBackBill->where(array("is_finish"=>0,"is_affirm"=>2,"back_type"=>1))->count();
        $LXDKYEcount = $DBackBill->where(array("is_finish"=>0,"is_affirm"=>2,"back_type"=>2))->count();
        $_SESSION['schedule_count'] = $TFcount + $ZFcount + $HFcount + $LXDKcount + $LXDKYEcount;
        $this->assign("TFcount",$TFcount);
        $this->assign("ZFcount",$ZFcount);
        $this->assign("HFcount",$HFcount);
        $this->assign("LXDKcount",$LXDKcount);
        $this->assign("LXDKYEcount",$LXDKYEcount);

        $this->assign("schedule_list",$schedule_list);
    	$this->display("Common/header");
    	$this->display("Common/nav");
    	$this->display("tasks");
    	$this->display("Common/footer");
    }

    /**
    * [重新计算待办未完成数目]
    **/
    public function refesh_schedule_count(){
        //待办数量
        $DSchedule = D("schedule");
        $schedule_list = $DSchedule->getScheduleList();
        $TFcount = $DSchedule->getScheduleCount(C("schedule_type_tf"),0);
        $ZFcount = $DSchedule->getScheduleCount(C("schedule_type_zf"),0);
        $HFcount = $DSchedule->getScheduleCount(C("schedule_type_hf"),0);
        $LXDKcount = D("back_bill")->where(array("is_finish"=>0,"is_affirm"=>2,"back_type"=>1))->count();
        $LXDKYEcount = D("back_bill")->where(array("is_finish"=>0,"is_affirm"=>2,"back_type"=>2))->count();
        $_SESSION['schedule_count'] = $TFcount+$ZFcount+$HFcount+$LXDKcount+$LXDKYEcount;
        die(json_encode(array("schedule_count"=>$_SESSION['schedule_count'])));
    }

    /**
    * [已办列表]
    **/
    public function finish_task(){
        $DSchedule = D("schedule");
        $schedule_list = $DSchedule->getAllSchedule();

        $TFcount = $DSchedule->getScheduleCount(C("schedule_type_tf"),1);
        $ZFcount = $DSchedule->getScheduleCount(C("schedule_type_zf"),1);
        $HFcount = $DSchedule->getScheduleCount(C("schedule_type_hf"),1);
        $LXDKcount = D("back_bill")->where(array("is_finish"=>1,"is_affirm"=>array("IN","1,2"),"back_type"=>1))->count();
        $LXDKYEcount = D("back_bill")->where(array("is_finish"=>1,"is_affirm"=>array("IN","1,2"),"back_type"=>2))->count();

        $this->assign("TFcount",$TFcount);
        $this->assign("ZFcount",$ZFcount);
        $this->assign("HFcount",$HFcount);
        $this->assign("LXDKcount",$LXDKcount);
        $this->assign("LXDKYEcount",$LXDKYEcount);

        $this->assign("schedule_list",$schedule_list);
        $this->display("Common/header");
        $this->display("Common/nav");
        $this->display("finish-task");
        $this->display("Common/footer");
    }

    /**
    * [处理退房账单]
    **/
    public function dispose_bill(){
        $MRoom = M("room");
        $MAccount = M("account");
        $DRoom = D("room");
        $DSchedule = D("schedule");
        if (parent::isPostRequest()) {            
            $schedule_id = I('post.schedule_id');
            $schedule_info = $DSchedule->getScheduleInfo($schedule_id);

            $schedule_type = $schedule_info['schedule_type'];

            $rent = $MRoom->where(['id'=>I("room_id")])->getField("rent");//房租

            if ( C("schedule_type_zf") == $schedule_type || C("schedule_type_hf") == $schedule_type ) {
                //如果是换房或者转房，要收取手续费
                $data['handling_fee'] = C('handling_percent')*$rent;
            }

            //插入退房水电气账单
            $data['pro_id'] = getOrderNo();
            $data['room_id'] = I("room_id");
            $data['house_code'] = I("house_code");
            $data['account_id'] = I("account_id");
            $data['person_day'] = I("person_day");
            $data['room_energy_fee'] = I("room_energy_fee");
            $data['public_energy_fee'] = I("public_energy_fee");
            $data['energy_fee'] = I("energy_fee");
            $data['water_fee'] = I("water_fee");
            $data['gas_fee'] = I("gas_fee");
            $data['wx_fee'] = I("wx_fee");
            $data['wx_des'] = I("wx_des");
            $data['total_fee'] = I("total_fee");
            $data['total_person_day'] = I("sum_person_day");
            $data['create_time'] = date("Y-m-d H:i:s",time());
            $data['pay_status'] = 0;
            $data['total_energy'] = I("total_energy_fee");
            $data['total_water'] = I("public_water_fee");
            $data['total_gas'] = I("public_gas_fee");
            $data['start_energy'] = I('start_energy'); //电始度数
            $data['end_energy'] = I('end_energy'); //电止度数
            $data['start_water'] = I('start_water'); //水始度数
            $data['end_water'] = I('end_water'); //水止度数
            $data['start_gas'] = I('start_gas'); //气始度数
            $data['end_gas'] = I('end_gas'); //气止度数
            $data['end_room_energy'] = I("end_room_energy");
            $data['start_room_energy'] = I("start_room_energy");
            $data['room_energy_add'] = I("room_energy_add");
            $data['late_pay_date'] = date("Y-m-d H:i:s",time());
            $data['should_pay_date'] = date("Y-m-d H:i:s",time());
            $data['input_year'] = date("Y",time());
            $data['input_month'] = date("m",time());
            $data['realname'] = $MAccount->where(array("id"=>$data['account_id']))->getField("realname");
            $data['total_person_energy'] = $data['room_energy_fee'] + $data['energy_fee'];
            switch ($schedule_type) {
                case '1':
                    $data['type'] = 2;//退租水电气结算
                    break;
                case '2':
                    $data['type'] = 3;//转租水电气结算
                    break;
                case '3':
                    $data['type'] = 4;//换租水电气结算
                    break;
                default:
                    $data['type'] = 1;//日常
                    break;
            }
            
            $result = M("charge_bill")->add($data);
            if ( $result ) {
                M("schedule")->where(array("id"=>$schedule_id))->save(array("is_finish"=>1));//生成水电气成功修改状态
                //插入一条发送账单确认待办
                $DSchedule = D("schedule");
                $result2 = $DSchedule->addNewSchdule($schedule_id,$schedule_type,3,C("admin_type_cw"));
                if ( !$result2 ) {
                    $this->error("插入发送账单确认待办失败",U("Admin/Task/index"));
                }
                $this->success("生成退房水电气成功!",U("Admin/Task/index"));
            } else {
                $this->error("生成退房水电气失败!",U("Admin/Task/index"));
            }
        } else {
            // 待办id
            $schedule_id = I('schedule_id');
            // 获取待办数据
            $schedule_info =  $DSchedule->getScheduleInfo($schedule_id);
            
            $schedule_type_name = C("schedule_type_arr")[$schedule_info['schedule_type']];
            
            $room_id = $schedule_info['room_id'];
            $DRoom = D("room");
            
            $appoint_time = $schedule_info['appoint_time'];//约定时间，人日算到这里
            $year = date("Y",strtotime($appoint_time));//约定时间 年
            $month = date("m",strtotime($appoint_time));//约定时间 月
            $day = date("d",strtotime($appoint_time));//约定时间 日

            $house_code = $DRoom->getHouseCodeById($room_id);
            $this->assign("house_code",$house_code);

            $DHouses = D("houses");
            $house_id = $DHouses->getHouseIdByCode($house_code);
            $person_count = $DHouses->getPersonCountByCode($house_code); //该房屋总人数
            $room_count = $DHouses->getRoomCountByCode($house_code); //该房屋的房间数量
            $sum_person_day = $DHouses->TFgetPersonDayCount($house_code,$appoint_time); //该房屋总人日

            $this->assign("sum_person_day",$sum_person_day);

            $room_list = $DHouses->getRoomList($house_code);
            $house_info = $DHouses->getHouse($house_code);

            $DArea = D("area");
            $area_info = $DArea->getAreaById($house_info['area_id']); //水气单价
            $water_unit = $area_info['water_unit']; //水费单价
            $gas_unit = $area_info['gas_unit']; //气费单价
            $energy_stair_arr = explode(",",$area_info['energy_stair']); //阶梯电费单价
            $rubbish_fee = $area_info['rubbish_fee']; //燃气垃圾费
            foreach ($energy_stair_arr AS $key=>$val) {
                $energy_stair[] = explode("-",$val);//阶梯算法数组
            }

            $DAmmeter = D("ammeter_house");
            $ammeter_house = $DAmmeter->getFirstInfoByHouseId($house_id); //获取一条最新的水电气信息

            $this->assign("ammeter_house",$ammeter_house);
            //计算退房那天抄的水电气和最新的水电气

            $add_energy = $schedule_info['zd'] - $ammeter_house['total_energy']; //电费增加度数
            
            $total_energy_fee = get_energy_fee($add_energy,$energy_stair);

            if ( $house_info['type'] == 1 ) {
                //房间总电费
                $room_total_energy_fee = $DHouses->get_room_total_energy_fee($house_code,$year,$month,$energy_stair);
                $public_energy_fee = $total_energy_fee - $room_total_energy_fee;//公共区域电费 = 总电费 - 房间总电费
            } else {
                $room_total_energy_fee = 0;
                $public_energy_fee = $total_energy_fee; //公共区域电费
            }
            $this->assign("total_energy_fee",$total_energy_fee);
            $this->assign("room_total_energy_fee",$room_total_energy_fee);
            $this->assign("public_energy_fee",$public_energy_fee);

            $add_water = $schedule_info['zs'] - $ammeter_house['total_water']; //水费增加度数
            $public_water_fee = $add_water * $water_unit; //公共水费
            
            $add_gas = $schedule_info['zq'] - $ammeter_house['total_gas']; //水费增加度数
            $public_gas_fee = $add_gas * $gas_unit;//公共气费
            $this->assign("public_water_fee",$public_water_fee);
            $this->assign("public_energy_fee",$public_energy_fee);
            $this->assign("public_gas_fee",$public_gas_fee);
            $this->assign("add_water",$add_water);
            $this->assign("add_energy",$add_energy);
            $this->assign("add_gas",$add_gas);
            $this->assign("water_unit",$water_unit);
            
            $this->assign("gas_unit",$gas_unit);
            $this->assign("energy_stair",$area_info['energy_stair']);

            $DAmmeterRoom = D("ammeter_room");
            $MAccount = M("account");
            $MContract = M("contract");
            $DContract = D("contract");
            $DChargeBill = D("charge_bill");
            
            $contract_list = $DContract->getContractListByDateForDailyBill($house_code,$year,$month);

            foreach ( $contract_list AS $key=>$val ) {
                $contract_list[$key]['realname'] = $MAccount->where(array("id"=>$val['account_id']))->getField("realname");
                $ht_start_date = $val['start_time']; //合同开始日
                $ht_end_date = $val['end_time']; //合同结束日
                $ht_actual_end_time = $val['actual_end_time'];//实际退房日
                //人日 需要计算租客申请的日数
                $ht_person = $val['person_count'];//合同人数
                //当前执行退房的id
                $room_id = $val['room_id'];
                if ( $schedule_info['room_id'] == $room_id ) {
                    //先判断这房屋是按间的还是按床的 房屋类型 1：合租按间 按间的话才获取房间电表 2：合租按床 
                    switch ($house_info['type']) {
                        case 1:
                            $ammeter_room = $DAmmeterRoom->getFirstInfo($room_id,$house_id); //最新的房间抄表信息
                            $add_room_energy = $schedule_info['roomd'] - $ammeter_room['room_energy'];
                            $room_energy_fee = get_energy_fee($add_room_energy,$energy_stair);

/*                            $contract_list[$key]['wg_fee'] = $house_info['fee'] / $room_count; //物业费/房间数*/
                            $contract_list[$key]['rubbish_fee'] = $rubbish_fee / $room_count;
                            break;
                        
                        case 2:
                            /*$contract_list[$key]['wg_fee'] = $house_info['fee'] / $bed_count; //物业费/床位数*/
                            $contract_list[$key]['rubbish_fee'] = $rubbish_fee / $bed_count;
                            break;
                    }      

                    $contract_list[$key]['add_room_energy'] = !empty($add_room_energy)?$add_room_energy:0;
                    $contract_list[$key]['start_room_energy'] = !empty($schedule_info['roomd'])?$schedule_info['roomd']:0;
                    $contract_list[$key]['end_room_energy'] = !empty($ammeter_room['room_energy'])?$ammeter_room['room_energy']:0;
                    $contract_list[$key]['room_energy_fee'] = ceil($room_energy_fee*100)/100;
                    $contract_list[$key]['wx_fee'] = $schedule_info['wx_fee'];//维修费
                    $contract_list[$key]['wx_des'] = $schedule_info['wx_des']; //退房信息
                } else {
                    $contract_list[$key]['room_energy_fee'] = 0;
                }

                //人日计算 start
                $person_day = 0;

                $is_start_date = $DContract->bothTimeIsEqual($ht_start_date,$appoint_time);
                //判断租客是否当月退房
                $is_end_date = $DContract->bothTimeIsEqual($ht_actual_end_time,$appoint_time);

                if ( $is_start_date ) {
                    if ( $is_end_date ) {
                        //合同开始和实际退房的月份一样，获取两者之间的日数
                        $lastday = date('Y-m-d', strtotime($ht_actual_end_time)); //退房时间
                        $person_day = round((strtotime($lastday)-strtotime($ht_start_date))/86400)+1;
                        $person_day*=$ht_person;
                    } else {
                        //是这个月入住则，人日获取的是，租期开始到月末的日数
                        $lastday = date('Y-m-d', strtotime($appoint_time)); //约定时间
                        $person_day = round((strtotime($lastday)-strtotime($ht_start_date))/86400)+1;
                        $person_day*=$ht_person;
                    }
                } else {
                    if ( $is_end_date ) {
                        //这个月就是退房的月数 那么这个月的人日就是到这一天
                        //判断实际退房和退房约定时间的大小，取小的那个
                        if ( strtotime($appoint_time) <= strtotime($ht_actual_end_time) ) {
                            $person_day = date("d",strtotime($appoint_time));
                        } else {
                            $person_day = date("d",strtotime($ht_actual_end_time));
                        }
                    } else {
                        //不是这个月入住也不是这个月退房的，则获取这个月的日数
                        // 因为是退房，所以获取是约定时间的日数
                        $person_day = date("d",strtotime($appoint_time));
                    }
                }
                //人日计算 end
                $contract_list[$key]['person_day'] = $person_day*$ht_person;

                //手续费
                if ( C("schedule_type_zf") == $schedule_type || C("schedule_type_hf") == $schedule_type ) {
                    //如果是换房或者转房，要收取手续费
                    $contract_list[$key]['handling_fee'] = C('handling_percent')*$val['rent'];
                }

                $contract_list[$key]['water_fee'] = ceil(($public_water_fee / $sum_person_day * $person_day)*100)/100;
                $contract_list[$key]['energy_fee'] = ceil(($public_energy_fee / $sum_person_day * $person_day)*100)/100;
                $contract_list[$key]['gas_fee'] = ceil(($public_gas_fee / $sum_person_day * $person_day)*100)/100;

                $contract_list[$key]['total_fee'] = $contract_list[$key]['room_energy_fee']+$contract_list[$key]['energy_fee']+$contract_list[$key]['water_fee']+$contract_list[$key]['gas_fee']+$contract_list[$key]['rubbish_fee']+$contract_list[$key]['wx_fee']+$contract_list[$key]['handling_fee'];
            }
            $this->assign("schedule_type_name",$schedule_type_name);
            $this->assign("schedule_info",$schedule_info);
            $this->assign("sum_person_day",$sum_person_day);
            $this->assign("person_day_area_date",date("Y-m-01",strtotime($schedule_info['appoint_time']))."~".date("Y-m-d",strtotime($schedule_info['appoint_time'])));

            $this->assign("contract_list",$contract_list);
            $this->display("dispose-bill");
        }
    }

    /**
    * [修改待办信息]
    **/
    public function update_schedule(){
        if ( !empty($_POST) ) {
            $schedule_id = I("schedule_id");
            $data['zS'] = I('zs');
            $data['zD'] = I('zd');
            $data['zQ'] = I('zq');
            $data['roomD'] = I('roomd');
            $data['wx_fee'] = I('wx_fee');
            $data['wx_des'] = I('wx_des');
            $result = M("schedule")->where(array("id"=>$schedule_id))->save($data);
            if ( $result ) {
                $this->success("修改成功!",U("Admin/Task/index"));
            } else {
                $this->error("修改失败!",U("Admin/Task/index"));
            }
        } else {
            $schedule_id = I("schedule_id");
            $DSchedule = D("schedule");
            $schedule_info = $DSchedule->getScheduleInfo($schedule_id);
            $this->assign("schedule_info",$schedule_info);
            $this->assign("schedule_id",$schedule_id);
            $this->display("update-schedule");
        }  
    }

    /**
    * [退房确认][列出未支付的账单并从押金中抵扣]
    **/
    public function checkout_pay(){
        $schedule_id = I("schedule_id");
        $DSchedule = D("schedule");
        $schedule_info = $DSchedule->getScheduleInfo($schedule_id);
        $DChargeBill = D("charge_bill");
        $notpaylist = $DChargeBill->getNotPayList($schedule_info['account_id']);
        $DContract = D("contract");
        $contract = $DContract->getContract($schedule_info['account_id'],$schedule_info['room_id']);

        $sum_total_fee = 0;
        foreach ($notpaylist AS $key=>$val) {
            $sum_total_fee += $val['total_fee'];
        }
        $residue_deposit = $contract['deposit'] - $sum_total_fee;//剩余押金

        if ( $residue_deposit < 0 ) {
            //当剩余押金小于0则需要支付
            $this->assign("is_error",1);
            /*$this->error("剩余押金少于0，警告",U("Admin/Task/index"));*/
        }
        $this->assign("contract",$contract);
        $this->assign("schedule_id",$schedule_id);
        $this->assign("residue_deposit",$residue_deposit);
        $this->assign("deposit",$contract['deposit']);
        $this->assign("sum_total_fee",$sum_total_fee);
        $this->assign("notpaylist",$notpaylist);
        $this->display("checkout-pay");
    }

    /**
    * [发送退款确认账单]
    **/
    public function send_back_bill(){
        $schedule_id = I("schedule_id");
        $residue_deposit = I("residue_deposit");
        $DSchedule = D("schedule");
        $schedule_info = $DSchedule->getScheduleInfo($schedule_id);
        $schedule_type = $schedule_info['schedule_type'];

        $DChargeBill = D("charge_bill");
        $notpaylist = $DChargeBill->getNotPayList($schedule_info['account_id']);
        //将所以未支付的账单改成发送状态
        foreach($notpaylist AS $key=>$val){
            $DChargeBill->where(array('id'=>$val['id']))->save(array('is_send'=>1));
        }

        //创蓝接口
        Vendor('ChuanglanSms.chuanglanSmsApi');
        $clapi  = new \ChuanglanSmsApi();
        $msg = '亲爱的乐窝小主,请确认您的退房账单,押金抵扣后剩余金额:'.$residue_deposit.' http://'.$_SERVER['HTTP_HOST'];
        $result = $clapi->sendSMS($schedule_info['mobile'], $msg,'true');
        $result = $clapi->execResult($result);
        if($result[1]==0){
            //发送账单成功 修改待办信息
            M("schedule")->where(array("id"=>$schedule_id))->save(array("is_finish"=>1));
            //插入一条新的待办，是租客点击确认账单的待办
            $new_schedule_id =  D("schedule")->addNewSchdule($schedule_id,$schedule_type,4,C("admin_type_cw"));
            $send_time = date("Y-m-d H:i:s",time());
            M("sms_log")->add(array("mobile"=>$schedule_info['mobile'],"account_id"=>$schedule_info['account_id'],"room_id"=>$schedule_info['room_id'],"message"=>$msg,"send_time"=>$send_time,"result"=>serialize($result)));
        }
        $DBackBill = D("back_bill");
        $arr['money'] = $residue_deposit;
        $arr['account_id'] = $schedule_info['account_id'];
        $arr['room_id'] = $schedule_info['room_id'];
        $arr['mobile'] = $schedule_info['mobile'];
        $arr['pay_account'] = $schedule_info['pay_account'];
        $arr['pay_type'] = $schedule_info['pay_type'];
        $arr['schedule_id'] = !empty($new_schedule_id)?$new_schedule_id:0;

        if ( C("schedule_type_hf") == $schedule_type ) {
            $arr['back_type'] = 2;//换房是打到余额中
        } else {
            $arr['back_type'] = 1;//其他退房 转房是打到帐号中
        }
        try{
            $result = $DBackBill->addOneBackBill($arr);
        }catch(Exception $e){ 
            $this->error("插入打款失败");
        }      

        $send_time = date("Y-m-d H:i:s",time());
        M("sms_log")->add(array("mobile"=>$schedule_info['mobile'],"message"=>$msg,"send_time"=>$send_time,"result"=>serialize($result)));
        $this->success("发送账单成功!",U("Admin/Task/index"));
    }

    /**
    * [退房/转房/换房][确认账单][财务修改账单支付状态]
    **/
    public function confirm_checkout_pay(){
        if ( !empty($_POST) ) {

            $back_bill_id = I("back_bill_id");
            $charge_id = I("id");
            $schedule_id = I("schedule_id");
            $schedule_info = M("schedule")->where(array('id'=>$schedule_id))->find();

            $schedule_type = $schedule_info['schedule_type'];
            $result = D("schedule")->setFinish($schedule_id);
            //财务确认后，修改back_bill is_affirm为2财务确认
            M("back_bill")->where(array("id"=>$back_bill_id))->save(array("is_affirm"=>2));
            //财务确认账单后，退房成功，修改房屋状态
            M("room")->where(array("id"=>$schedule_info['room_id']))->save(array("status"=>0,"account_id"=>0));
            //修改合同状态
            $save['actual_end_time'] = date("Y-m-d H:i:s",time());
            $save['contract_status'] = 4;//正常退租
            M("contract")->where(array("account_id"=>$schedule_info['account_id'],"room_id"=>$schedule_info['room_id'],"is_delete"=>0))->save($save);
            //完成全部操作，插入一条退房完成的和删除的待办
            D("schedule")->addNewSchdule($schedule_id,$schedule_type,6,C("admin_type_cw"),1,1);
            //完成全部操作，将此流程的待办变成is_delete=1
            $this->recursive_update_schedule($schedule_id);
            $DChargeBill = D("charge_bill");
            foreach($charge_id AS $key=>$val){
                $DChargeBill->setPayStatus($val,3);//余额抵押
            }
            $this->success("账单确认!",U("Admin/Task/index"));
        } else {
            $schedule_id = I("schedule_id");
            $schedule_info = D("schedule")->getScheduleInfo($schedule_id);
            $schedule_type = $schedule_info['schedule_type'];
            $notpaylist = D("charge_bill")->getNotPayList($schedule_info['account_id']);
            $contract = D("contract")->getContract($schedule_info['account_id'],$schedule_info['room_id']);
            
            $back_bill = M("back_bill")->where(array("account_id"=>$schedule_info['account_id'],"room_id"=>$schedule_info['room_id'],"is_affirm"=>1))->find();

            $sum_total_fee = 0;
            foreach($notpaylist AS $key=>$val){
                $sum_total_fee += $val['total_fee'];
            }
            $residue_deposit = $contract['deposit'] - $sum_total_fee;//剩余押金
            $this->assign("schedule_type_name",C("schedule_type_arr")[$schedule_type]);
            $this->assign("schedule_id",$schedule_id);
            $this->assign("residue_deposit",$residue_deposit);
            $this->assign("deposit",$contract['deposit']);
            $this->assign("sum_total_fee",$sum_total_fee);
            $this->assign("notpaylist",$notpaylist);
            $this->assign("back_bill",$back_bill);
            $this->display("confirm-checkout-pay");
        }
    }

    /**
    * [递归将待办的上一个schedule_id改成is_detele=1]
    **/
    public function recursive_update_schedule($schedule_id){
        $MSchedule = M("schedule");
        //修改id的is_detele=1
        $MSchedule->where(array("id"=>$schedule_id))->save(array("is_delete"=>1));
        //获取是否有上一个schedule_id
        $is_has_last_schedule_id = $MSchedule->where(array("id"=>$schedule_id))->getField("last_schedule_id");
        if ( !is_null($is_has_last_schedule_id) ) {
            $this->recursive_update_schedule($is_has_last_schedule_id);
        }
    }

    /*
     * [退房例行打款]
     */
    public function money_back(){
        if ( !empty($_POST) ) {
            $back_type = I("back_type"); //1的话因为是打动打款到支付宝所以修改状态就可以，2的话要转到帐号余额中
            $id_arr = I("id"); //back_bill中的id

            $DBackBill = D("back_bill");
            foreach($id_arr AS $key=>$val){
                $DBackBill->setFinish($val);
            }
            $MAccount = M("account");
            if ( 2 == $back_type ) {//转到帐号余额中
                foreach ($id_arr as $k => $v) {
                    $bill_info = $DBackBill->getById($v);
                    //插入余额中
                    $result = $MAccount->where(array("mobile"=>$bill_info['mobile']))->setInc('balance',$bill_info['money']);
                }
            }
            $this->success("例行打款完成",U("Admin/Task/index"));
        } else {
            $is_finish = I("is_finish");
            $back_type = I("back_type");
            $back_bill = D("back_bill")->showBackBillList($is_finish,2,$back_type);
            $this->assign("back_bill",$back_bill);
            $this->assign("back_type",$back_type);
            $this->display("money-back");
        }
    }

    /*
     * [已办例行打款]
     */
    public function money_back_finish(){
        $back_bill = D("back_bill")->showBackBillList(1);
        $this->assign("back_bill",$back_bill);
        $this->display("money-back");
    }
}