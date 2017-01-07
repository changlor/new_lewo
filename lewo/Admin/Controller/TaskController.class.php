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
        // 实例化
        $MAccount   = M('account');
        $MContract  = M('contract');
        $MRoom      = M('room');
        $MAccount   = M('account');
        $DRoom      = D('room');
        $DContract  = D('contract');
        $DChargeBill= D('charge_bill');
        $DEvents    = D('events');
        $DSchedule  = D('schedule');
        $DHouses    = D('houses');
        $DArea      = D('area');
        $DAmmeter   = D('ammeter_house');
        $DAmmeterRoom = D('ammeter_room');
        M()->startTrans();
        if (parent::isPostRequest()) {  
            // 待办ID
            $schedule_id = I('post.schedule_id');
            // 待办数据
            $schedule_info = $DSchedule->getScheduleInfo($schedule_id);
            // 判断是否修改 
            $is_update = (bool)trim(I('is_update'));
            if ($is_update) {
                // 修改待办
                $input = [
                    'schedule_id' => $schedule_id,
                    'total_energy' => I('post.end_energy'),
                    'total_water' => I('post.end_water'),
                    'total_gas' => I('post.end_gas'),
                    'total_room_energy' => I('post.room_list'),
                    'wx_fee' => I('post.wx_fee'),
                    'wx_des' => trim(I('post.wx_des')),
                ];
                $res = $DSchedule->putSchedule($input);
                if ($res) {
                    $this->success($res['msg']);
                } else {
                    $this->error($res['msg']);
                }
            } else {
                // 插入退房水电气账单
                $input = [
                    'room_id' => I('post.room_id'),
                    'house_id' => I('post.house_id'),
                    'house_code' => I('post.house_code'),
                    'account_id' => I('post.account_id'),
                    'person_day' => I('post.person_day'),
                    'room_energy_fee' => I('post.room_energy_fee'),
                    'public_energy_fee' => I('post.public_energy_fee'),
                    'public_water_fee' => I('post.public_water_fee'),
                    'public_gas_fee' => I('post.public_gas_fee'),
                    'energy_fee' => I('post.energy_fee'),
                    'water_fee' => I('post.water_fee'),
                    'gas_fee' => I('post.gas_fee'),
                    'wx_fee' => I('post.wx_fee'),
                    'wx_des' => trim(I('post.wx_des')),
                    'total_fee' => I('post.total_fee'),
                    'total_person_day' => I('post.sum_person_day'),
                    'create_time' => date('Y-m-d H:i:s',time()),
                    'total_energy' => I('post.total_energy_fee'),
                    'total_water' => I('post.public_water_fee'),
                    'total_gas' => I('post.public_gas_fee'),
                    'start_energy' => I('post.start_energy'),
                    'end_energy' => I('post.end_energy'),
                    'start_water' => I('post.start_water'),
                    'end_water' => I('post.end_water'),
                    'start_gas' => I('post.start_gas'),
                    'end_gas' => I('post.end_gas'),
                    'end_room_energy' => I('post.end_room_energy'),
                    'start_room_energy' => I('post.start_room_energy'),
                    'room_energy_add' => I('post.room_energy_add'),
                    'input_year' => date('Y',time()),
                    'input_month' => date('m',time()),
                    'type' => 2,
                    // 日常账单
                    'bill_type' => 3,
                    'is_send' => 1,
                ];
                
                $result = $DChargeBill->postChargeBill($input);
                // 生成水电气成功
                if ( $result['success'] ) {
                    // 修改待办为以完成
                    $updateScheduleResult = $DSchedule->finishedSchedule([
                        'scheduleId'=>$schedule_info['id']
                    ]);
                    // 生成一条event事件
                    $eventInput = [
                        'event_id' => $schedule_info['event_id'],
                        'account_id' => $schedule_info['account_id'],
                        'room_id' => $schedule_info['room_id'],
                        // 退房
                        'event_type' => 1,
                        // 财务生成了水电气
                        'event_status' => 3,
                    ];
                    $eventResult = $DEvents->postEvent($eventInput);
                    // 插入一条发送账单确认待办
                    $scheduleInput = [
                        'event_id' => $schedule_info['event_id'],
                        'account_id' => $schedule_info['account_id'],
                        'room_id' => $schedule_info['room_id'],
                        // 退房
                        'schedule_type' => 1,
                        // 财务生成了水电气
                        'status' => 3,
                        'username_id' => $_SESSION['username_id'],
                        'admin_type' => C('admin_type_cn'),
                        'pay_account' => $schedule_info['pay_account'], 
                        'pay_type' => $schedule_info['pay_type'], 
                        'appoint_time' => $schedule_info['appoint_time'], 
                        'steward_id' => $schedule_info['steward_id'], 
                    ];
                    $scheduleResult2 = $DSchedule->postSchedule($scheduleInput);

                    if ($eventResult && $scheduleResult2 && $updateScheduleResult) {
                        M()->commit();
                        $this->success("操作成功!",U("Admin/Task/index"));
                    } else {
                        M()->rollback();
                        $this->error($eventResult['msg'] . ',' .$scheduleResult2['msg'],U("Admin/Task/index"));
                    }
                } else {
                    $this->error($result['msg'],U("Admin/Task/index"));
                }
            }
        } else {
            // 待办id
            $schedule_id = I('schedule_id');
            // 获取待办数据
            $schedule_info =  $DSchedule->getScheduleInfo($schedule_id);
            // 获取待办名字
            $schedule_type_name = C("schedule_type_arr")[$schedule_info['schedule_type']];
            // 房间id
            $room_id = $schedule_info['room_id']; 
            // 退房时录入的房间电表
            $schedule_info['total_room_energy'] = unserialize($schedule_info['total_room_energy']);
            // 验房检查物品
            $schedule_info['check_item'] = unserialize($schedule_info['check_item']);
            // 退房退的物品
            $schedule_info['check_out_goods'] = unserialize($schedule_info['check_out_goods']);   
            // 约定时间，人日算到这里
            $appoint_time = $schedule_info['appoint_time'];
            // 约定时间 年
            $year = date("Y",strtotime($appoint_time));
            // 约定时间 月
            $month = date("m",strtotime($appoint_time));
            // 约定时间 日
            $day = date("d",strtotime($appoint_time));
            // 获取house_code
            $house_code = $DRoom->getHouseCodeById($room_id);
            // 获取house_id       
            $house_id = $DHouses->getHouseIdByCode($house_code);
            // 该房屋总人数
            $person_count = $DHouses->getPersonCountByCode($house_code); 
            // 该房屋的房间数量
            $room_count = $DHouses->getRoomCountByCode($house_code); 
            // 该房屋总人日
            $sum_person_day = $DHouses->TFgetPersonDayCount($house_code,$appoint_time); 
            // 获取房间列表
            $room_list = $DHouses->getRoomList($house_code);
            // 获取房屋信息
            $house_info = $DHouses->getHouse($house_code);  
            // 获取小区信息
            $area_info = $DArea->getAreaById($house_info['area_id']);
            // 水费单价
            $water_unit = $area_info['water_unit']; 
            // 气费单价
            $gas_unit = $area_info['gas_unit']; 
            // 阶梯电费单价
            $energy_stair_arr = explode(",",$area_info['energy_stair']); 
            // 燃气垃圾费
            $rubbish_fee = $area_info['rubbish_fee']; 
            // 阶梯算法数组
            foreach ($energy_stair_arr AS $key=>$val) {
                $energy_stair[] = explode("-",$val);
            }
            // 获取一条最新的水电气信息
            $ammeter_house = $DAmmeter->getFirstInfoByHouseId($house_id); 
            // 电费增加度数
            $add_energy = $schedule_info['total_energy'] - $ammeter_house['total_energy']; 
            // 计算电费
            $total_energy_fee = get_energy_fee($add_energy,$energy_stair);
            // 计算公共区域电费
            if ( $house_info['type'] == 1 ) {
                // 房间总电费
                $room_total_energy_fee = $DHouses->getCheckOutRoomTotalEnergyFee($schedule_info['total_room_energy'], $energy_stair);
                // 公共区域电费 = 总电费 - 房间总电费
                $public_energy_fee = $total_energy_fee - $room_total_energy_fee;
            } else {
                //公共区域电费 = 总电费
                $public_energy_fee = $total_energy_fee;
            }
            // 公共区域电费
            $public_energy_fee = $public_energy_fee < 0 ? 0: $public_energy_fee;
            // 水费增加度数
            $add_water = $schedule_info['total_water'] - $ammeter_house['total_water'];
            // 公共水费
            $public_water_fee = $add_water * $water_unit; 
            // 水费增加度数
            $add_gas = $schedule_info['total_gas'] - $ammeter_house['total_gas']; 
            // 公共气费
            $public_gas_fee = $add_gas * $gas_unit;
            // 获取该月有关联的合同
            $contract_list = $DContract->getContractListByDateForDailyBill($house_code,$year,$month);
            // 开始计算每个人的费用
            foreach ( $contract_list AS $key=>$val ) {
                // 合同开始日
                $ht_start_date = $val['start_time'];
                // 合同结束日
                $ht_end_date = $val['end_time']; 
                // 实际退房日
                $ht_actual_end_time = $val['actual_end_time'];
                // 合同人数
                $ht_person = $val['person_count'];
                // 当前房间退租时的电表总数
                $currentRoomEnergy = $schedule_info['total_room_energy'][$val['room_id']];
                // 房屋类型 1：合租按间 按间的话才获取房间电表 2：合租按床 
                switch ($house_info['type']) {
                    case 1:
                        // 最新的房间抄表信息
                        $ammeter_room = $DAmmeterRoom->getFirstInfo($val['room_id']); 
                        $add_room_energy = $currentRoomEnergy - $ammeter_room['room_energy'];
                        $room_energy_fee = get_energy_fee($add_room_energy, $energy_stair);
                        break;
                    case 2:
                        
                        break;
                }      

                $contract_list[$key]['add_room_energy'] = !empty($add_room_energy) ? $add_room_energy : 0;
                $contract_list[$key]['start_room_energy'] = !empty($ammeter_room['room_energy']) ? $ammeter_room['room_energy'] : 0;
                $contract_list[$key]['end_room_energy'] = $currentRoomEnergy;
                $contract_list[$key]['room_energy_fee'] = ceil($room_energy_fee*100)/100;
                // 维修费
                $contract_list[$key]['wx_fee'] = $schedule_info['wx_fee'];
                // 退房信息
                $contract_list[$key]['wx_des'] = $schedule_info['wx_des']; 

                // 人日计算 start
                $person_day = 0;
                // 判断合同开始和退房的年月是否一样
                $is_start_date = $DContract->bothTimeIsEqual($ht_start_date,$appoint_time);
                // 判断租客是否当月退房
                $is_end_date = $DContract->bothTimeIsEqual($ht_actual_end_time,$appoint_time);

                if ( $is_start_date ) {
                    if ( $is_end_date ) {
                        // 合同开始和实际退房的月份一样，获取两者之间的日数
                        $lastday = date('Y-m-d', strtotime($ht_actual_end_time)); //退房时间
                        $person_day = round((strtotime($lastday)-strtotime($ht_start_date))/86400)+1;
                        $person_day*=$ht_person;
                    } else {
                        // 是这个月入住则，人日获取的是，租期开始到月末的日数
                        $lastday = date('Y-m-d', strtotime($appoint_time)); //约定时间
                        $person_day = round((strtotime($lastday)-strtotime($ht_start_date))/86400)+1;
                        $person_day*=$ht_person;
                    }
                } else {
                    if ( $is_end_date ) {
                        // 这个月就是退房的月数 那么这个月的人日就是到这一天
                        // 判断实际退房和退房约定时间的大小，取小的那个
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
                // 最终人日 = 人日 * 合同入住的人数
                $contract_list[$key]['person_day'] = $person_day = $person_day * $ht_person;

                // 手续费
                if ( C("schedule_type_zf") == $schedule_type || C("schedule_type_hf") == $schedule_type ) {
                    //如果是换房或者转房，要收取手续费
                    $contract_list[$key]['handling_fee'] = C('handling_percent')*$val['rent'];
                }
                // 计算公共区域的水电气
                $contract_list[$key]['water_fee'] = ceil(($public_water_fee / $sum_person_day * $person_day)*100)/100;
                $contract_list[$key]['energy_fee'] = ceil(($public_energy_fee / $sum_person_day * $person_day)*100)/100;
                $contract_list[$key]['gas_fee'] = ceil(($public_gas_fee / $sum_person_day * $person_day)*100)/100;
                
                if (!empty($public_gas_fee) && $public_gas_fee != 0) {
                    // 燃气垃圾费，一般没有气费的同时也没有燃气垃圾费
                    $contract_list[$key]['rubbish_fee'] = ceil(($rubbish_fee / $person_count) * 100)/100;
                } else {
                    $contract_list[$key]['rubbish_fee'] = 0;
                }

                $contract_list[$key]['total_fee'] = $contract_list[$key]['room_energy_fee']+$contract_list[$key]['energy_fee']+$contract_list[$key]['water_fee']+$contract_list[$key]['gas_fee']+$contract_list[$key]['rubbish_fee']+$contract_list[$key]['wx_fee']+$contract_list[$key]['handling_fee'];
            }

            $this->assign("total_energy_fee",$total_energy_fee);
            $this->assign("room_total_energy_fee",$room_total_energy_fee);
            $this->assign("public_energy_fee",$public_energy_fee);
            $this->assign("public_water_fee",$public_water_fee);
            $this->assign("public_energy_fee",$public_energy_fee);
            $this->assign("public_gas_fee",$public_gas_fee);
            $this->assign("add_water",$add_water);
            $this->assign("add_energy",$add_energy);
            $this->assign("add_gas",$add_gas);
            $this->assign("water_unit",$water_unit);
            $this->assign("gas_unit",$gas_unit);
            $this->assign("energy_stair",$area_info['energy_stair']);
            $this->assign("ammeter_house",$ammeter_house);
            $this->assign("sum_person_day",$sum_person_day);
            $this->assign("house_id",$house_id);
            $this->assign("house_code",$house_code);
            $this->assign("schedule_type_name",$schedule_type_name);
            $this->assign("schedule_info",$schedule_info);
            $this->assign("person_day_area_date",date("Y-m-01",strtotime($schedule_info['appoint_time']))."~".date("Y-m-d",strtotime($schedule_info['appoint_time'])));

            $this->assign("contract_list",$contract_list);
            $this->display("dispose-bill");
        }
    }

    /**
    * [old][修改待办信息]
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
    public function checkoutPay(){
        $schedule_id = I("schedule_id");
        $DSchedule = D("schedule");
        $DPay = D('pay');
        $DContract = D("contract");
        $DChargeBill = D("charge_bill");
        $schedule_info = $DSchedule->getScheduleInfo($schedule_id);
        // 获取未支付的账单
        $input = [
            'account_id' => $schedule_info['account_id'],
            'room_id' => $schedule_info['room_id'],
            'pay_status' => 0,
        ];
        $notPaylist = $DPay->getNotPayList($input);
        // 欠费总金额
        $sumTotalFee = 0;
        foreach ($notPaylist as $key=>$val) {
            $billDetail = $DPay->getBillDetail($val);
            if (is_null($billDetail['success'])) {
                // 合并详情
                $notPaylist[$key] = array_merge($val, $billDetail);
                $sumTotalFee += $val['price'];
            }
        }
        // 获取押金
        $deposit = $DContract->getDeposit($schedule_info['account_id'],$schedule_info['room_id']);
        // 剩余押金
        $residueDeposit = $deposit - $sumTotalFee;
        // 生成一个欠款
        if ( $residue_deposit < 0 ) {
            //当剩余押金小于0则需要支付
            $this->assign("is_error",1);
            /*$this->error("剩余押金少于0，警告",U("Admin/Task/index"));*/
        }
        $this->assign("contract",$contract);
        $this->assign("schedule_id",$schedule_id);
        $this->assign("residue_deposit",$residueDeposit);
        $this->assign("deposit",$deposit);
        $this->assign("sum_total_fee",$sumTotalFee);
        $this->assign("notpaylist",$notPaylist);
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