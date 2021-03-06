<?php
namespace Admin\Controller;
use Think\Controller;
class HousesController extends BaseController {
    public function __construct(){
        parent::__construct();
        if ( empty($_SESSION['username']) && ACTION_NAME != 'register') {
            header("Location:".U("Admin/Index/login"));
            die();
        }
    }

    public function index(){
        $_SESSION['P_REFERER'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']; 
        $where = [];

        $cityId = I('post.cityId');
        if (!empty($cityId)) {
            $where['area.city_id'] = I('post.cityId');
            $this->assign('cityId',$cityId);
        }
        $areaId = I('areaId');
        if (!empty($areaId)) {
            $where['area.id'] = I('areaId');
            $this->assign('areaId',$areaId);
        }
        $stewardId = I('post.stewardId');
        if (!empty($stewardId)) {
            $where['h.steward_id'] = I('post.stewardId');
            $this->assign('stewardId',$stewardId);
        }

        $DHouses = D('Houses');
        $DArea = D('area');
        $DAdmin = D('admin_user');
        $DChargeHouse = D('charge_house');
        $MPay = M("pay");   
        $MRoom = M('room');
        //当月
        $year = date("Y",time());
        $month = date("m",time());
        $this->assign("year",$year);
        $this->assign("month",$month);
        //上一个月
        $lastDate = date("Y-m",strtotime($year."-".$month."- 1 month"));
        $lastYear = date("Y",strtotime($lastDate));
        $lastMonth = date("m",strtotime($lastDate));
        $this->assign("lastYear",$lastYear);
        $this->assign("lastMonth",$lastMonth);

        // 房屋列表 
        $housesList = $DHouses->getHousesList($where);
        // 房屋总数
        $housesCount = $DHouses->getHousesCount($where);
        // 小区列表
        $areaList = $DArea->getareaList();
        // 管家列表
        $stewardList = $DAdmin->getStewardAccount();
        // 该月已发总数
        $nowSendCount = 0;
        // 该月应发总数
        $nowMustSendCount = 0;
        // 上个月已发总数
        $lastSendCount = 0;
        // 上个月应发总数
        $lastMustSendCount = 0;
        // 该月房屋已发总数
        $nowHousesSendCount = $DChargeHouse->getCreatedBillCount($where,[
            'input_year' => $year,
            'input_month' => $month,
            'is_create' => 1
        ]);
        // 上个月房屋已发总数
        $lastHousesSendCount = $DChargeHouse->getCreatedBillCount($where,[
            'input_year' => $lastYear,
            'input_month' => $lastMonth,
            'is_create' => 1
        ]);
        $total_yz_count = 0;

        foreach($housesList AS $key=>$val){
            // 已租人数
            $total_yz_count += $val['yz_count'];
            //当月
            $where = array();
            $where['cb.house_code'] = $val['house_code'];
            $where['p.input_year'] = $year;
            $where['p.input_month'] = $month;
            $where['cb.type'] = 1;
            // 获取该房屋应发账单的总数
            $housesList[$key]['now_total_count'] = $MPay
            ->alias('p')
            ->join('lewo_charge_bill cb ON cb.pro_id=p.pro_id ')
            ->where($where)
            ->count();
            // 获取该月房屋发账单的总数
            $where['p.is_send'] = 1;
            $housesList[$key]['now_sended_count'] = $MPay
            ->alias('p')
            ->join('lewo_charge_bill cb ON cb.pro_id=p.pro_id ')
            ->where($where)
            ->count();
            // 获取该月应发账单的总数
            $nowMustSendCount += $housesList[$key]['now_total_count'];
            // 获取该月已经发账单的总数
            $nowSendCount += $housesList[$key]['now_sended_count'];
            //上月
            $where = array();
            $where['cb.house_code'] = $val['house_code'];
            $where['p.input_year'] = $lastYear;
            $where['p.input_month'] = $lastMonth;
            $housesList[$key]['last_total_count'] = $MPay
            ->alias('p')
            ->field('cb.*,p.*')
            ->join('lewo_charge_bill cb ON cb.pro_id=p.pro_id ')
            ->where($where)
            ->count();
            $where['p.is_send'] = 1;
            $housesList[$key]['last_sended_count'] = $MPay
            ->alias('p')
            ->field('cb.*,p.*')
            ->join('lewo_charge_bill cb ON cb.pro_id=p.pro_id ')
            ->where($where)
            ->count();
            // 上个月已经应的总数
            $lastMustSendCount += $housesList[$key]['last_total_count'];
            // 上个月已经发账单的总数
            $lastSendCount += $housesList[$key]['last_sended_count'];

        }
        
        $this->assign('total_yz_count',$total_yz_count);
        $this->assign('nowHousesSendCount',$nowHousesSendCount);
        $this->assign('lastHousesSendCount',$lastHousesSendCount);
        $this->assign('housesCount',$housesCount);
        $this->assign('stewardList',$stewardList);
        $this->assign('areaList',$areaList);
        $this->assign('city_list', C('city_id'));
        $this->assign('nowSendCount',$nowSendCount);
        $this->assign('nowMustSendCount',$nowMustSendCount);
        $this->assign('lastSendCount',$lastSendCount);
        $this->assign('lastMustSendCount',$lastMustSendCount);
        $this->assign("housesList",$housesList);
        $this->display("Common/header");
        $this->display("Common/nav");
        $this->display("houses");
        $this->display("Common/footer");
    }

    /**
    * [获取小区]
    **/
    public function area(){
        $DArea = D('area');
        $this->assign("areaList",$DArea->getareaList());
        $this->display("Common/header");
        $this->display("Common/nav");
        $this->display("area");
        $this->display("Common/footer");
    }

    /**
    * [添加小区]
    **/
    public function add_area(){
        if ( !empty($_POST) ) {
            $data['area_name'] = I("post.area_name");
            $data['area_description'] = I("post.area_description");
            $data['city_id'] = I("post.city_id");
            $data['energy_unit'] = I("post.energy_unit");
            $data['water_unit'] = I("post.water_unit");
            $data['gas_unit'] = I("post.gas_unit");
            $data['energy_stair'] = I("post.energy_stair");
            $data['rubbish_fee'] = I("post.rubbish_fee");
            $result = M("area")->add($data);
            if ( !empty($result) ) {
                $this->success("添加成功!",U('Admin/Houses/area'));
            } else {
                $this->error("添加失败!",U('Admin/Houses/area'));
            }
        } else {
            $this->assign("city_id",C("city_id"));
            $this->display("add-area");
            $this->display("Common/footer");
        }
    }   
    /**
    * [修改小区信息]
    **/
    public function update_area(){
        if ( !empty($_POST) ) {
            $id = I("id");
            $data['area_name'] = I("post.area_name");
            $data['area_description'] = I("post.area_description");
            $data['energy_unit'] = I("post.energy_unit");
            $data['water_unit'] = I("post.water_unit");
            $data['gas_unit'] = I("post.gas_unit");
            $data['energy_stair'] = I("post.energy_stair");
            $data['rubbish_fee'] = I("post.rubbish_fee");
            $result = M("area")->where(array("id"=>$id))->save($data);
            if ( !empty($result) ) {
                $this->success("修改成功!",U('Admin/Houses/area'));
            } else {
                $this->error("修改失败!",U('Admin/Houses/area'));
            }
        } else {
            $id  = I("area_id");
            $DArea = D("area");
            $area_info = $DArea->getAreaById($id);

            $this->assign("area_info",$area_info);
            $this->assign("city_id",C("city_id"));
            $this->display("update-area");
            $this->display("Common/footer");   
        }
    }

    /**
    * [删除小区]
    **/
    public function delete_area(){
        $id = I("area_id");
        $result = D('area')->where(array("id"=>$id))->delete();
        if ($result) {
            $this->success('删除成功');
        } else {
            $this->error("删除失败");
        }
    }

    /*
     * [添加房源]
     */
    public function add_house(){
        if ( !empty($_POST) ) {
            $Mhouses = M('houses');

            $data['house_code'] = I('post.house_code');
            $data['type'] = I('post.type');
            $data['area_id'] = I('post.area_id');
            $data['building'] = I('post.building');
            $data['floor'] = I('post.floor');
            $data['door_no'] = I('post.door_no');
            $data['region_id'] = I('post.region_id');
            $data['steward_id'] = I('post.steward_id');
            $data['fee'] = I('post.fee');
            $data['house_owner'] = I('post.house_owner');
            $data['house_mobile'] = I('post.house_mobile');
            $data['start_date'] = I('post.start_date');
            $data['end_date'] = I('post.end_date');
            $data['area_description'] = I('post.area_description');
            $data['house_description'] = I('post.house_description');
            $data['subway'] = I('post.subway');
            $data['address'] = I('post.address');
            $data['modify_time'] = date("Y-m-d H:i:s", time());
            $data['create_time'] = date("Y-m-d H:i:s", time());

            $result = $Mhouses->where(array('house_code'=>$data['house_code']))->find();
            if( empty($result) ){
                $Mhouses->add($data);
                if ( $data['type'] == 1 ) {
                    $this->success("房屋生成成功！",U("Admin/Houses/add_room",array('house_code'=>$data['house_code'],'add_type'=>'room')));//房屋添加成功后到房间添加页面
                } elseif ( $data['type'] == 2 ) {
                    $this->success("房屋生成成功！",U("Admin/Houses/add_room",array('house_code'=>$data['house_code'],'add_type'=>'bed')));
                }
            } else {
                $this->error("房屋编码已存在！");
            }
        } else {

            $DSteward = D('admin_user');
            $DRegion = D('Region');
            $this->assign('steward_list',$DSteward->getStewardAccount());
            $DArea = D('area');
            $this->assign("areaList",$DArea->getareaList());
            $this->assign("region_list",$DRegion->getRegion());
    	    $this->display("add-house");
    	    $this->display("Common/footer");
        }
    }

    /**
     * [房屋修改]
     **/
    public function update_house(){
        if ( !empty($_POST) ) {
            $data['house_code'] = I('post.house_code');
            $data['type'] = I('post.type');
            $data['area_id'] = I('post.area_id');
            $data['building'] = I('post.building');
            $data['floor'] = I('post.floor');
            $data['door_no'] = I('post.door_no');
            $data['region_id'] = I('post.region_id');
            $data['steward_id'] = I('post.steward_id');
            $data['fee'] = I('post.fee');
            $data['init_water'] = I('post.init_water');
            $data['init_energy'] = I('post.init_energy');
            $data['init_gas'] = I('post.init_gas');
            $data['house_owner'] = I('post.house_owner');
            $data['house_mobile'] = I('post.house_mobile');
            $data['start_date'] = I('post.start_date');
            $data['end_date'] = I('post.end_date');
            $data['area_description'] = I('post.area_description');
            $data['house_description'] = I('post.house_description');
            $data['subway'] = I('post.subway');
            $data['address'] = I('post.address');
            $data['modify_time'] = date("Y-m-d H:i:s", time());

            $MHouses = M('Houses');
            $result = $MHouses->where(array('house_code'=>$data['house_code']))->save($data);
            if ( $result  ) {
                $this->success("修改成功",U("Admin/Houses/index"));
            } else {
                $this->error("修改失败");
            }
            
        } else {
            $house_code = I('house_code');
            $DHouses = D('Houses');
            $DRegion = D('Region');
            $DSteward = D('admin_user');
            $this->assign('steward_list',$DSteward->getStewardAccount());
            $house_info = $DHouses->getHouse($house_code);
            foreach($house_info AS $key=>$val){
                $this->assign($key,$val);
            }
            $DArea = D('area');
            $this->assign("areaList",$DArea->getareaList());
            $this->assign('house_code',$house_code);
            $this->assign("region_list",$DRegion->getRegion());
            $this->display("update-house");
            $this->display("Common/footer");
        }

    }

    /**
     * [房间/床位添加]
     **/
    public function add_room(){
        $Mroom = M('room');
        $MHouses = M('houses');

        if ( !empty($_POST) ) {
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
            $upload->savePath  =     ''; // 设置附件上传（子）目录
            $info   =   $upload->upload();
            $room_head_images = '';
            if(count($info) != 0) { 
                foreach( $info AS $key=>$val ){
                    if ( $val['key'] == 'room_images_01' ) {
                        $data['room_images_01'] = $val['savepath'].$val['savename'];
                    }
                    if ( $val['key'] == 'room_images_02' ) {
                        $data['room_images_02'] = $val['savepath'].$val['savename'];
                    }
                    if ( $val['key'] == 'room_images_03' ) {
                        $data['room_images_03'] = $val['savepath'].$val['savename'];
                    }
                    if ( $val['key'] == 'room_images_04' ) {
                        $data['room_images_04'] = $val['savepath'].$val['savename'];
                    }
                    if ( $val['key'] == 'room_head_images' ) {
                        $data['room_head_images'] = $val['savepath'].$val['savename'];
                    }
                    if ( $val['key'] == 'house_type_image' ) {
                        $data['house_type_image'] = $val['savepath'].$val['savename'];
                    }
                }
            }
            $data['house_code'] = I('post.house_code');
            $data['house_id'] = $MHouses->where(array('house_code'=>$data['house_code']))->getField('id');
            $bed_code = I('post.bed_code');
            if ( !empty($bed_code) ) {
                $data['bed_code'] = $bed_code;
                $data['room_type'] = 2;
            } else {
                 $data['room_type'] = 1;
            }
            $data['room_code'] = I('post.room_code');
            $data['room_sort'] = I('post.room_sort');
            $data['room_nickname'] = I('post.room_nickname');
            $data['room_introduce'] = I('post.room_introduce');
            $data['room_area'] = I('post.room_area');
            $data['room_sort'] = I('post.room_sort');
            $data['person_count'] = I('post.person_count');
            $data['rent'] = I('post.rent');
            $data['room_fee'] = I('post.room_fee');
            $data['room_parameter'] = count(I('post.room_parameter'))==0? serialize(I('post.room_parameter')):'';
            $data['modify_time'] = date("Y-m-d H:i:s", time());
            $data['create_time'] = date("Y-m-d H:i:s", time());

            $Mroom->add($data);
            if ( isset($_POST['saveAndAdd']) ) {
                $this->success("生成成功,并继续添加");
            } else {
                $this->success("生成成功",U("Admin/Houses/detail_house",array('house_code'=>$data['house_code'])));
            }
           
        } else {
            $add_type = I("add_type");
            $title = ($add_type=='room')? '房间':'床位'; 
            $house_code = I('house_code');
            $house_info = $MHouses
                        ->alias('h')
                        ->field('h.*,area.area_name')
                        ->join('lewo_area area ON area.id = h.area_id')
                        ->where(array('h.house_code'=>$house_code))
                        ->find();
            $this->assign('house_info',$house_info);
            $this->assign('add_type',$add_type);
            $this->assign('title',$title);
            $this->assign('room_sort_arr',C('room_sort_arr'));
            $this->assign('person_count_arr',C('person_count_arr'));
            $this->assign('house_code',$house_code);
            $this->display("add-room");
            $this->display("Common/footer");
        }
    }

    /**
     * [房间/床位修改]
     **/
    public function update_room(){
        if ( !empty($_POST) ) {
            $id = I("post.id");
            $Mroom = M('room');
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
            $upload->savePath  =     ''; // 设置附件上传（子）目录
            $info   =   $upload->upload();
            $room_head_images = '';
            if(count($info) != 0) { 
                foreach( $info AS $key=>$val ){
                    if ( $val['key'] == 'room_images_01' ) {
                        $data['room_images_01'] = $val['savepath'].$val['savename'];
                    }
                    if ( $val['key'] == 'room_images_02' ) {
                        $data['room_images_02'] = $val['savepath'].$val['savename'];
                    }
                    if ( $val['key'] == 'room_images_03' ) {
                        $data['room_images_03'] = $val['savepath'].$val['savename'];
                    }
                    if ( $val['key'] == 'room_images_04' ) {
                        $data['room_images_04'] = $val['savepath'].$val['savename'];
                    }
                    if ( $val['key'] == 'room_head_images' ) {
                        $data['room_head_images'] = $val['savepath'].$val['savename'];
                    }
                    if ( $val['key'] == 'house_type_image' ) {
                        $data['house_type_image'] = $val['savepath'].$val['savename'];
                    }
                }
            }
            $data['house_code'] = I('post.house_code');
            $bed_code = I('post.bed_code');
            if ( !empty($bed_code) ) {
                $data['bed_code'] = $bed_code;
                $data['room_type'] = 2;
            } else {
                 $data['room_type'] = 1;
            }
            $data['room_code'] = I('post.room_code');
            $data['room_sort'] = I('post.room_sort');
            $data['room_nickname'] = I('post.room_nickname');
            $data['room_introduce'] = I('post.room_introduce');
            $data['room_area'] = I('post.room_area');
            $data['room_sort'] = I('post.room_sort');
            $data['person_count'] = I('post.person_count');
            $data['rent'] = I('post.rent');
            $data['room_fee'] = I('post.room_fee');
            $data['room_parameter'] = serialize(I('post.room_parameter'));
            $data['modify_time'] = date("Y-m-d H:i:s", time());
            $data['is_show'] = I("is_show");
            $a = $Mroom->where(array('id'=>$id))->save($data);
            $this->success("房间修改成功",U("Admin/Houses/detail_house",array('house_code'=>$data['house_code'])));
            
        } else {
            $id = I('id');
            $house_code = I('house_code');
            $rent_out_type = I('rent_out_type');
            $title = $rent_out_type=='bed'? '床位':'房间';

            $DRoomInfo = D('Houses')->getRoom($id);
            $DRoomInfo['room_parameter'] = unserialize($DRoomInfo['room_parameter']);
            $DRoomInfo['room_images'] = unserialize($DRoomInfo['room_images']);
            foreach ( $DRoomInfo AS $key=>$val ) {
                $this->assign($key,$val);
            }
            $this->assign('id',$id);
            $this->assign('title',$title);
            $this->assign('rent_out_type',$rent_out_type);
            $this->assign('room_sort_arr',C('room_sort_arr'));
            $this->assign('person_count_arr',C('person_count_arr'));
            $this->assign('update',1);
            $this->assign('house_code',$house_code);
            $this->display("update-room");
            $this->display("Common/footer");
        }
    }

    /**
     * [房屋详细页]
     **/
    public function detail_house(){
        $house_code = I('get.house_code');
        $area_id = I('area_id');
        $roomList = D("Houses")->getRoomList($house_code);
        $this->assign('room_list',$roomList);
        $this->assign('house_code',$house_code);
        $this->display("Common/header");
        $this->display("Common/nav");
        $this->display("detail-house");
        $this->display("Common/footer");
    }

    /**
     * [房间/床位删除]
     **/
    public function delete_room(){
        $id = I('id');
        $DRoom = D('Houses');
        $result = $DRoom->deleteRoom($id);
        if ($result) {
            $this->success('删除成功');
        } else {
            $this->error("删除失败");
        }
    }

    /**
    * [查看账单]
    **/
    public function check_bill(){
        $charge_id  = I("charge_id");
        $house_id   = I("house_id");
        $year       = I("year"); //当年
        $month      = I("month"); //当月
        $DHouses    = D("houses");
        $DArea      = D("area");
        $DAcccount  = D("account");
        $house_code = $DHouses->getHouseCodeById($house_id);
        $house_info = $DHouses->getHouse($house_code);
        $area_info  = $DArea->getAreaById($house_info['area_id']); //水电气单价
        $energy_stair_arr = explode(",",$area_info['energy_stair']); //阶梯电费单价
        foreach ($energy_stair_arr AS $key=>$val) {
            $energy_stair[] = explode("-",$val);//阶梯算法数组
        }
        $this->assign('house_info',$house_info);
        $this->assign('area_info',$area_info);
        $DChargeBill = D("charge_bill");

        // 1.月底水电气2.退租水电气结算 3.换租水电气结算 4.转租水电气结算
        $bill_list = $DChargeBill->showChargeBillList($house_code,$year,$month,1);//1.月底水电气        

        // 获取房间水电气的起止度
        $DChargeHouse = D("charge_house");
        // 判断是否在charge_house里生成该月,没有则生成一条
        $DChargeHouse->postOneCharge($house_id,$year,$month);
        // 获取房屋当月的水电气信息
        $charge_house_info = $DChargeHouse->getChargeHouseInfo($house_id,$year,$month);

        // 输出总水电气的计算
        // 房屋总电费
        $room_total_energy_fee = 0;
        // 是否有支付的账单
        $is_has_pay = false;
        foreach ( $bill_list AS $k=>$v ) {
            $room_total_energy_fee += $v['room_energy_fee'];
            // 查找是否有pay_status=1
            if ( $v['pay_status'] != 0){
                $is_has_pay = true;
            }
        }
        $room_total_energy_fee = number_format($room_total_energy_fee,2);
        $this->assign('is_has_pay',$is_has_pay);
        // 增加的电度数
        $add_energy         = $charge_house_info['end_energy'] - $charge_house_info['start_energy'];
        $total_energy_fee   = number_format(get_energy_fee($add_energy,$energy_stair),2);
        $public_energy_fee  = ( $total_energy_fee - $room_total_energy_fee ) + $charge_house_info['extra_public_energy_fee'];
        // 增加的水度数
        $add_water          = $charge_house_info['end_water'] - $charge_house_info['start_water'];
        $public_water_fee   = ( $add_water * $area_info['water_unit'] ) + $charge_house_info['extra_public_water_fee'];
        // 增加的气度数
        $add_gas            = $charge_house_info['end_gas'] - $charge_house_info['start_gas'];
        $public_gas_fee     = ( $add_gas * $area_info['gas_unit'] ) + $charge_house_info['extra_public_gas_fee'];
        
        //获取 该月已退房的账单(非正常账单) 
        //公共区域水电气 - 已退租的水电气 = 该月未退租(正常合同)的租客的公共区域水电气
        //去除了已退租的租客的水电气，剩下的水电气由正常租客均摊
        $abnormal_bill_list = $DChargeBill->showAbnormalChargeBillList($house_code,$year,$month);//非正常账单
        $this->assign("abnormal_bill_list",$abnormal_bill_list);
        //计算非正常账单的水电气总额
        $abnormal_total_energy_fee  = 0;
        $abnormal_total_water_fee   = 0;
        $abnormal_total_gas_fee     = 0;
        foreach ($abnormal_bill_list AS $k=>$v) {
            $abnormal_total_energy_fee  += $v['energy_fee'];
            $abnormal_total_water_fee   += $v['water_fee'];
            $abnormal_total_gas_fee     += $v['gas_fee'];
        }
        $public_energy_fee  -= $abnormal_total_energy_fee;
        $public_water_fee   -= $abnormal_total_water_fee;
        $public_gas_fee     -= $abnormal_total_gas_fee;

        $this->assign("total_energy_fee",$total_energy_fee);
        $this->assign("add_energy",$add_energy);
        $this->assign("room_total_energy_fee",$room_total_energy_fee);
        $this->assign("public_energy_fee",$public_energy_fee);

        $this->assign("water_unit",$area_info['water_unit']);
        $this->assign("add_water",$add_water);
        $this->assign("public_water_fee",$public_water_fee);

        $this->assign("gas_unit",$area_info['gas_unit']);
        $this->assign("add_gas",$add_gas);
        $this->assign("public_gas_fee",$public_gas_fee);
        $this->assign("energy_stair",$area_info['energy_stair']);//阶梯

        $this->assign("type",$house_info['type']);
        $this->assign("charge_house_info",$charge_house_info);
        $this->assign('house_id',$house_id);
        $this->assign("year",$year);
        $this->assign("month",$month);
        $this->assign('bill_list',$bill_list);
        $this->assign('ammeter',$ammeter);
        $this->assign('last_ammeter',$last_ammeter);
        $this->assign('house_code',$house_code);
        $this->display('check-bill');
    }

    /**
    * [发送账单到租客手机]
    **/
    public function send_bill(){
        $house_id = I("house_id");
        $year = I("year"); //当年
        $month = I("month"); //当月
        $pro_id = explode(",",I("pro_id"));
        $MPay = M('pay');
        $DChargeHouse = D("charge_house");
        $DChargeBill = M("charge_bill");
        $MPay = M('pay');
        $DAccount = D("account");

        //创蓝接口
        Vendor('ChuanglanSms.chuanglanSmsApi');
        $clapi  = new \ChuanglanSmsApi();
        $success_id = array();

        foreach ( $pro_id AS $val ) {
            $pay_info = $MPay->field('price,account_id,should_date,last_date,room_id')->where(array('pro_id'=>$val))->find();
            $mobile = $DAccount->getFieldById($pay_info['account_id'],"mobile");
            $realname = $DAccount->getFieldById($pay_info['account_id'],"realname");
            $msg = '亲爱的乐窝小主,你的'.$month.'月份帐单已出炉,总金额为'.$pay_info['price'].',请在'.$pay_info['should_date'].'前处理~,快来查看 http://'.$_SERVER['HTTP_HOST'];
            $result = $clapi->sendSMS($mobile, $msg,'true');
            $result = $clapi->execResult($result);
            if($result[1]==0){
                $MPay->where(array('pro_id'=>$val))->save(array("is_send"=>1));
                $success[$val] = $realname;
            }

            $send_time = date("Y-m-d H:i:s",time());
            M("sms_log")->add(array("mobile"=>$mobile,"account_id"=>$pay_info['account_id'],"room_id"=>$pay_info['room_id'],"message"=>$msg,"send_time"=>$send_time,"result"=>serialize($result)));
        }
        die(json_encode($success));
    }

    public function updateBillField()
    {
        $proId = I('post.proId');
        $type = I('post.type');
        $fieldName = I('post.fieldKey');
        $fieldValue = I('post.fieldValue');
        $bill = [];
        $bill[$fieldName] = $fieldValue;
        switch ($type) {
            case 'overdue':
                $model = M('charge_bill');
                break;
            case 'time':
                $model = M('pay');
                break;
            default:
                break;
        }
        $r0 = $model->where(['pro_id' => $proId])->save($bill);
        if ($r0 > 0) {
            if ($type = 'overdue') {
                $money = M('charge_bill')->where(['pro_id' => $proId])->field(['rent_fee', 'service_fee', 'wgfee_unit', 'room_energy_fee', 'energy_fee', 'water_fee', 'gas_fee', 'rubbish_fee', 'wx_fee'])->find();
                $favorable = M('pay')->where(['pro_id' => $proId])->getField('favorable');
                $price = 0;
                foreach ($money as $key => $value) {
                    $price = $price + $value;
                }
                $price = $price - $favorable;
                M('pay')->where(['pro_id' => $proId])->save(['price' => $price]);
                M('charge_bill')->where(['pro_id' => $proId])->save(['total_fee' => $price]);
                dump($price);
            }
            echo 'success';
        }
    }

    /**
    * [修改账单]
    **/
    public function update_bill(){
        $charge_house_id    = I("charge_house_id");
        $house_id           = I("house_id");
        $year               = I("year");
        $month              = I("month");
        $room_list          = I("room_list");
        $extra_public_energy_fee = I("extra_public_energy_fee");
        $extra_public_water_fee  = I("extra_public_water_fee");
        $extra_public_gas_fee    = I("extra_public_gas_fee");
        $DHouses            = D("houses");
        $DArea              = D("area");
        $DChargeHouse       = D("charge_house");
        $DChargeBill        = D("charge_bill");

        //上一个月
        $lastDate   = date("Y-m",strtotime($year."-".$month."- 1 month"));
        $lastYear   = date("Y",strtotime($lastDate));
        $lastMonth  = date("m",strtotime($lastDate));

        $house_code = $DHouses->getHouseCodeById($house_id);
        $house_info = $DHouses->getHouse($house_code);
        $area_info  = $DArea->getAreaById($house_info['area_id']); //水电气单价

        $water_unit = $area_info['water_unit']; //水费单价
        $gas_unit   = $area_info['gas_unit']; //气费单价
        $energy_stair_arr = explode(",",$area_info['energy_stair']); //阶梯电费单价
        $energy_stair = array();
        foreach ($energy_stair_arr AS $key=>$val) {
            $energy_stair[] = explode("-",$val);//阶梯算法数组
        }

        //修改房屋账单信息的水电气度数
        $SDQdata['end_energy']      = I("end_energy");
        $SDQdata['start_energy']    = I("start_energy");
        $SDQdata['end_water']       = I("end_water");
        $SDQdata['start_water']     = I("start_water");
        $SDQdata['end_gas']         = I("end_gas");
        $SDQdata['start_gas']       = I("start_gas");      
        $SDQdata['extra_public_energy_fee'] = $extra_public_energy_fee;      
        $SDQdata['extra_public_water_fee']  = $extra_public_water_fee;      
        $SDQdata['extra_public_gas_fee']    = $extra_public_gas_fee;      
        $uResult = $DChargeHouse->updateSDQ($charge_house_id,$SDQdata);

        //修改当月录入时的水电气
        $MAmmeterHouse = M("ammeter_house");
        $ammeter_house_where['house_id']    = $house_id;
        $ammeter_house_where['input_year']  = $year;
        $ammeter_house_where['input_month'] = $month;
        $ammeter_result = $MAmmeterHouse->where($ammeter_house_where)->find(); 
        if ( !is_null($ammeter_result) ) {
            $ammeter_house_now_save['modify_time']  = date("Y-m-d H:i:s",time());
            $ammeter_house_now_save['total_energy'] = I("end_energy");
            $ammeter_house_now_save['total_water']  = I("end_water");
            $ammeter_house_now_save['total_gas']    = I("end_gas");
            $ammeter_house_now_save['energy_add']   = I("end_energy") - I("start_energy");
            $ammeter_house_now_save['water_add']    = I("end_water") - I("start_water");
            $ammeter_house_now_save['gas_add']      = I("end_gas") - I("start_gas");
            $MAmmeterHouse->where($ammeter_house_where)->save($ammeter_house_now_save);
        } else {
            $add = array();
            $add['house_id']        = $house_id; 
            $add['input_year']      = $year;
            $add['input_month']     = $month;
            $add['modify_time']     = date("Y-m-d H:i:s",time());
            $add['input_date']      = date("Y-m-d H:i:s",time());
            $add['total_energy']    = I("end_energy");
            $add['total_water']     = I("end_water");
            $add['total_gas']       = I("end_gas");
            $add['status']          = 1;
            $MAmmeterHouse->add($add);
        } 

        $last_ammeter_house_where['house_id']       = $house_id;
        $last_ammeter_house_where['input_year']     = $lastYear;
        $last_ammeter_house_where['input_month']    = $lastMonth;
        //修改上个月的水电气 ， 如果上个月没有录入则录入
        $last_ammeter_result = $MAmmeterHouse->where($last_ammeter_house_where)->find();      
        
        if ( !is_null($last_ammeter_result) ) {
            $last_ammeter_house_save['modify_time']     = date("Y-m-d H:i:s",time());
            $last_ammeter_house_save['total_energy']    = I("start_energy");
            $last_ammeter_house_save['total_water']     = I("start_water");
            $last_ammeter_house_save['total_gas']       = I("start_gas");
            $MAmmeterHouse->where($last_ammeter_house_where)->save($last_ammeter_house_save);
        } else {
            $add = array();
            $add['house_id']        = $house_id; 
            $add['input_year']      = $lastYear;
            $add['input_month']     = $lastMonth;
            $add['modify_time']     = date("Y-m-d H:i:s",time());
            $add['input_date']      = date("Y-m-d H:i:s",time());
            $add['total_energy']    = I("start_energy");
            $add['total_water']     = I("start_water");
            $add['total_gas']       = I("start_gas");
            $add['status']          = 1;
            $MAmmeterHouse->add($add);
        }

        //总电
        $add_energy = $SDQdata['end_energy'] - $SDQdata['start_energy'];
        $total_energy_fee = get_energy_fee($add_energy,$energy_stair);

        //循环获取房间电表的电费
        $room_total_energy_fee = 0;
        foreach ( $room_list AS $k=>$v ) {
            $room_add_energy = $v['end_room_energy'] - $v['start_room_energy'];
            $room_energy_fee = get_energy_fee($room_add_energy,$energy_stair);
            $room_total_energy_fee += $room_energy_fee;
        }

        if ( $house_info['type'] == 1 ) { //按间
            $public_energy_fee = $total_energy_fee - $room_total_energy_fee;//公共区域电费 = 总电费 - 房间总电费
        } else { //按床
            $public_energy_fee = $total_energy_fee; //公共区域电费
        }
        $public_energy_fee += $extra_public_energy_fee;//增加额外公摊电费

        $add_water          = $SDQdata['end_water'] - $SDQdata['start_water'];
        $public_water_fee   = ( $add_water * $water_unit ) + $extra_public_water_fee; //公共水费
        $add_gas            = $SDQdata['end_gas'] - $SDQdata['start_gas'];
        $public_gas_fee     = ( $add_gas * $gas_unit ) + $extra_public_gas_fee; //公共气费
        
        //获取 该月已退g房的账单(非正常账单) 
        //公共区域水电气 - 已退租的水电气 = 该月未退租(正常合同)的租客的公共区域水电气
        //去除了已退租的租客的水电气，剩下的水电气由正常租客均摊
        $abnormal_bill_list = $DChargeBill->showAbnormalChargeBillList($house_code,$year,$month);//非正常账单
        //计算非正常账单的水电气总额

        $abnormal_total_energy_fee  = 0;
        $abnormal_total_water_fee   = 0;
        $abnormal_total_gas_fee     = 0;
        foreach ($abnormal_bill_list AS $k=>$v) {
            $abnormal_total_energy_fee  += $v['energy_fee'];
            $abnormal_total_water_fee   += $v['water_fee'];
            $abnormal_total_gas_fee     += $v['gas_fee'];
        }
        $public_energy_fee  -= $abnormal_total_energy_fee;
        $public_water_fee   -= $abnormal_total_water_fee;
        $public_gas_fee     -= $abnormal_total_gas_fee;

        //总人日
        $sum_person_day = 0;
        foreach($room_list AS $key=>$val){
            $sum_person_day += $val['person_day'];
        }

        $MAmmeterRoom = M("ammeter_room");

        foreach ($room_list AS $key2=>$val2) {
            $val2['energy_fee']     = ceil((($public_energy_fee / $sum_person_day)*$val2['person_day'])*100)/100;
            $val2['water_fee']      = ceil((($public_water_fee / $sum_person_day)*$val2['person_day'])*100)/100;
            $val2['gas_fee']        = ceil((($public_gas_fee / $sum_person_day)*$val2['person_day'])*100)/100;
            $val2['modify_time']    = date("Y-m-d H:i:s",time());

            $add_roomD = $val2['end_room_energy'] - $val2['start_room_energy'];
            //阶梯算法 房间电费
            $val2['room_energy_add'] = $add_roomD;
            $val2['room_energy_fee'] = get_energy_fee($add_roomD,$energy_stair);
            $val2['public_energy_fee'] = $public_energy_fee;
            $val2['total_fee'] = $val2['energy_fee'] + $val2['water_fee'] + $val2['gas_fee'] + $val2['room_energy_fee'] + $val2['rent_fee'] + $val2['service_fee'] + $val2['wgfee_unit'] + $val2['rubbish_fee'] + $val2['wx_fee'] - $val2['favorable'];
            $val2['start_energy']   = $SDQdata['start_energy'];
            $val2['end_energy']     = $SDQdata['end_energy'];
            $val2['start_water']    = $SDQdata['start_water'];
            $val2['end_water']      = $SDQdata['end_water'];
            $val2['start_gas']      = $SDQdata['start_gas'];
            $val2['end_gas']        = $SDQdata['end_gas'];
            $val2['sum_person_day'] = $sum_person_day;
            $val2['extra_public_energy_fee'] = $extra_public_energy_fee;      
            $val2['extra_public_water_fee']  = $extra_public_water_fee;      
            $val2['extra_public_gas_fee']    = $extra_public_gas_fee;  
            $DChargeBill->updateChargeBill($val2['pro_id'],$val2);

            //修改电表
            $ammeter_room_where['room_id'] = $val2['room_id'];
            $ammeter_room_where['input_year'] = $year;
            $ammeter_room_where['input_month'] = $month;
            $ammeter_room_result = $MAmmeterRoom->where($ammeter_room_where)->find();  
            if ( !is_null($ammeter_room_result )) {
                $ammeter_room_now_save['modify_time'] = date("Y-m-d H:i:s",time());
                $ammeter_room_now_save['room_energy'] = $val2['end_room_energy'];
                $MAmmeterRoom->where($ammeter_room_where)->save($ammeter_room_now_save);
            } else {
                $add = array();
                $add['room_id'] = $val2['room_id'];
                $add['input_year'] = $year;
                $add['input_month'] = $month;
                $add['input_date'] = date("Y-m-d H:i:s",time());
                $add['modify_time'] = date("Y-m-d H:i:s",time());
                $add['room_energy'] = $val2['end_room_energy'];
                $add['status'] = 1;
                $MAmmeterRoom->add($add);
            }
            
            //修改上个月的水电气 ， 如果上个月没有录入则录入
            $last_ammeter_room_where['room_id'] = $val2['room_id'];
            $last_ammeter_room_where['input_year'] = $lastYear;
            $last_ammeter_room_where['input_month'] = $lastMonth;
            $last_ammeter_room_result = $MAmmeterRoom->where($last_ammeter_room_where)->find();      

            if ( !is_null($last_ammeter_room_result) ) {
                $last_ammeter_room_save['modify_time'] = date("Y-m-d H:i:s",time());
                $last_ammeter_room_save['room_energy'] = $val2['start_room_energy'];
                $MAmmeterRoom->where($last_ammeter_room_where)->save($last_ammeter_room_save);
            } else {
                $add = array();
                $add['room_id'] = $val2['room_id'];
                $add['input_year'] = $lastYear;
                $add['input_month'] = $lastMonth;
                $add['input_date'] = date("Y-m-d H:i:s",time());
                $add['modify_time'] = date("Y-m-d H:i:s",time());
                $add['room_energy'] = $val2['start_room_energy'];
                $add['status'] = 1;
                $MAmmeterRoom->add($add);
            }
        }

        $this->success("修改成功");
    }


    
    /**
    * [重新生成账单]
    **/
    public function re_create_bill(){
        //判断是否已发送，发送账单就不能重新生成
        $charge_id  = I("charge_id");
        $house_id   = I("house_id");
        $year       = I("year"); //当年
        $month      = I("month"); //当月
        //删除已生成的账单
        $MCharge_bill  = M('charge_bill');
        $MCharge_house = M('charge_house');
        $MPay          = M('pay');
        $DHouses       = D('houses');
        $DChargeBill   = D('charge_bill');
        $house_code    = $DHouses->getHouseCodeById($house_id);
        $flag          = true;
        M()->startTrans();
        $where['id'] = $charge_id;
        $update_result = $MCharge_house->where($where)->save(array('is_create'=>0));
        if ( $update_result != 1 ) {
            $flag = false;
        }
        //删除账单
        $bill_list = $DChargeBill->showChargeBillList($house_code,$year,$month,1);//1.月底水电气
        foreach ($bill_list as $key => $val) {
            if ( $val['pay_status'] == 1 ) {
                $data['msg'] .= $val['realname'].'已支付了,';
                $flag = false;
            }
            $delete_cb_result = $MCharge_bill->where(array('pro_id'=>$val['pro_id']))->delete();
            if ( $delete_cb_result != 1 ) {
                $flag = false;
            }
            $delete_p_result = $MPay->where(array('pro_id'=>$val['pro_id']))->delete();
            if ( $delete_p_result != 1 ) {
                $flag = false;
            }
        }
        if ( $flag ) {
            M()->commit();
            $data['status']  = true;
            $data['msg'] .= '删除成功';
            $this->ajaxReturn($data,'json');
        } else {
            M()->rollback();
            $data['status']  = false;
            $data['msg'] .= '删除失败';
            $this->ajaxReturn($data,'json');
        }
        
        //再执行$this->create_bill
    }

    /**
    * [生成账单]
    **/
    public function create_bill(){
        $charge_id  = I("charge_id");
        $house_id   = I("house_id");
        // 当年
        $year       = I("year"); 
        // 当月
        $month      = I("month"); 
        $now_month  = $year."-".$month;
        $now_date   = date('Y-m-d');
        // 上一个月
        $lastDate   = date("Y-m",strtotime($year."-".$month."- 1 month"));
        $lastYear   = date("Y",strtotime($lastDate));
        $lastMonth  = date("m",strtotime($lastDate));
        // 下一个月
        $nextDate   = date("Y-m",strtotime($year."-".$month."+ 1 month"));
        $nextYear   = date("Y",strtotime($nextDate));
        $nextMonth  = date("m",strtotime($nextDate));
        // 实例化
        $DChargeHouse   = D("charge_house");
        $DChargeBill    = D("charge_bill");
        $DAmmeter       = D("ammeter_house");
        $DHouses        = D("houses");
        $DArea          = D("area");
        // 是否错误
        $error = false;
        // 开启事务
        M()->startTrans();

        // 判断是否已经生成过了
        $is_has_create = M("charge_house")->where(array("house_id"=>$house_id,"input_year"=>$year,"input_month"=>$month,"is_create"=>1))->find();
        if ( !empty($is_has_create) ) {
            die(json_encode(array("info"=>"已生成")));
        }      
        // 房屋编码
        $house_code     = $DHouses->getHouseCodeById($house_id);
        // 该房屋总人数
        $person_count   = $DHouses->getPersonCountByCode($house_code); 
        // 该房屋的房间数量
        $room_count     = $DHouses->getRoomCountByCode($house_code); 
        // 该房屋的床位数量
        $bed_count      = $DHouses->getBedCountByCode($house_code); 
        // 该房屋总人日
        $sum_person_day = $DHouses->getPersonDayCount($house_code,$year,$month);  
        // 房屋信息
        $house_info     = $DHouses->getHouse($house_code); 
        // 水电气单价
        $area_info      = $DArea->getAreaById($house_info['area_id']);
        // 水费单价 
        $water_unit     = $area_info['water_unit']; 
        // 气费单价
        $gas_unit       = $area_info['gas_unit']; 
        // 阶梯电费单价
        $energy_stair_arr = explode(",",$area_info['energy_stair']); 
        // 燃气垃圾费
        $rubbish_fee    = $area_info['rubbish_fee']; 

        foreach ($energy_stair_arr AS $key=>$val) {
            // 阶梯算法数组
            $energy_stair[] = explode("-",$val);
        }
        // 该月的水电气
        $ammeter = $DAmmeter->getAmmeterByIdAndDate($house_id,$year,$month);
        // 上个月的水电气
        $last_ammeter = $DAmmeter->getAmmeterByIdAndDate($house_id,$lastYear,$lastMonth);

        if ( $ammeter['status'] != 1 ) {
            die(json_encode(array("info"=>"该月没录入水电气")));
        }
        if (count($last_ammeter)==0) {
            // 上个月的为空时获取房源初始化水电气
            $last_ammeter['total_water']    = $house_info['init_water'];
            $last_ammeter['total_energy']   = $house_info['init_energy'];
            $last_ammeter['total_gas']      = $house_info['init_gas'];
        }
        // 保存水电气数据到该房屋账单(charge_house)中
        $SDQdata['start_energy']    = $last_ammeter['total_energy'];
        $SDQdata['end_energy']      = $ammeter['total_energy'];
        $SDQdata['start_water']     = $last_ammeter['total_water'];
        $SDQdata['end_water']       = $ammeter['total_water'];
        $SDQdata['start_gas']       = $last_ammeter['total_gas'];
        $SDQdata['end_gas']         = $ammeter['total_gas'];
        // 修改水电气
        $SDQupdateResult = $DChargeHouse->updateSDQ($charge_id,$SDQdata);

        // 房屋总电费
        $add_energy = $ammeter['total_energy'] - $last_ammeter['total_energy'];
        $total_energy_fee = get_energy_fee($add_energy,$energy_stair);

        if ( $house_info['type'] == 1 ) {
            // 房间总电费
            $room_total_energy_fee  = $DHouses->get_room_total_energy_fee($house_code,$year,$month,$energy_stair);
            // 公共区域电费 = 总电费 - 房间总电费
            $public_energy_fee      = $total_energy_fee - $room_total_energy_fee;
        } else {
            $public_energy_fee      = $total_energy_fee; //公共区域电费
        }
        $public_energy_fee = $public_energy_fee<0?0:$public_energy_fee;

        //房屋总水费
        $add_water          = $ammeter['total_water'] - $last_ammeter['total_water'];
        $public_water_fee   = $add_water * $water_unit;
        $public_water_fee   = $public_water_fee<0?0:$public_water_fee;

        //房屋总气费
        $add_gas            = $ammeter['total_gas'] - $last_ammeter['total_gas'];
        $public_gas_fee     = $add_gas * $gas_unit;
        $public_gas_fee     = $public_gas_fee<0?0:$public_gas_fee;

        $DAmmeterRoom   = D("ammeter_room");
        $MContract      = M("contract");
        $DContract      = D("contract");
        //获取 该月已退房的账单(非正常账单) 
        //公共区域水电气 - 已退租的水电气 = 该月未退租(正常合同)的租客的公共区域水电气
        //去除了已退租的租客的水电气，剩下的水电气由正常租客均摊
        $abnormal_bill_list = $DChargeBill->showAbnormalChargeBillList($house_code,$year,$month);//非正常账单
        //计算非正常账单的水电气总额

        $abnormal_total_energy_fee  = 0;
        $abnormal_total_water_fee   = 0;
        $abnormal_total_gas_fee     = 0;
        foreach ($abnormal_bill_list AS $k=>$v) {
            $abnormal_total_energy_fee  += $v['energy_fee'];
            $abnormal_total_water_fee   += $v['water_fee'];
            $abnormal_total_gas_fee     += $v['gas_fee'];
        }
        $public_energy_fee  -= $abnormal_total_energy_fee;
        $public_water_fee   -= $abnormal_total_water_fee;
        $public_gas_fee     -= $abnormal_total_gas_fee;

        //获取该月和此房屋有关联的正常合同列表
        $contract_list = $DContract->getContractListByDateForDailyBill($house_code,$year,$month,1);

        if ( count($contract_list) == 0 ) {
            die(json_encode(array("info"=>"此房屋没有正常合同，非法生成")));
        }

        foreach ( $contract_list AS $key=>$val ) {
            $contract_list[$key]['house_code'] = $house_code;
            $contract_list[$key]['house_id'] = $house_id;
            // 合同开始日
            $ht_start_date  = $val['start_time']; 
            $ht_start_day   = date("d",strtotime($ht_start_date));
            // 合同结束日
            $ht_end_date    = $val['end_time']; 
            // 合同结束月
            $ht_end_year    = date("Y",strtotime($ht_end_date));
            // 合同结束月
            $ht_end_month   = date("m",strtotime($ht_end_date)); 
            // 合同结束日的日
            $ht_end_day     = date("d",strtotime($ht_end_date));

            // 先判断这房屋是按间的还是按床的 房屋类型 1：合租按间 按间的话才获取房间电表 2：合租按床 
            switch ($house_info['type']) {
                case 1:
                    $ammeter_room       = $DAmmeterRoom->getAmmeterRoomByRoomId($val['room_id'],$year,$month);
                    $last_ammeter_room  = $DAmmeterRoom->getAmmeterRoomByRoomId($val['room_id'],$lastYear,$lastMonth);
                    
                    if ( count($last_ammeter_room)==0 ) {
                        // 上个月没有水电气信息则获取合同上的初始化电表
                        $roomD = $MContract->where(array("account_id"=>$val['account_id'],"room_id"=>$val['room_id']))->getField("roomD");
                        $last_ammeter_room['room_energy'] = !empty($roomD)?$roomD:0;
                    }
                    $add_roomD = $ammeter_room['room_energy'] - $last_ammeter_room['room_energy'];
                    $contract_list[$key]['room_energy_add'] = $add_roomD;

                    // 房间电费
                    $contract_list[$key]['room_energy_fee'] = get_energy_fee($add_roomD,$energy_stair);
                    // 止度数
                    $contract_list[$key]['end_room_energy'] = $ammeter_room['room_energy'];
                    // 起度数
                    $contract_list[$key]['start_room_energy'] = $last_ammeter_room['room_energy'];
                    // 物业费/房间数
                    $contract_list[$key]['wg_fee'] = $house_info['fee'] / $room_count; 
                    break;
                case 2:
                    // 物业费/床位数
                    $contract_list[$key]['wg_fee'] = $house_info['fee'] / $bed_count; 
                    break;
            }

            // 获取人日
            $person_day = get_person_day($year,$month,$val);
       
            // 房租到期日
            $rent_date = $val['rent_date'];
            // 缴费周期
            $period = $val['period'];
            $contract_list[$key]['rent']    = $contract_list[$key]['rent'] * $period;
            $contract_list[$key]['fee']     = $contract_list[$key]['fee'] * $period;

            // 房租描述 该年该月.合同开始日~下月
            $rent_date_year     = date("Y",strtotime($rent_date)); 
            $rent_date_month    = date("m",strtotime($rent_date)); 
            $rent_date_day      = date("d",strtotime($rent_date)); 
            // 上次的房租到期日+1天
            $rent_start_date    = date('Y-m-d',strtotime($rent_date.' +1 day'));
            $start_fee_des      = $rent_start_date;
            // 房租描述结束日是 下 $period 个月 的 -1 day
            $rent_end_date      = date('Y-m-d',strtotime($rent_start_date.' +'.$period.' month -1 day'));

            // 如何房租描述结束日 大于 合同结束，则房租描述结束日==合同结束日
            if ( strtotime($rent_end_date) > strtotime($ht_end_date) ) {
                $end_fee_des = $ht_end_date;
            } else {
                $end_fee_des = $rent_end_date;
            }

            $rent_fee_des       = $start_fee_des."到".$end_fee_des."房租";

            // 将支付后修改的房租到期日改成房租结束日
            $rent_date_old = $rent_date;
            $rent_date_to = $end_fee_des;
            //最迟缴费日
            //应该是房租到期日 == 最迟缴费日
            $now_month_days     = date("t",strtotime($year."-".$month));
            $end_fee_des_day    = date("d",strtotime($end_fee_des));
            
            $late_pay_date = $rent_date;
            //应支付是房租到期日的前5天(可设置)
            //如果该日比点击的日期
            $late_pay_date_day  = date("d",strtotime($late_pay_date));
            $should_pay_times   = C("should_pay_times");
            if ( $late_pay_date_day <= $should_pay_times ) {
                $should_pay_date = $late_pay_date;
            } else {
                $should_pay_date = date("Y-m-d",strtotime($late_pay_date."- ".C("should_pay_times")." day"));;
            } 

            //如果合同开始日是1号，缴费周期则判断该月是否等于房租到期日的下个月
            // 举个例子：
            // 正常情况1：
            //  1)9月15号签合同是季度的
            //  2)缴费周期为3
            //  3)则：9月15号~10月14号，10月15号~11月14号，11月15号~12月14号。
            //  4)12月14号是房租到期日
            //  5)12月要缴费，交12月15号~1月14号，1月15号~2月14号，2月15号~3月14号,3月14号是房租到期日
            // 特殊情况1：
            //  1)9月1号签的合同是季度的
            //  2)缴费周期为3
            //  3)则：9月1号~9月30号，10月1号~10月31号，11月1号~11月30号。
            //  4)11月30号是房租到期日

            // 所得结论：到了房租到期日的月份就要缴费了
            // 租客是季付的，应缴费水电日期是读取下个月十号之前
            // 缴费周期大于1
            if ( $period > 1 ) {
                // 缴费周期大于1的，他应该在房租到期日的上一个月就要给房租和服务费
                // 房租到期日的年月 上一个年月
                $last_rent_start_year    = date('Y',strtotime($rent_date.' -1 month'));
                $last_rent_start_month    = date('m',strtotime($rent_date.' -1 month'));
                if ( $year != $last_rent_start_year && $month != $last_rent_start_month ) {
                    $rent_fee_des = "房租到期日在".$rent_date;
                    $contract_list[$key]['rent'] = 0;
                    $contract_list[$key]['fee'] = 0;
                    // 如何季付的话，他只支付水电气，所以不用修改房租到期日
                    $rent_date_to = $rent_date_old;
                }
                $should_pay_date = $late_pay_date = date("Y-m-d",strtotime($now_month . ' +1 month +9 day'));
            }

            //判断房租到期日是否等于合同结束日
            if ( strtotime($rent_date) == strtotime($ht_end_date) ) {
                $rent_fee_des = "合同结束";
                $contract_list[$key]['rent'] = 0;
                $contract_list[$key]['fee'] = 0;
                // 合同结束不修改房租到期日
                $rent_date_to = $rent_date_old;
            }

            //公共区域总电费 public_energy_fee         
            $contract_list[$key]['public_energy_fee']   = $public_energy_fee;
            //总人日total_person_day
            $contract_list[$key]['sum_person_day']      = $sum_person_day;
            //总电费
            $contract_list[$key]['total_energy']        = $total_energy_fee;
            //总水费
            $contract_list[$key]['total_water']         = $public_water_fee;
            //总气费
            $contract_list[$key]['total_gas']           = $public_gas_fee;
            //公摊电费
            $energy_fee = 0;
            $everyday_energy = $public_energy_fee / $sum_person_day; //每天的电费
            $energy_fee = $everyday_energy * $person_day;
            $contract_list[$key]['energy_fee'] = ceil($energy_fee*100)/100;
            //公摊水费
            $water_fee = 0;
            $water_fee = ($public_water_fee / $sum_person_day)*$person_day;
            $contract_list[$key]['water_fee'] = ceil($water_fee*100)/100;
            //公摊气费
            $gas_fee = 0;
            $gas_fee = ($public_gas_fee / $sum_person_day)*$person_day;
            $contract_list[$key]['gas_fee'] = ceil($gas_fee*100)/100;

            if (!empty($public_gas_fee) && $public_gas_fee != 0) {
                // 燃气垃圾费，一般没有气费的同时也没有燃气垃圾费
                $contract_list[$key]['rubbish_fee'] = ceil(($rubbish_fee / $person_count) * 100)/100;
            } else {
                $contract_list[$key]['rubbish_fee'] = 0;
            }
            
            $contract_list[$key]['start_energy']    = $SDQdata['start_energy']; //电始度数
            $contract_list[$key]['end_energy']      = $SDQdata['end_energy']; //电止度数
            $contract_list[$key]['start_water']     = $SDQdata['start_water']; //水始度数
            $contract_list[$key]['end_water']       = $SDQdata['end_water']; //水止度数
            $contract_list[$key]['start_gas']       = $SDQdata['start_gas']; //气始度数
            $contract_list[$key]['end_gas']         = $SDQdata['end_gas']; //气止度数
            $contract_list[$key]['rent_date_old']   = $rent_date_old;//支付前房租到期日
            $contract_list[$key]['rent_date_to']    = $rent_date_to;//支付后房租到期日
            $contract_list[$key]['late_pay_date']   = $late_pay_date;//最迟缴费时间
            $contract_list[$key]['should_pay_date'] = $should_pay_date;//应缴费时间
            $contract_list[$key]['start_fee_des']   = $start_fee_des;//房租描述开始
            $contract_list[$key]['end_fee_des']     = $end_fee_des;//房租描述结束
            $contract_list[$key]['rent_fee_des']    = $rent_fee_des;//房租描述
            $contract_list[$key]['ht_start_date']   = $ht_start_date;//合同租期开始日
            $contract_list[$key]['ht_end_date']     = $ht_end_date;//合同租期结束
            $contract_list[$key]['person_day']      = $person_day;//人日
            $contract_list[$key]['ammeter_room']    = $ammeter_room;//该月水电气信息
            $contract_list[$key]['last_ammeter_room'] = $last_ammeter_room;//上个月水电气信息
            $contract_list[$key]['type'] = 1;//日常
            $contract_list[$key]['bill_type'] = 3;//账单类型 3：日常
            $contract_list[$key]['total_fee'] = $contract_list[$key]['rent'] + $contract_list[$key]['room_energy_fee'] + $contract_list[$key]['energy_fee'] + $contract_list[$key]['water_fee'] + $contract_list[$key]['gas_fee'] + $contract_list[$key]['fee'] + $contract_list[$key]['wg_fee'] + $contract_list[$key]['rubbish_fee'];
            //插入charge_bill中
           
            $result = $DChargeBill->addBill($contract_list[$key],$year,$month);
            
        }

        $result = $DChargeHouse->setIsCreate($charge_id);
file_put_contents("D://rel.txt", json_encode($charge_id), FILE_APPEND);
        if ( !$result ) {
            $error = true;
        }
        if ( $error ) {
            M()->rollback();
            die(json_encode(array("result"=>false)));
        } else {
            M()->commit();
            die(json_encode(array("result"=>true)));
        }
    }

    /**
    * [查看账单]
    **/
    public function bill_list(){
        $_SESSION['P_REFERER'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']; 
        $house_id = I("house_id");
        $DHouses = D("houses");
        $house_code = $DHouses->getHouseCodeById($house_id);

        $DChargeHouse = D("charge_house");
        $DAmmeter = D('ammeter_house');
        $year = date("Y");
        $month = date("m");
        $lastDate = date("Y-m",strtotime($year."-".$month) - 1);
        $lastYear = date("Y",strtotime($lastDate));
        $lastMonth = date("m",strtotime($lastDate));
        // 判断当月是否存在账单
        $DChargeHouse->postOneCharge($house_id,$year,$month); 
        // 判断当月是否存在账单
        $DAmmeter->checkHouseAmmeterByDate($house_id,$year,$month);
        // 判断上个月是否存在账单
        $DChargeHouse->postOneCharge($house_id,$lastYear,$lastMonth);
        // 判断上个月是否存在账单
        $DAmmeter->checkHouseAmmeterByDate($house_id,$lastYear,$lastMonth);
        $charge_list = $DChargeHouse->getChargeList($house_id);

        $this->assign('charge_list',$charge_list);
        $this->assign('house_id',$house_id);

        $this->assign('house_code',$house_code);
        $this->display("Common/header");
        $this->display("Common/nav");
        $this->display("bill-list");
        $this->display("Common/footer");
    }

    /**
    * [查看账单]
    **/
    public function check_SDQ(){

        $DHouses = D("houses");
        $MAmmeterHouse = M("ammeter_house");
        $MAmmeterRoom = M("ammeter_room");
        $house_id = I("house_id");
        if ( !empty($_POST) ) {
            $save['status'] = I("status");
            $save['total_water'] = I("zs");
            $save['total_energy'] = I("zd");
            $save['total_gas'] = I("zq");
            $MAmmeterHouse->where(array("id"=>I("ammeter_house_id")))->save($save);
            foreach ( I("roomd") AS $key=>$val ) {
                $MAmmeterRoom->where(array("id"=>$key))->save(array("room_energy"=>$val));
            }
            $this->success("修改成功",U("Admin/Houses/bill_list",array("house_id"=>$house_id)));
        } else {
            $house_code = $DHouses->getHouseCodeById($house_id);
            $house_info = $DHouses->getHouseById($house_id);
            $room_list = $DHouses->getRoomList($house_code);
            $year = I("year");
            $month = I("month");
            $ammeter_house = $MAmmeterHouse->where(array("house_id"=>$house_id,"input_month"=>$month,"year"=>$year))->find();         
            $ammeter_room = array();
            foreach ( $room_list AS $key=>$val ) {
                $ammeter_room[$key] = $MAmmeterRoom->where(array("room_id"=>$val['id'],"input_month"=>$month,"input_year"=>$year))->find();
                $ammeter_room[$key]['room_code'] = $val['room_code'];
            }

            $this->assign("house_id",$house_id);
            $this->assign("house_info",$house_info);
            $this->assign("ammeter_room",$ammeter_room);
            $this->assign("ammeter_house",$ammeter_house);

            $this->assign("house_code",$house_code);
            $this->assign("year",$year);
            $this->assign("month",$month);
            $this->display("Common/header");
            $this->display("Common/nav");
            $this->display("check_SDQ");
            $this->display("Common/footer");
        }
    }

    /**
    * [ajax查看账单]
    **/
    public function ajax_check_bill(){
        $DHouses = D("houses");
        $house_code = $DHouses->getHouseCodeById(I("post.house_id"));
        $year = I("post.year");
        $month = I("post.month");
        //搜索该年该月已发送的账单
        $MCharge_bill = M("charge_bill");
        $where = array();
        $where['house_code'] = $house_code;
        $where['input_year'] = $year;
        $where['input_month'] = $month;
        $total_count = $MCharge_bill->where($where)->count();
        $where['is_send'] = 1;
        $sended_count = $MCharge_bill->where($where)->count();
        die(json_encode(array("year"=>$year,"month"=>$month,"total_count"=>$total_count,"sended_count"=>$sended_count)));
    }

    /**
    * 设置空房
    **/
    public function be_empty_room(){
        $room_id = I("id");
        $account_id = I("account_id");
        $status = I("status");
        $Mroom = M("room");
        $result = $Mroom->where(array("id"=>$room_id))->save(array("account_id_bak"=>$account_id,"status_bak"=>$status,"account_id"=>0,"status"=>0));
        if ( $result ) {
            $this->success("设置成功!");
        } else {
            $this->error("设置失败!");
        }
    }

    /**
    * 还原空房
    **/
    public function restore_room(){
        $room_id = I("id");
        $Mroom = M("room");
        $account_id_bak = $Mroom->where(array("id"=>$room_id))->getField("account_id_bak");
        $status_bak = $Mroom->where(array("id"=>$room_id))->getField("status_bak");
        if ( 0 == $account_id_bak ) {
            $this->error("还原失败!");
        }
        $result = $Mroom->where(array("id"=>$room_id))->save(array("account_id"=>$account_id_bak,"status"=>$status_bak));
        if ( $result ) {
            $this->success("还原成功!");
        } else {
            $this->error("还原失败!");
        }
    }

    /**
    * [输出房源管理统计]
    **/
    public function all_houses_table(){
        $search = I('search');
        $is_has_flag = strpos($search, '-');
        if ( !empty($search) ) {

            if ( $is_has_flag ) {
                $search_arr = explode('-',$search);
                if ( !is_null($search_arr['0']) ) {
                    $where['h.building'] = $search_arr['0'];
                }
                if ( !is_null($search_arr['1']) ) {
                    $where['h.floor'] = $search_arr['1'];
                }
                if ( !is_null($search_arr['2']) ) {
                    $where['h.door_no'] = $search_arr['2'];
                }
            } else {
                $where['area.area_name|a.realname|h.house_code'] =array(array('LIKE','%'.$search.'%'),array('LIKE','%'.$search.'%'),array('LIKE','%'.$search.'%'),'_multi'=>true);
            }

        }

        $where['area.city_id'] = 2;

        $this->assign('search',$search);
        $MHouses = M('houses');
        $MRoom   = M('room');
        $house_list = $MHouses->select();
        $room_list  = $MRoom
                    ->alias('r')
                    ->field('r.account_id,r.id AS room_id,r.room_code,r.room_sort,h.house_code,h.building,h.floor,h.door_no,a.realname,a.mobile,area.area_name,c.*')
                    ->join('(select id AS ht_id,account_id,room_id,pay_date,create_time,actual_end_time,MAX(start_time) AS start_time,end_time,rent_date,period,deposit,rent AS ht_rent,fee AS ht_fee,contract_status from lewo_contract GROUP BY account_id,room_id,contract_status) AS c ON r.account_id=c.account_id AND r.id=c.room_id AND c.contract_status=1','left')
                    ->join('lewo_houses h ON r.house_code = h.house_code','left')
                    ->join('lewo_account a ON a.id = r.account_id','left')
                    ->join('lewo_area area ON area.id = h.area_id','left')
                    ->where($where)
                    ->order('h.house_code asc,r.room_sort asc')
                    ->select();

        foreach ($room_list as $key => $val) {
            $room_list[$key]['room_count'] = $MRoom->where(array('house_code'=>$val['house_code']))->count();
        }

        $this->assign('room_list',$room_list);
        $this->display('all_houses_table');
    }
}