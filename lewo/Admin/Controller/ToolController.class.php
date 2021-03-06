<?php
namespace Admin\Controller;
use Think\Controller;
class ToolController extends Controller {
    public function __construct(){
        parent::__construct();
        if ( empty($_SESSION['username']) && ACTION_NAME != 'register') {
            header("Location:".U("Admin/Index/login"));
            die();
        }
        ini_set('max_execution_time', '9999');
    }
    public function index(){
        $this->display("Common/header");
        $this->display("Common/nav");
        $this->display("index");
        $this->display("Common/footer");
    }

    private function _deleteDir($R){
        //打开一个目录句柄
        $handle = opendir($R);
        //读取目录,直到没有目录为止
        while(($item = readdir($handle)) !== false){
            //跳过. ..两个特殊目录
            if($item != '.' and $item != '..'){
                //如果遍历到的是目录
                if(is_dir($R.'/'.$item)){
                    //继续向目录里面遍历
                    $this->_deleteDir($R.'/'.$item);
                }else{
                    //如果不是目录，删除该文件
                    if(!unlink($R.'/'.$item))
                        die('error!');
                }
            }
        }
        //关闭目录
        closedir( $handle );
        //删除空的目录
        return rmdir($R); 
    }

    //清除缓存--删除runtime文件夹
    public function delRun () {
        //获取当前的缓存目录
        $R = RUNTIME_PATH;
        //执行删除函数
        if($this->_deleteDir($R))
            //$this->error('删除成功！');
        die("清除成功!");
    }

    /**
    * [针对之前的日常账单和合同账单没有在lewo_pay表中生成关联数据，此功能将未生成的数据在lewo_pay中添加数据以致能够关联]
    **/
    /*public function create_pay_data(){
        $MChargebill = M('charge_bill');
        $MContract = M('contract');
        $MPay = M('pay');
        $charge_bill_list = $MChargebill->select();
        $contract_list = $MContract->select();

        foreach($charge_bill_list AS $key=>$val){
            $data = array();
            $data['pro_id'] = $val['order_no'];
            $data['room_id'] = $val['room_id'];
            $data['price'] = $val['total_fee'];
            $data['bill_type'] = 3;
            $data['pay_type'] = $val['pay_type'];
            $data['pay_status'] = $val['pay_status'];
            if ( $data['pay_status'] == 1 ) {
                $data['pay_money'] = $data['price'];
            }
            $data['pay_time'] = !empty($val['pay_time'])?$val['pay_time']:0;
            $data['create_time'] = $val['create_time'];
            $data['account_id'] = $val['account_id'];
            $data['input_month'] = $val['input_month'];
            $data['input_year'] = $val['input_year'];
            $data['should_date'] = $val['should_pay_date'];
            $data['last_date'] = $val['late_pay_date'];
            $data['is_show'] = 1;
            $data['modify_log'] = 'create_pay_data所创建的';
            $MPay->add($data);
        }
        foreach($contract_list AS $key=>$val ){
            $data = array();
            $data['pro_id'] = $val['order_no'];
            $data['room_id'] = $val['room_id'];
            $data['price'] = $val['pay_total'];
            $data['bill_type'] = 2;
            $data['pay_status'] = $val['pay_status'];
            if ( $data['pay_status'] == 1 ) {
                $data['pay_money'] = $data['price'];
            }
            $data['pay_time'] = !empty($val['pay_date'])?$val['pay_date']:0;
            $data['create_time'] = !empty($val['create_time'])?$val['create_time']:0;
            $data['account_id'] = $val['account_id'];
            $data['pay_type'] = $val['pay_type'];
            $data['input_month'] = date('m',strtotime($val['create_time']));
            $data['input_year'] = date('Y',strtotime($val['create_time']));
            $data['should_date'] = $data['create_time'];
            $data['last_date'] = $data['create_time'];
            $data['is_show'] = 1;
            $data['modify_log'] = 'create_pay_data所创建的';
            $MPay->add($data);
        }
        $this->success("处理成功!",U('Admin/Tool/index'));
    }*/

    /**
    * [php版本]
    **/
    public function php_v(){
        phpinfo();
    }

    /**
    * 搜索每间房屋，判断是否已经有账单了，有则修改房屋为已生成
    **/
/*    public function update_houses_is_create_bill(){
        $MHouse = M('houses');
        $MPay = M('pay');

        $house_list = $MHouse->field('id')->select();

        foreach ($house_list as $key => $val) {
            //2016 10 9
            $where = array();
            $where['house_id'] = $val['id'];
            $where['input_year'] = 2016;
            $where['input_month'] = 10;

        }
    }*/

    public function check_double_pro_id(){
        $MPay = M('pay');
        $MPay->startTrans();
        $flag = true;
        $pro_arr = $MPay->field('pro_id,create_time')->order('create_time desc')->select();
        $pro_select_arr = array();
        $i = 0;
        foreach ($pro_arr as $key => $val) {
            $result = in_array($val['pro_id'], $pro_select_arr);
            if ( !$result ) {
                $is_double_arr = $MPay->field('id,pro_id,bill_type')->where(array('pro_id'=>$val['pro_id']))->select();
                if ( count($is_double_arr) > 1 ) {
                    echo $val['create_time'].'pro_id:'.$val['pro_id'].'有'.count($is_double_arr).'个<br/>';
                    $i++;
                    foreach ($is_double_arr as $pk => $pv) {
                        if ( $pv['bill_type'] != 2 && $pv['bill_type'] != 3 ) {
                            $pro_id = getOrderNo();
                            $result  = $MPay->where(array('id'=>$pv['id']))->save(array('pro_id'=>$pro_id));
                            if ( !$result ) {
                                $flag = false;
                            }
                        }
                    }
                }
            }
            $pro_select_arr[] = $val['pro_id'];
        }
        if ( $flag ) {
            $MPay->commit();
        } else {
            $MPay->rollback();
        }
        echo $i;

    }

    /**
    * [将order_no 移植到 pro_id]
    **/
    public function update_pro_id(){
        $MContract = M('contract');
        $MChargebill = M('charge_bill');
        $contract_list = $MContract->select();
        $charge_bill_list = $MChargebill->select();
        foreach ($contract_list as $key => $val) {
            if ( $val['order_no'] == '' ) {
                continue;
            }
            $MContract->where(array('id'=>$val['id']))->save(array('pro_id'=>$val['order_no']));
        }
        foreach ($charge_bill_list as $key => $val) {
            if ( $val['order_no'] == '' ) {
                continue;
            }
            $MChargebill->where(array('id'=>$val['id']))->save(array('pro_id'=>$val['order_no']));
        }
        $this->success("处理成功!",U('Admin/Tool/index'));
    }

    /**
    *[遍历房间，获取相应的正常合同，如没有正常合同则是空房]
    **/
    public function check_room_is_in(){
        $MPay = M('pay');
        $MRoom = M('room');
        $MContract = M('contract');
        $room_list = $MRoom->select();

        foreach ($room_list as $key => $val) {
            $save = array();
            $room_id = $val['id'];
            $contract_info = $MContract
                            ->alias('c')
                            ->field('p.room_id,p.account_id,p.bill_type,c.contract_status')
                            ->join('lewo_pay p ON p.pro_id=c.pro_id')
                            ->where(array('p.pay_status'=>1,'p.room_id'=>$room_id,'p.bill_type'=>2,'c.contract_status'=>1))
                            ->order('rent_date desc')
                            ->find();
            if ( !is_null($contract_info) ) {
                $save['account_id'] = $contract_info['account_id'];
                $save['status'] = 2;
                $MRoom->where(array('id'=>$room_id))->save($save);
            } else {
                $save['account_id'] = 0;
                $save['status'] = 0;
                $MRoom->where(array('id'=>$room_id))->save($save);
            }
        }
    }

    /**
    * [遍历日常账单，修改水电气数据]
    **/
    public function update_charge_sdq(){
        header('Content-type:text/html; charset=utf-8');
        $MChargebill = M('charge_bill');
        $MAeemeterRoom = M('ammeter_room');
        $MAeemeterHouse = M('ammeter_house');
        $MChargeHouse = M('charge_house');
        $MHouse = M('houses');
        $bill_list = $MChargebill
                    ->alias('cb')
                    ->field('cb.house_code,cb.start_energy,cb.end_energy,cb.start_water,cb.end_water,cb.start_gas,cb.end_gas,cb.start_room_energy,cb.end_room_energy,p.input_year,p.input_month,p.pro_id,p.room_id,cb.id')
                    ->join('lewo_pay p ON cb.pro_id=p.pro_id')
                    ->select();
        $i = 0;
        foreach ($bill_list as $key => $val) {
           
            $year       = null;
            $month      = null;
            $lastyear   = null;
            $lastmonth  = null;
            $house_id   = null;
            $room_id    = null;
            $year       = $val['input_year'];
            $month      = $val['input_month'];
            //上一个月
            $lastDate   = date("Y-m",strtotime($year."-".$month."- 1 month"));
            $lastyear   = date("Y",strtotime($lastDate));
            $lastmonth  = date("m",strtotime($lastDate));

            $house_id   = $MHouse->where(array('house_code'=>$val['house_code']))->getField('id');
            $room_id    = $val['room_id'];

            $ammeter_house = $MAeemeterHouse->where(array('house_id'=>$house_id,'input_year'=>$year,'input_month'=>$month))->find();
            $last_ammeter_house = $MAeemeterHouse->where(array('house_id'=>$house_id,'input_year'=>$lastyear,'input_month'=>$lastmonth))->find();
            $ammeter_room = $MAeemeterRoom->where(array('room_id'=>$room_id,'input_year'=>$year,'input_month'=>$month))->find();
            $last_ammeter_room = $MAeemeterRoom->where(array('room_id'=>$room_id,'input_year'=>$lastyear,'input_month'=>$lastmonth))->find();
            $save = array();
            $save['end_energy']   = is_null($ammeter_house['total_energy'])?0:$ammeter_house['total_energy'];
            $save['start_energy']     = is_null($last_ammeter_house['total_energy'])?0:$last_ammeter_house['total_energy'];
            $save['end_water']    = is_null($ammeter_house['total_water'])?0:$ammeter_house['total_water'];
            $save['start_water']      = is_null($last_ammeter_house['total_water'])?0:$last_ammeter_house['total_water'];
            $save['end_gas']      = is_null($ammeter_house['total_gas'])?0:$ammeter_house['total_gas'];
            $save['start_gas']        = is_null($last_ammeter_house['total_gas'])?0:$last_ammeter_house['total_gas'];
            $save['end_room_energy']  = is_null($ammeter_room['room_energy'])?0:$ammeter_room['room_energy'];
            $save['start_room_energy']    = is_null($last_ammeter_room['room_energy'])?0:$last_ammeter_room['room_energy'];

            $result = $MChargebill->where(array('id'=>$val['id']))->save($save);
            $save1['is_create'] = 1;
            $save1['end_energy']   = is_null($ammeter_house['total_energy'])?0:$ammeter_house['total_energy'];
            $save1['start_energy']     = is_null($last_ammeter_house['total_energy'])?0:$last_ammeter_house['total_energy'];
            $save1['end_water']    = is_null($ammeter_house['total_water'])?0:$ammeter_house['total_water'];
            $save1['start_water']      = is_null($last_ammeter_house['total_water'])?0:$last_ammeter_house['total_water'];
            $save1['end_gas']      = is_null($ammeter_house['total_gas'])?0:$ammeter_house['total_gas'];
            $save1['start_gas']        = is_null($last_ammeter_house['total_gas'])?0:$last_ammeter_house['total_gas'];
            $MChargeHouse->where(array('house_id'=>$house_id,'input_month'=>$month,'input_year'=>$year))->save($save1);
            if ( $result ) {
                $i++;
            }
        }
        echo "成功录入:".$i;
    }

    /**
    * [在把旧的合同和pay表导过来后，判断是否有的合同表中没有在pay表中的]
    **/
    public function check_ht_is_in_pay(){
        $MContract = M('contract');
        $MPay = M('pay');
        $contract_list = $MContract->select();
        foreach($contract_list AS $key=>$val){

            $count = $MPay->where(array('pro_id'=>$val['pro_id'],'bill_type'=>2))->count();
            if ( $count == 0 ) {
                echo $count.':';
                echo $val['id'].":";
                echo $val['pro_id']."没有在pay中生成数据!<br/>";
                $save = array();
                $save['pro_id'] = $val['pro_id'];
                $save['account_id'] = $val['account_id'];
                $save['room_id'] = $val['room_id'];
                $save['pay_status'] = $val['pay_status'];
                $save['price'] = $val['pay_total'];
                if ( $save['pay_status'] == 1 ) {
                    $save['pay_money'] = $val['pay_total'];
                    $save['pay_time'] = $val['pay_date'];
                    $save['pay_type'] =  $val['pay_type'];
                }
                $save['input_month'] = date('m',strtotime($val['create_time']));
                $save['input_year'] = date('Y',strtotime($val['create_time']));
                $save['create_time'] = $save['last_date'] = $save['should_date'] = $val['create_time'];
                $save['modify_log'] = date('Y-m-d H:i:s')." admin --> 旧系统数据丢失，自动补全支付信息";
                $save['bill_type'] = 2;

                $result = $MPay->add($save);
                if ( $result ) {
                    echo "添加成功!<br/>";
                }
            }
        }
    }

    /**
    * [检测帐号是否存在两次][并获取出那个没有关联合同的]
    **/
    public function check_is_two_account(){
        $MAccount = M("account");
        $MContract = M("contract");
        $arr_mobile = $MAccount->field("id,mobile")->select();
        $info = array();
        foreach ( $arr_mobile AS $k=>$v ) {
            /*$info[$k]['mobile'] = $v['mobile'];
            $info[$k]['id'] = $v['id'];
            $info[$k]['count'] = count($MAccount->where(array("mobile"=>$v['mobile']))->select());*/
            $account_arr = $MAccount->where(array("mobile"=>$v['mobile']))->select();
            if ( count($account_arr) > 1 ) {
                $info[$k]['mobile'] = $v['mobile'];
                $info[$k]['id'] = $v['id'];
                $info[$k]['count'] = $count;
                foreach ($account_arr as $key => $value) {
                    $contract_info = $MContract->where(array("account_id"=>$value['id']))->find();
                    if ( !is_null($contract_info) ) {
                        echo $value['id']."有合同<br/>";
                    } else {
                        echo $value['id']."没合同,";
                        $result = $MAccount->where(array("id"=>$value['id']))->delete();
                        if ( $result ) {
                            echo "删除成功!<br/>";
                        } else {
                            echo "删除失败!<br/>";
                        }
                    }
                }
            }
        }
        
    }

    /**
    * [修改缴费周期]
    **/
    public function update_zhouqi(){
        $MContract = M("contract");
        $arr = $MContract->field("id,rent_type")->select();
        foreach($arr AS $key=>$val){
            $arr_type = explode("_",$val['rent_type']);
            $MContract->where(array("id"=>$val['id']))->save(array("period"=>$arr_type['1']));
        }
        $this->success("处理成功!",U('Admin/Tool/index'));
    }

    public function update_room_account(){
        
        $MAccount = M("account");
        $MRoom = M("room");
        foreach ($arr AS $key=>$val) {
           $account_id = $MAccount->where(array("mobile"=>$val['0']))->getField("id");
           $account_id = !empty($account_id)? $account_id : 0;
           $room_info = $MRoom->where(array("room_code"=>$val['1']))->save(array("account_id"=>$account_id));

        }
        $this->success("处理成功!",U('Admin/Tool/index'));
    }

    public function update_house_steward(){
        $MAdmin = M("admin_user");
        $MHouse = M("houses");
        foreach ( $arr AS $key=>$val ) {
            $steward_id = $MAdmin->where(array("username"=>$val['0']))->getField("id");
            $MHouse->where(array("house_code"=>$val['1']))->save(array("steward_id"=>$steward_id));
        }
        $this->success("处理成功!",U('Admin/Tool/index'));
    }
    /**
    * 添加房屋水电气信息
    **/
    public function add_ammeter_house(){

        $MHouse = M("houses");
        $MAHouse = M("ammeter_house");
        foreach ($arr AS $key=>$val) {
            if ( 2016 == $val['6'] && 11 == $val['5'] ) {
                continue;
            }
            $data = array();
            $house_id = 0;
            $house_id = $MHouse->where(array("house_code"=>$val['0']))->getField("id");
            if ( is_null($house_id)) {
                file_put_contents("is_null_house_code.txt", $val['0'].",",FILE_APPEND);
                continue;
            }
            $data['house_id'] = $house_id;
            $data['total_water'] = $val['1'];
            $data['total_energy'] = $val['2'];
            $data['total_gas'] = $val['3'];
            $data['input_date'] = $val['4'];
            $data['input_month'] = $val['5'];
            $data['input_year'] = $val['6'];
            $data['energy_add'] = $val['7'];
            $data['water_add'] = $val['8'];
            $data['gas_add'] = $val['9'];
            $data['status'] = 1;
            $MAHouse->add($data);
        }
        $this->success("处理成功!",U('Admin/Tool/index'));
    }

    /**
    * 添加房间电表
    **/
    public function add_ammeter_room(){

        $MRoom = M("room");
        $MHouse = M("houses");
        $MAeemeterRoom = M("ammeter_room");
        foreach ($arr AS $key=>$val) {
            if ( 2016 == $val['6'] && 11 == $val['5'] ) {
                continue;
            }
            $room_id = $MRoom->where(array("room_code"=>$val['5']))->getField("id");
            if ( is_null($room_id) ) {
                file_put_contents("room_code_is_null.txt", $val['5'].",",FILE_APPEND);
                continue;
            }
            $house_id = $MHouse->where(array("house_code"=>$val['6']))->getField("id");
            if ( is_null($house_id) ) {
                file_put_contents("house_code_is_null.txt", $val['6'].",",FILE_APPEND);
                continue;
            }
            $data['room_energy'] = $val['0'];
            $data['input_date'] = $val['1'];
            $data['input_month'] = $val['2'];
            $data['input_year'] = $val['3'];
            $data['room_energy_add'] = $val['4'];
            $data['room_id'] = $room_id;
            $data['house_id'] = $house_id;
            $data['status'] = 1;
            $MAeemeterRoom->add($data);
        }
        $this->success("处理成功!",U('Admin/Tool/index'));
    }

    //添加合同
    public function add_contract(){
        //0.pro_id,1.room_code,2.mobile,3.start_date,4.end_date,5.rent_date,6.rent_type,7.ht_status,8.rent,9.fee,10.contact_2,11.how_pay,12.pay_status,13.person_count,14.pay_date,15.creat_date,16.total,17.paytotal,18.z_s,19.z_d,20.z_q,21.room_d,22.deposit

$arr = $this->contract_data();

        $MAccount = M("account");
        $MRoom = M("room");
        $MContract = M("contract");
        $i = 0;
        foreach ( $arr AS $key=>$val ) {
            $is_has_account = $MAccount->where(array("mobile"=>$val['2']))->select();
            if ( count($is_has_account) > 0 ) {
                $account_id = $MAccount->where(array("mobile"=>$val['2']))->getField("id");    
            } else {
                continue;
            }
            
            $room_id = $MRoom->where(array("room_code"=>$val['1']))->getField("id");
            $result = $MContract->where(array("account_id"=>$account_id,"contract_status"=>1))->select();
            if ( count($result) > 0 ) {

                continue;
            }
            $data = array();
            $data['pro_id']  = sprintf("%.0f", $val[0]);
            $data['account_id'] = !empty($account_id)?$account_id:0;
            $data['room_id'] = !empty($room_id)?$room_id:0;
            
            $data['start_time'] = $val[3];
            $data['end_time'] = $val[4];
            $data['rent_date'] = $val[5];
            $data['rent_type'] = $val[6];
            $data['contract_status'] = is_null($val[7])?0:$val[7];
            $data['rent'] = $val[8];
            $data['fee'] = $val[9];
            $data['contact2'] = $val[10];
            $data['pay_status'] = $val[12];
            $data['person_count'] = $val[13];
            $data['pay_date'] = $val[14];
            $data['create_time'] = $val[15];
            $data['total_fee'] = $val[16];
            $data['pay_total'] = $val[17];
            $data['zS'] = $val[18];
            $data['zD'] = $val[19];
            $data['zQ'] = $val[20];
            $data['roomD'] = $val[21];
            $data['deposit'] = $val[22];

            //1.现金 2.支付宝 3.微信 4.支付宝转账 5.微信转账 6.银行卡7.退租8.押金抵扣
            //支付类型 1:支付宝(线上) 2:支付宝(线下) 3：微信(线上) 4:微信(线下) 5:押金抵扣 6:现金 7.银行卡 8.退租
            switch ($val[11]) {
                case '1':
                    $pay_type = 6;
                    break;
                case '2':
                    $pay_type = 1;
                    break;
                case '3':
                    $pay_type = 3;
                    break;
                case '4':
                    $pay_type = 2;
                    break;
                case '5':
                    $pay_type = 4;
                    break;
                case '6':
                    $pay_type = 7;
                    break;
                case '7':
                    $pay_type = 8;
                    break;
                case '8':
                    $pay_type = 5;
                    break;
                default:
                    $pay_type = 0;
                    break;
            }
            $data['pay_type'] = $pay_type;

            $data['is_delete'] = 0;
            $data['log'] = "t_account_room导入数据";
            $MContract->add($data);
            $i++;
        }

        $this->success("处理成功!数量:".$i,U('Admin/Tool/index'));
    }

    //更新用户信息
    public function update_account(){       
        $MAccount = M("account");
        foreach ($arr as $key => $value) {
            $where['mobile'] = $value['2'];
            $save['realname'] = $value['0'];
            $save['password'] = $value['1'];
            $save['card_no'] = $value['3'];
            $save['sex'] = ($value['4']=='M')? 1:2;
            $MAccount->where($where)->save($save);
        }
        $this->success("处理成功!",U('Admin/Tool/index'));
    }

    // 添加日常账单
    public function add_charge_bill(){

        $MAccount = M("account");
        $MRoom = M("room");
        $MChargebill = M("charge_bill");

$arr = $this->charge_data();
        //0.mobile,1.pro_id,2.room_code,3.house_code,4.water_fee,5.energy_fee AS room_energy_fee,6.public_energy_fee,7.gas_fee,8.rubbish_fee,9.total_person_day,10.unit_person_day AS person_day,11.cteate_time AS create_time,12.modify_time,13.total_energy,14.total_water,15.total_gas,16.wgfee_unit,17.public_energy_fee_person AS energy_fee,18.total_fee,19.rent_fee,20.rent_des,21.service_fee,22.room_energy_add,23.issendtouser AS is_send,24.wx_fee,25.wx_des,26.type,27.rent_date_old,28.rent_date_to
        $i = 0;
        foreach ($arr as $key => $val) {
            $pro_id = sprintf("%.0f", $val[1]);
            $room_id = $MRoom->where(array("room_code"=>$val[2]))->getField("id");
            $account_id = $MAccount->where(array("mobile"=>$val[0]))->getField("id");
            if ( is_null($account_id) ) {
                file_put_contents('D://mobile_is_null.txt', "mobile:".$val[0].",", FILE_APPEND);
                continue;
            }
            if ( is_null($room_id) ) {
                file_put_contents('D://room_code_is_null.txt', "room_code:".$val[2].",", FILE_APPEND);
                continue;
            }
            $data['pro_id'] = $pro_id;
            $data['room_id'] = $room_id;      
            $data['account_id'] = $account_id;
            $data['house_code'] = $val[3];
            $data['water_fee'] = $val[4];
            $data['room_energy_fee'] = $val[5];
            $data['public_energy_fee'] = $val[6];
            $data['gas_fee'] = $val[7];
            $data['rubbish_fee'] = $val[8];
            $data['total_person_day'] = $val[9];
            $data['person_day'] = $val[10];
            $data['create_time'] = $val[11];
            $data['modify_log'] = 't_charge_weg导入';
            $data['modify_time'] = $val[12];
            $data['total_energy'] = $val[13];
            $data['total_water'] = $val[14];
            $data['total_gas'] = $val[15];
            $data['wgfee_unit'] = $val[16];
            $data['energy_fee'] = $val[17];
            $data['total_fee'] = $val[18];
            $data['rent_fee'] = $val[19];
            $data['rent_des'] = $val[20];
            $data['service_fee'] = $val[21];
            $data['room_energy_add'] = $val[22];
            $data['is_send'] = $val[23];
            $data['wx_fee'] = $val[24];
            $data['wx_des'] = $val[25];
            $data['type'] = $val[26];
            $data['rent_date_old'] = $val[27];
            $data['rent_date_to'] = $val[28];
            $id = $MChargebill->add($data);
            $i++;
        }

        $this->success("处理成功!".$i,U('Admin/Tool/index'));
    }

    /**
    * 判断此房间是否有正常合同，没有则为未租
    **/
    public function is_in_person_room(){
        $Mroom = M("room");
        $room_list = $Mroom->select();
        $MContract = M("contract");
        $str = '';
        foreach ( $room_list AS $key=>$val ) {
            $contract = $MContract->where(array("room_id"=>$val['id'],"contract_status"=>1))->find();
            if ( is_null($contract) ){
               $str .= $val['id'].",";
               $Mroom->where(array("id"=>$val['id']))->save(array("account_id"=>0,"status"=>0));
            }
        }
        $str = substr($str, 0 , -1);
        dump($str);
        $this->success("处理成功!",U('Admin/Tool/index'));
    }

    /**
    * 添加t_pay
    **/
    public function add_pay(){
        $MPay = M('pay');
        $MRoom = M('room');
        $MAccount = M('account');
        //0room_code,1mobile,2price,3.bill_type,4pay_status,5pay_date,6create_time,7pro_id,8pay_type,9is_show,10input_month,11input_year,12should_time,13last_time,14transaction_id,15modify_log,16back_rent,17rentdate_rent,18rentdate_fee,19delete_person,20dc_fee,21dc_des

$arr = $this->pay_data();

        $i = 0;
        foreach ($arr as $key => $val) {
            $data = array();
            $pro_id = sprintf("%.0f", $val[7]);
            $pro_id_is_has = $MPay->where(array('pro_id'=>$pro_id))->select();
            $room_id = $MRoom->where(array("room_code"=>$val['0']))->getField("id");
            $account_id = $MAccount->where(array("mobile"=>$val['1']))->getField("id");
            if ( $room_id == null ) {
                file_put_contents("D://room_code_is_null.txt", "room_code:".$val['0'].",pro_id:".$pro_id."\n。", FILE_APPEND);
                continue;
            }
            if ( $account_id == null ) {
                file_put_contents("D://account_id_is_null.txt", "account_id:".$val['1'].",pro_id:".$pro_id."\n。", FILE_APPEND);
                continue;
            }
            $data['room_id'] = $room_id;
            $data['account_id'] = $account_id;
            $data['price'] = $val['2'];
            $data['bill_type'] = $val['3'];
            $data['pay_status'] = $val['4'];

            //1.现金 2.支付宝 3.微信 4.支付宝转账 5.微信转账 6.银行卡7.退租8.押金抵扣
            //支付类型 1:支付宝(线上) 2:支付宝(线下) 3：微信(线上) 4:微信(线下) 5:押金抵扣 6:现金 7.银行卡 8.退租
            switch ($val['8']) {
                case '1':
                    $pay_type = 6;
                    break;
                case '2':
                    $pay_type = 1;
                    break;
                case '3':
                    $pay_type = 3;
                    break;
                case '4':
                    $pay_type = 2;
                    break;
                case '5':
                    $pay_type = 4;
                    break;
                case '6':
                    $pay_type = 7;
                    break;
                case '7':
                    $pay_type = 8;
                    break;
                case '8':
                    $pay_type = 5;
                    break;
                default:
                    $pay_type = 0;
                    break;
            }
            $data['pay_type'] = $pay_type;

            if ( $data['pay_status'] == 1 ) {
                $data['pay_money'] = $data['price'];
                $data['pay_time'] = $val['5'];
                $data['pay_type'] = $pay_type;
            } else {
                $data['pay_money'] = 0;
                $data['pay_time'] = 0;
                $data['pay_type'] = 0;
            }
            $data['create_time'] = $val['6'];
            $data['pro_id'] = sprintf("%.0f", $val[7]);     
            $data['is_show'] = $val['9'];
            $data['input_month'] = $val['10'];
            $data['input_year'] = $val['11'];
            $data['should_date'] = $val['12'];
            $data['last_date'] = $val['13'];
            $data['transaction_id'] = $val['14'];
            $data['modify_log'] = 't_pay导入'.$val['15'];
            $data['back_rent'] = $val['16'];
            $data['rentdate_rent'] = $val['17'];
            $data['rentdate_fee'] = $val['18'];
            $data['delete_person'] = $val['19'];
            $data['dc_fee'] = $val['20'];
            $data['dc_des'] = $val['21'];
            $MPay->add($data);
            $i++;
        }
        echo '录入'.$i.'条';
    }

    /**
    * [添加租客信息]
    **/
    public function add_account(){
        //0mobile,1nickname,2email,3realname,4`password`,5register_time,6tag,7sex,8birthday,9avatar,10address,11card_no,12job,13`enable`,14specialty,15wx_no,16qq,17hobby,18edu_history,19wx_openid
$arr = $this->account_data();

        $i = 0;
        $MAccount = M('account');
        foreach ($arr as $key => $val) {
            $is_has_account = $MAccount->where(array('mobile'=>$val[0]))->find();
            if ( is_null($is_has_account) ) {
                //没有租客信息则添加
                $i++;
                $data['mobile'] = $val[0];
                $data['nickname'] = $val[1];
                $data['email'] = $val[2];
                $data['realname'] = $val[3];
                $data['password'] = $val[4];
                $data['register_time'] = $val[5];
                $data['tag'] = $val[6];
                $data['sex'] = $val[7]=='M'?1:2;
                $data['birthday'] = $val[8];
                $data['avatar'] = $val[9];
                $data['address'] = $val[10];
                $data['card_no'] = $val[11];
                $data['job'] = $val[12];
                $data['enable'] = $val[13];
                $data['specialty'] = $val[14];
                $data['wx_no'] = $val[15];
                $data['qq'] = $val[16];
                $data['hobby'] = $val[17];
                $data['edu_history'] = $val[18];
                $data['wx_openid'] = $val[19];
                $MAccount->add($data);
            } else {
                $save['nickname'] = $val[1];
                $save['email'] = $val[2];
                $save['realname'] = $val[3];
                $save['password'] = $val[4];
                $save['register_time'] = $val[5];
                $save['tag'] = $val[6];
                $save['sex'] = $val[7]=='M'?1:2;
                $save['birthday'] = $val[8];
                $save['avatar'] = $val[9];
                $save['address'] = $val[10];
                $save['card_no'] = $val[11];
                $save['job'] = $val[12];
                $save['enable'] = $val[13];
                $save['specialty'] = $val[14];
                $save['wx_no'] = $val[15];
                $save['qq'] = $val[16];
                $save['hobby'] = $val[17];
                $save['edu_history'] = $val[18];
                $save['wx_openid'] = $val[19];
                $MAccount->where(array('mobile'=>$val[0]))->save($save);
            }
        }
        echo '录入'.$i.'条';
    }

    /**
    * [搜索房屋编号是否存在]
    **/
    public function check_house_code_is_has(){

        $MHouse = M('houses');
        $MHouse->startTrans();
        $flag = false;
        foreach ($arr as $val) {
            $result = $MHouse->field('id,house_code,is_update')->where(array('house_code'=>$val[0],'is_update'=>0))->find();
            if ( $result ) {
                $result1 = $MHouse->where(array('id'=>$result['id']))->save(array('house_code'=>$val[1],'is_update'=>1));
                if ( $result1 ) {
                    $flag = true;
                }
                echo $val[0].'存在'.'<br/>';
                echo '将'.$val[0].'修改为'.$val[1].'<br/>';
            } else {
                echo $val[0].'不存在'.'<br/>';
            }
            echo '<br/>';
        }
        if(!$flag){ 
               $MHouse->rollback();
        }else{
             $MHouse->commit();
       }
    }

    /**
    * 租客数据
    **/
    public function account_data(){
    }

    /**
    * pay列表数据
    **/
    public function pay_data(){
    }

    /**
    * 合同数据
    **/
    public function contract_data(){
    }

    /**
    * 日常数据
    **/
    public function charge_data(){
    }
}