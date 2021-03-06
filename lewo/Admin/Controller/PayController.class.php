<?php
namespace Admin\Controller;
use Think\Controller;
class PayController extends Controller {
    public function __construct(){
        parent::__construct();
        if ( empty($_SESSION['username']) && ACTION_NAME != 'register') {
            header("Location:".U("Admin/Index/login"));
            die();
        }
    }
    /**
    * [账单列表]
    * @param is_download 是否下载为execl文件
    **/
    public function all_bill(){
        //搜索条件
        if ( empty($is_download) ) {//避免赋予一个下载链接给返回页面
            $_SESSION['P_REFERER'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
        }

        $DRoom = D("room");
        $DHouses = D("houses");
        $DAccount = D("account");
        $DArea = D("area");
        $DContract = D("contract");
        $is_download = I('is_download');
        $now_year = intval(date('Y',time()));
        $year_list = array(); //年份
        for( $i=$now_year; $i>=2015; $i--) {
            $year_list[$i] = $i;
        }

        $month_list = array(12=>12,11=>11,10=>10,9=>9,8=>8,7=>7,6=>6,5=>5,4=>4,3=>3,2=>2,1=>1);//月份

        $search = I('search');
        $is_has_flag = strpos($search, '-');
        if ( $is_has_flag && !empty($search)) {
            $search_arr = explode('-',$search);
            if ( !is_null($search_arr['0']) ) {
                $where['houses.building'] = $search_arr['0'];
            }
            if ( !is_null($search_arr['1']) ) {
                $where['houses.floor'] = $search_arr['1'];
            }
            if ( !is_null($search_arr['2']) ) {
                $where['houses.door_no'] = $search_arr['2'];
            }
        }
        $this->assign('search',$search);

        $room_code = I("room_code");
        if ( !empty($room_code) ) {
            $where['room.room_code'] = array('LIKE','%'.$room_code.'%');
            $this->assign("room_code",$room_code);
        }
        $account_key = I("account_key");
        if ( !empty($account_key) ) {
            $where['_string'] = "account.realname LIKE '%".$account_key."%' OR account.mobile LIKE '%".$account_key."%'";
            $this->assign("account_key",$account_key);
        }
        $area_id = I("area_id");
        if ( !empty($area_id) ) {
            $where['houses.area_id'] = $area_id;
            $this->assign("area_id",$area_id);
        }
        $city_id = I("city_id");
        if ( !empty($city_id) ) {
            $where['area.city_id'] = $city_id;
            $this->assign("city_id",$city_id);
        }
        $input_month = I("input_month");
        if ( !empty($input_month) ) {
            $where['pay.input_month'] = $input_month;
            $this->assign("input_month",$input_month);
        }
        $input_year = I("input_year");
        if ( !empty($input_year) ) {
            $where['pay.input_year'] = $input_year;
            $this->assign("input_year",$input_year);
        }
        $pay_type = I("pay_type");
        if ( !empty($pay_type)) {
            $where['pay.pay_type'] = $pay_type;
            $this->assign("pay_type",$pay_type);
        }
        $contract_status = I("contract_status");
        if ( !empty($contract_status)) {
            $where['lewo_contract.contract_status'] = $contract_status;
            $this->assign("contract_status",$contract_status);
        }
        $bill_type = I("bill_type");
        if (!is_array($bill_type) && !empty($bill_type[0]) ) {
            $bill_type = explode(',', $bill_type);
        }
        if ( !empty($bill_type) && is_array($bill_type) ) {

            $bill_type_str = '';
            foreach ($bill_type as $key => $val) {
                $bill_type_str .= $val.',';
            }
            $bill_type_str = substr($bill_type_str,0,-1);
            $where['pay.bill_type'] = array('IN',$bill_type_str);
            $this->assign("bill_type",$bill_type);
        }

        $is_send = I("is_send");
        if ( !empty($is_send) || $is_send === '0') {
            $where['pay.is_send'] = $is_send;
            $this->assign("is_send",$is_send);
        }
        $pay_status = I("pay_status");
        if ( !empty($pay_status) || $pay_status === '0') {
            $where['pay.pay_status'] = $pay_status;
            $this->assign("pay_status",$pay_status);
        }
        $search_time = I("search_time");
        $start_time = I("start_time");
        $end_time = I("end_time");    
        if ( !empty($search_time) && !empty($start_time) && !empty($end_time) ) {
            $end_time = date('Y-m-d',strtotime($end_time."+1 day"));
            switch ($search_time) {
                case 'payDate':
                    $where['pay.pay_time'] = array("BETWEEN",array($start_time,$end_time));
                    break;
                case 'htStartDate':
                    $where['lewo_contract.start_time'] = array("BETWEEN",array($start_time,$end_time));
                    break;
                case 'htEndDate':
                    $where['lewo_contract.end_time'] = array("BETWEEN",array($start_time,$end_time));
                    break;
                case 'rentDate':
                    $where['lewo_contract.rent_date'] = array("BETWEEN",array($start_time,$end_time));
                    break;
                case 'zcht':
                    //这段时间内合同正常的列表
                    $where['_string'] = '\''.$end_time.'\' <= lewo_contract.end_time ' ;
                    break;
            }

            $this->assign("search_time",$search_time);
            $this->assign("start_time",$start_time);
            $this->assign("end_time",I("end_time"));
        }

        $order = "pay.id desc,pay.create_time desc";
        $sort_type = I("sort_type");
        if ( !empty($sort_type) ) {
            $sort = I("sort")==1? "asc":"desc";
            switch ($sort_type) {
                case 'payTime':
                    $order = "pay.pay_time ".$sort;
                    break;
                case 'payType':
                    $order = "pay.pay_type ".$sort;
                    break;
                case 'startDate':
                    $order = "lewo_contract.start_time ".$sort;
                    break;
                case 'endDate':
                    $order = "lewo_contract.end_time ".$sort;
                    break;
                case 'rentDate':
                    $order = "lewo_contract.rent_date ".$sort;
                    break;
                case 'tbq':
                    $order = "pay.last_date ".$sort;
                    break;
                case 'inputMonth':
                    $order = "pay.input_year ".$sort.", pay.input_month ".$sort;
                    break;
                
                default:
                    # code...
                    break;
            }
            $this->assign("sort_type",$sort_type);
            $this->assign("sort",$sort);
        }
        $page_count = I("page_count");
        if ( !empty($page_count) &&  $page_count > 0 ) {
            $page_count = I("page_count");
            $this->assign("page_count",$page_count);
        }else {
            $page_count = 100;
        }
        $MContract  = M("contract");// 实例化User对象
        $MChargeBill= M("charge_bill");// 实例化User对象
        $MPay       = M("pay");// 实例化User对象
        $DContract  = D("contract");
        $is_show = I('is_show');
        if ( $is_show != '' ) {
            $where['pay.is_show'] = $is_show; //显示
        } else {
            $where['pay.is_show'] = '1'; //显示
        }

        $count      = $MPay
                    ->alias('pay')
                    ->join('(select MAX(id) AS ht_id,account_id,room_id from lewo_contract WHERE is_delete=0  GROUP BY account_id,room_id ) AS contract ON pay.account_id=contract.account_id AND pay.room_id=contract.room_id ','left')
                    ->join('lewo_contract ON lewo_contract.id = contract.ht_id', 'left')
                    ->join('lewo_charge_bill charge_bill ON pay.pro_id=charge_bill.pro_id','left')
                    ->join('lewo_account account ON pay.account_id=account.id','left')
                    ->join('lewo_room room ON pay.room_id=room.id','left')
                    ->join('lewo_houses houses ON room.house_code=houses.house_code','left')
                    ->join('lewo_area area ON houses.area_id=area.id','left')
                    ->where($where)
                    ->count(1);

        $Page       = new \Think\Page($count,$page_count);// 实例化分页类

        //针对select multple属性的元素
        if ( !empty($bill_type) && is_array($bill_type) ) {
            $bill_type = implode(',', $bill_type);
            $Page->parameter['bill_type'] = $bill_type;
        }  

        $show       = $Page->show();// 分页显示输出

        $field = [
            // contract
            'lewo_contract.id', 'lewo_contract.account_id',
            'lewo_contract.room_id', 
            'lewo_contract.create_time', 'lewo_contract.actual_end_time',
            'lewo_contract.start_time', 'lewo_contract.end_time',
            'lewo_contract.rent_date', 'lewo_contract.period',
            'lewo_contract.deposit', 'lewo_contract.actual_deposit',
            'lewo_contract.rent'=>'ht_rent', 'lewo_contract.actual_rent',
            'lewo_contract.fee'=>'ht_fee', 'lewo_contract.wg_fee'=>'ht_wgfee',
            'lewo_contract.contract_status',
            // charge_bill
            'charge_bill.room_id', 'charge_bill.house_id',
            'charge_bill.house_code', 'charge_bill.account_id',
            'charge_bill.water_fee', 'charge_bill.public_energy_fee',
            'charge_bill.energy_fee', 'charge_bill.gas_fee',
            'charge_bill.rubbish_fee', 'charge_bill.person_day',
            'charge_bill.total_person_day', 'charge_bill.total_energy',
            'charge_bill.total_water', 'charge_bill.total_gas',
            'charge_bill.wgfee_unit', 'charge_bill.room_energy_fee',
            'charge_bill.rent_fee', 'charge_bill.rent_des',
            'charge_bill.service_fee', 'charge_bill.wx_fee',
            'charge_bill.wx_des', 'charge_bill.type',
            'charge_bill.handling_fee',
            'charge_bill.rent_date_old', 'charge_bill.rent_date_to',
            // pay
            'pay.room_id', 'pay.price',
            'pay.bill_type', 'pay.pay_money',
            'pay.pay_status', 'pay.pay_time',
            'pay.account_id', 'pay.pro_id',
            'pay.pay_type', 'pay.is_show',
            'pay.input_month', 'pay.input_year',
            'pay.should_date', 'pay.last_date',
            'pay.is_send', 'pay.favorable',
            'pay.favorable_des', 'pay.bill_des',
            // account
            'account.mobile', 'account.realname',
            // room
            'room.room_code', 'room.bed_code',
            // houses
            'houses.id AS house_id', 'houses.area_id',
            'houses.building', 'houses.floor',
            'houses.door_no', 'houses.steward_id',
            // area
            'area.city_id', 'area.area_name',
            // admin_user
            'admin_user.username', 'admin_user.nickname AS gj_nickname',
            'admin_user.realname AS gj_realname', 'admin_user.admin_type'
        ];

        $list       = $MPay
                    ->alias('pay')
                    ->field($field)
                    ->join('(select MAX(id) AS ht_id,account_id,room_id from lewo_contract WHERE is_delete=0  GROUP BY account_id,room_id ) AS contract ON pay.account_id=contract.account_id AND pay.room_id=contract.room_id ','left')
                    ->join('lewo_contract ON lewo_contract.id = contract.ht_id', 'left')
                    ->join('lewo_charge_bill charge_bill ON pay.pro_id=charge_bill.pro_id','left')
                    ->join('lewo_account account ON pay.account_id=account.id','left')
                    ->join('lewo_room room ON pay.room_id=room.id','left')
                    ->join('lewo_houses houses ON room.house_code=houses.house_code','left')
                    ->join('lewo_area area ON houses.area_id=area.id','left')
                    ->join('lewo_admin_user admin_user ON houses.steward_id=admin_user.id','left')
                    ->where($where)
                    ->order($order)
                    ->limit($Page->firstRow.','.$Page->listRows)
                    ->select();

        $DAccount   = D("account");
        $DRoom      = D("room");
        $DHouses    = D("houses");
        $DArea      = D("area");
        $total_price = 0;
        $total_pay_money = 0;
        $total_sdq = 0;
        $total_deposit = 0;
        $total_rent = 0;
        $total_service_fee = 0;
        $total_wg_fee = 0;
        $total_wx_fee = 0;
        $total_favorable_fee = 0;
        foreach ( $list AS $key=>$val ) {
            $rent_type = explode("_", $val['rent_type']);
            $list[$key]['rent_type_name'] = "押".$rent_type['0']."付".$rent_type['1'];
            $list[$key]['contract_status_name'] = C("contract_status_arr")[$val['contract_status']];
            $list[$key]['pay_type_name'] = C("pay_type")[$val['pay_type']];
            $list[$key]['SDQtotal'] = 0;
            $list[$key]['SDQtotal'] = $val['water_fee'] + $val['gas_fee'] + $val['energy_fee'] + $val['room_energy_fee'] + $val['rubbish_fee'];
            $list[$key]['SDQtotal_des'] = '个人电费:'.$val['room_energy_fee'].' 公摊电费:'.$val['energy_fee'].' 水费:'.$val['water_fee'].' 气费:'.$val['gas_fee'] . ' 垃圾处置费'.$val['rubbish_fee'];
            
            $list[$key]['bill_type_name'] = C('bill_type')[$val['bill_type']];

            switch ($val['type']) {
                case 2:
                    $list[$key]['bill_type_name'] .= "退房";
                    break;
                case 3:
                    $list[$key]['bill_type_name'] .= "转房";
                    break;
                case 4:
                    $list[$key]['bill_type_name'] .= "换房";
                    break;
            }
            $rent = 0;
            $service_fee = 0;
            $wg_fee = 0;
            $ht_deposit = 0;
            switch ($val['bill_type']) {
                case 2:
                // 2/7 合同
                    $rent = $val['ht_rent'];
                    $service_fee = $val['ht_fee'];
                    $wg_fee = $val['ht_wgfee'];
                    $ht_deposit = $val['deposit'];
                    // 合同实收押金
                    $list[$key]['ht_actual_deposit'] = $val['actual_deposit'];
                    // 合同实收房租
                    $list[$key]['ht_actual_rent'] = $val['actual_rent'];
                    //合同账单的房租到期日计算
                    $list[$key]['rent_date_to'] = date('Y-m-d',strtotime($val['start_time'].' +'.$val['period'].' month -1 day'));
                    break;
                case 3:
                // 3/8 日常
                    $rent = $val['rent_fee'];
                    $service_fee = $val['service_fee'];
                    $wg_fee = $val['wgfee_unit'];
                    break;
            }
            $list[$key]['rent']         = $rent;
            $list[$key]['service_fee']  = $service_fee;
            $list[$key]['wg_fee']       = $wg_fee;
            $list[$key]['ht_deposit']   = $ht_deposit;
            //最迟缴费倒计时
            if ( $val['pay_status'] != 1 ) {
                $last_date          =strtotime($val['last_date']);
                $enddate            =strtotime(date('Y-m-d',time()));
                $count_down_days    =round(($last_date-$enddate)/86400);
                $list[$key]['count_down_days'] = $count_down_days;
            }
            
            $total_price        += $val['price'];
            $total_pay_money    += $val['pay_money'];
            $total_sdq          += $list[$key]['SDQtotal'];
            $total_deposit      += $ht_deposit;
            $total_rent         += $rent;
            $total_service_fee  += $service_fee;
            $total_wg_fee       += $val['wgfee_unit'];
            $total_wx_fee       += $val['wx_fee'];
            $total_favorable_fee+= $val['favorable'];
        }

        //下载execl
        if ( !empty($is_download) ) {
            header("Content-type: application/vnd.ms-excel; charset=utf-8");
            $file_time = date('Ymd',time());
            header("Content-Disposition: attachment; filename=".$file_time."乐窝账单.xls");
            $data = null;
            $data .= "类型\t房间编号\t床位编号\t小区楼层\t管家\t姓名\t电话\t合同开始\t合同结束\t房租到期\t最迟缴费\t倒计时\t缴费周期\t批次\t押金\t实收押金\t房租\t实收房租\t服务费\t水电气\t物管\t欠费\t欠费描述\t优惠\t应付\t实收\t支付时间\t支付方式\n";

            foreach ($list AS $row)
            {
                $data .= "$row[bill_type_name]\t$row[room_code]\t$row[bed_code]\t$row[area_name]($row[building]-$row[floor]-$row[door_no])\t$row[gj_nickname]$row[gj_realname]\t$row[realname]\t$row[mobile]\t$row[start_time]\t$row[end_time]\t$row[rent_date_to]\t$row[last_date]\t$row[count_down_days]\t$row[period]\t$row[input_year]年$row[input_month]月\t$row[ht_deposit]\t$row[ht_actual_deposit]\t$row[rent]\t$row[ht_actual_rent]\t$row[service_fee]\t$row[SDQtotal]\t$row[wgfee_unit]\t$row[wx_fee]\t$row[wx_des]\t$row[favorable]\t$row[price]\t$row[pay_money]\t$row[pay_time]\t$row[pay_type_name]\n";
            }
            echo iconv("UTF-8","GB2312//IGNORE",$data);
            exit;
        }

        /*$contract_list = $DContract->getContractList();*/
        /*$this->assign("contract_list",$contract_list);*/
        $this->assign('contract_status_arr',C('contract_status_arr'));
        $this->assign('contract_list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('count',$count);
        $this->assign('total_price',$total_price);
        $this->assign('total_pay_money',$total_pay_money);
        $this->assign('total_sdq',$total_sdq);
        $this->assign('total_deposit',$total_deposit);
        $this->assign('total_rent',$total_rent);
        $this->assign('total_service_fee',$total_service_fee);
        $this->assign('total_wg_fee',$total_wg_fee);
        $this->assign('total_wx_fee',$total_wx_fee);
        $this->assign('year_list',$year_list);
        $this->assign('month_list',$month_list);
        //小区列表
        $DArea = D("area");
        $this->assign('area_list',$DArea->getareaList());
        $this->assign('pay_type_list',C("pay_type"));
        $this->assign('city_list',C("city_id"));
        $this->assign('bill_type_list',C("bill_type"));
        $this->display("all_bill");
    }



    /**
    * [修改账单支付状态]
    **/
    public function edit_pay(){
    	if ( !empty($_POST) ) {
            $MContract  = M("contract");
            $MPay       = M('pay');
            $DPay       = D('pay');
    		$pro_id     = I('pro_id');
            $pay_money  = I("pay_money");
            $pay_status = I('pay_status');
            $pay_type   = I('pay_type');
            $last_date   = I('last_date');
            $should_date   = I('should_date');
            $bill_des   = I('bill_des');
            if ( $pay_status == 1 && empty($pay_type) ) {
                $this->error('请选择支付方式');
            }
            if ( !empty($pay_type) && $pay_status == 0 ) {
                $this->error('未支付时不能修改支付方式');
            }
            $pay_info   = $MPay->where(array("pro_id"=>$pro_id))->find();
            $bill_type  = $pay_info['bill_type'];
            $account_id = $pay_info["account_id"];
            $room_id    = $pay_info["room_id"];
            $not_create_bill_id = array(5,8,10);//5押金抵扣，8退房的支付方式不生成拖欠账单 10违约退房
            $is_in_not_create = in_array($pay_type, $not_create_bill_id);

            //未支付和未付完 则生成欠款
            if ( $pay_money > $pay_info['price'] ) $this->error('输入的金额大于应付金额');
            if ( $pay_money != $pay_info['price'] && $pay_status == 1 && !$is_in_not_create){           
                switch ($bill_type) {
                    case 2:
                    case 7:
                        if ($pay_type == 9) {//如果是错误扣除 那么新账单的类型是账单更改
                            $due_bill_type = C('bill_type_zdgg');
                        } else {
                            $due_bill_type = 7;
                        }
                        break;
                    case 3:
                    case 8:
                        if ($pay_type == 9) {//如果是错误扣除 那么新账单的类型是账单更改
                            $due_bill_type = C('bill_type_zdgg');
                        } else {
                            $due_bill_type = 8;
                        }
                        break;
                    default:
                        $due_bill_type = 9;
                        break;
                }

                $due_price = $pay_info['price'] - $pay_money;
                $param = array(
                            'account_id'=>$account_id,
                            'room_id'=>$room_id,
                            'bill_type'=>$due_bill_type,
                            'input_year'=>$pay_info['input_year'],
                            'input_month'=>$pay_info['input_month'],
                            'should_date'=>$pay_info['should_date'],
                            'last_date'=>$pay_info['last_date'],
                            'price'=>$due_price,
                            'is_send'=>1
                            );
                $result = $DPay->create_bill($param);
                if ( !$result ) $this->error('欠款账单生成失败!');
            }

            switch ($bill_type) {
                case 2:
                // 2/7 合同
                    $DRoom = D("room");
                    $DRoom->setRoomStatus($room_id,2);
                    $DRoom->setRoomPerson($room_id,$account_id);
                    //修改合同正常
                    $MContract->where(array("pro_id"=>$pro_id))->save(array("contract_status"=>1));
                    break;
                case 3:
                // 3/8 日常
                    //修改合同信息
                    $charge_info = M("charge_bill")->where(array("pro_id"=>$pro_id))->find();
                    $rent_date = $charge_info['rent_date_to']; //房租到期日
                    $MContract->where(array('account_id'=>$account_id,'room_id'=>$room_id,'contract_status'=>1))->save(array("rent_date"=>$rent_date));
                    break;
            }
            $extra_modify_log  = I('modify_log');
            $save = $_POST;

            $modify_log = $this->modify_log($pro_id,$save,$extra_modify_log);

            $data['modify_log'] = $modify_log; 
            $data['pay_type'] = $pay_type;
            $data['pay_time'] = I("pay_time");
            $data['pay_money']  = $pay_money;
            $data['pay_status'] = $pay_status;
            $data['last_date'] = $last_date;
            $data['should_date'] = $should_date;
            $data['bill_des'] = $bill_des;

            $result = M("pay")->where(array("pro_id"=>$pro_id))->save($data);

    		if ( !empty($result) ) {
    			/*$this->success("修改成功.".$success_msg,U('Admin/Pay/all_bill'),10);*/
                redirect($_SESSION['P_REFERER']);
    		} else {
    			$this->error("修改失败",U('Admin/Pay/all_bill'));
    		}
    		
    	} else {
    		$pro_id = I("pro_id");
            $DChargeBill = D("charge_bill");
            $pay_info = $DChargeBill->getPayInfo($pro_id);
            $this->assign("pay_info",$pay_info);
            $this->assign("time",date("Y-m-d",time()));
    		$this->assign("pro_id",$pro_id);
    		$this->assign("pay_type",C("pay_type"));
    		$this->display("edit-pay");
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
        $pay_info   = $MPay->where($where)->find();
        $modify_log  = $pay_info['modify_log'];
        $modify_log .= '<br/>修改人:'.$_SESSION['admin_type_name'].$_SESSION['username'].' 时间:'.date('Y-m-d H:i:s').' <br/>';

        if ( $save['pay_status'] != $pay_info['pay_status'] ) {
            $pay_status_arr = array(0=>'未支付',1=>'已支付',2=>'管家代收');
            $modify_log .= '支付状态：'.$pay_status_arr[$pay_info['pay_status']].'--> <b style="color:green">'.$pay_status_arr[$save['pay_status']].'</b>;<br/>';
        }

        if ( $save['bill_des'] != $pay_info['bill_des'] ) {
            $modify_log .= '账单描述：'.$pay_info['bill_des'].'--> <b style="color:green">'.$save['bill_des'].'</b>;<br/>';
        }

        if ( $save['pay_type'] != $pay_info['pay_type'] ) {
            $pay_type_arr = C('pay_type');
            $modify_log .= '支付方式：'.$pay_type_arr[$pay_info['pay_type']].'--> <b style="color:green">'.$pay_type_arr[$save['pay_type']].'</b>;<br/>';
        }

        if ( $save['pay_money'] != $pay_info['pay_money'] ) {
            $modify_log .= '支付金额：'.$pay_info['pay_money'].'--> <b style="color:green">'.$save['pay_money'].'</b>;<br/>';
        }

        if ( $save['pay_time'] != $pay_info['pay_time'] ) {
            $modify_log .= '支付时间：'.$pay_info['pay_time'].'--> <b style="color:green">'.$save['pay_time'].'</b>;<br/>';
        }

        if ( $save['last_date'] != $pay_info['last_date'] ) {
            $modify_log .= '最迟缴费时间：'.$pay_info['last_date'].'--> <b style="color:green">'.$save['last_date'].'</b>;<br/>';
        }

        if ( $save['should_date'] != $pay_info['should_date'] ) {
            $modify_log .= '应缴费时间：'.$pay_info['should_date'].'--> <b style="color:green">'.$save['should_date'].'</b>;<br/>';
        }

        if ( !empty($extra_modify_log) ) {
            $modify_log .= '备注：'.$extra_modify_log.'</b>;<br/>';
        }

        return $modify_log;
    }
    public function check_log(){
        $pro_id = I("pro_id");
        $DChargeBill = D("charge_bill");
        $pay_info = $DChargeBill->getPayInfo($pro_id);
        $this->assign("pay_info",$pay_info);
        $this->assign("time",date("Y-m-d",time()));
        $this->assign("pro_id",$pro_id);
        $this->assign("pay_type",C("pay_type"));
        $this->display("check-log");
    }

    public function delete_pay(){
        $MPay = M('pay');
        $pro_id = I('pro_id');
        $result = $MPay->where(array('pro_id'=>$pro_id))->save(array('is_show'=>0));
        if ( $result ) {
            $this->success('账单删除成功!');
        } else {
            $this->error('账单删除失败!');
        }
    }

    public function clear_pay(){
        $MPay       = M('pay');
        $pro_id     = I('pro_id');
        $bill_type  = $MPay->where(array('pro_id'=>$pro_id))->getField('bill_type');
        switch ($bill_type) {
            case 2:
                M('contract')->where(array('pro_id'=>$pro_id))->delete();
                break;
            case 3:
                M('charge_bill')->where(array('pro_id'=>$pro_id))->delete();
                break;
        }
        $result     = $MPay->where(array('pro_id'=>$pro_id))->delete();
        if ( $result ) {
            $this->success('账单删除成功!');
        } else {
            $this->error('账单删除失败!');
        }
    }

    public function back_pay(){
        $MPay = M('pay');
        $pro_id = I('pro_id');
        $result = $MPay->where(array('pro_id'=>$pro_id))->save(array('is_show'=>1));
        if ( $result ) {
            $this->success('账单恢复成功!');
        } else {
            $this->error('账单恢复失败!');
        }
    }

    /**
    * [修改合同账单]
    **/
    public function edit_contract_pay(){
        if ( !empty($_POST) ) {
            $pro_id         = I('pro_id');
            $MContract      = M("contract");
            $account_id     = $MContract->where(array("pro_id"=>$pro_id))->getField("account_id");
            $room_id        = $MContract->where(array("pro_id"=>$pro_id))->getField("room_id");

            $data['pay_type']   = I("pay_type");
            $data['pay_date']   = I("pay_time");
            $data['pay_log']    = I("pay_log");
            $pdata['pay_status'] = 1;
            $result = $MContract->where(array("id"=>$id))->save($data);
            $result2 = M('pay')->where()->save(array());
            if ( $result ) {
                $DRoom = D("room");
                $DRoom->setRoomStatus($room_id,2);
                $DRoom->setRoomPerson($room_id,$account_id);
                $this->success("修改成功",U("Admin/Pay/contract_bill"));
            } else {
                $this->error("修改失败",U("Admin/Pay/contract_bill"));
            }
        } else {
            $pro_id = I("pro_id");
            $DContract = D("contract");
            $pay_info = $DContract->getPayInfo($pro_id);

            $this->assign("pay_info",$pay_info);
            $this->assign("id",$id);
            $this->assign("pay_type",C("pay_type"));
            $this->display("edit-contract-pay");
        }
    } 
    
    public function delete_bill(){
        $id = I("id");
        $result = M("charge_bill")->where(array("id"=>$id))->save(array("is_delete"=>1));
        if ( $result ) {
            $this->success("删除成功!",U("Admin/Pay/daily_bill"));
        } else {
            $this->error("删除失败!",U("Admin/Pay/daily_bill"));
        }
    }



    public function check_pay_log(){
        $id = I("id");
        $DChargeBill = D("charge_bill");
        $pay_info = $DChargeBill->getPayInfo($id);
        $this->assign("pay_info",$pay_info);
        $this->assign("id",$id);
        $this->assign("pay_type",C("pay_type"));
        $this->display("check_pay_log");
    }

    public function update_send_bill(){
        $id = I("pro_id");
        $result = M("charge_bill")->where(array("pro_id"=>$id))->save(array("is_send"=>1));
        $result2 = M("pay")->where(array("pro_id"=>$id))->save(array("is_send"=>1));
        if ( !empty($result) ) {
            die(json_encode(array("info"=>"修改成功")));
        } else {
            die(json_encode(array("info"=>"修改失败")));
        }
        
    }
}