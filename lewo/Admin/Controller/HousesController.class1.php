<?php
namespace Admin\Controller;
use Think\Controller;
class HousesController extends Controller {
    public function __construct(){
        parent::__construct();
        if ( empty($_SESSION['username']) && ACTION_NAME != 'register') {
            header("Location:".U("Admin/Index/login"));
            die();
        }
    }

    public function index(){
        $where = array();
        $area_id = I("area_id");
        if ( !empty($area_id) ) {
            $where['area_id'] = $area_id;
        }
        $DHouses = D('Houses');
        $_SESSION['house_url'] = U('Admin/Houses/index',$where);
        
        $this->assign("housesList",$DHouses->getHousesList($where));
        $this->display("Common/header");
        $this->display("Common/nav");
        $this->display("houses");
        $this->display("Common/footer");
    }

    /**
    * [»ñÈ¡Ð¡Çø]
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
    * [Ìí¼ÓÐ¡Çø]
    **/
    public function add_area(){
        if ( !empty($_POST) ) {
            $data['area_name'] = I("post.area_name");
            $data['area_description'] = I("post.area_description");
            $data['energy_unit'] = I("post.energy_unit");
            $data['water_unit'] = I("post.water_unit");
            $data['gas_unit'] = I("post.gas_unit");
            $data['energy_stair'] = I("post.energy_stair");
            $data['rubbish_fee'] = I("post.rubbish_fee");
            $result = M("area")->add($data);
            if ( !empty($result) ) {
                $this->success("Ìí¼Ó³É¹¦!",U('Admin/Houses/area'));
            } else {
                $this->error("Ìí¼ÓÊ§°Ü!",U('Admin/Houses/area'));
            }
        } else {
            $this->display("add-area");
            $this->display("Common/footer");
        }
    }   
    /**
    * [ÐÞ¸ÄÐ¡ÇøÐÅÏ¢]
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
                $this->success("ÐÞ¸Ä³É¹¦!",U('Admin/Houses/area'));
            } else {
                $this->error("ÐÞ¸ÄÊ§°Ü!",U('Admin/Houses/area'));
            }
        } else {
            $id  = I("area_id");
            $DArea = D("area");
            $area_info = $DArea->getAreaById($id);
            $this->assign("area_info",$area_info);
            $this->display("update-area");
            $this->display("Common/footer");   
        }
    }

    /**
    * [É¾³ýÐ¡Çø]
    **/
    public function delete_area(){
        $id = I("area_id");
        $result = D('area')->where(array("id"=>$id))->delete();
        if ($result) {
            $this->success('É¾³ý³É¹¦');
        } else {
            $this->error("É¾³ýÊ§°Ü");
        }
    }

    /*
     * [Ìí¼Ó·¿Ô´]
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
                    $this->success("·¿ÎÝÉú³É³É¹¦£¡",U("Admin/Houses/add_room",array('house_code'=>$data['house_code'],'add_type'=>'room')));//·¿ÎÝÌí¼Ó³É¹¦ºóµ½·¿¼äÌí¼ÓÒ³Ãæ
                } elseif ( $data['type'] == 2 ) {
                    $this->success("·¿ÎÝÉú³É³É¹¦£¡",U("Admin/Houses/add_room",array('house_code'=>$data['house_code'],'add_type'=>'bed')));
                }
            } else {
                $this->error("·¿ÎÝ±àÂëÒÑ´æÔÚ£¡");
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
     * [·¿ÎÝÐÞ¸Ä]
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
                $this->success("ÐÞ¸Ä³É¹¦",U("Admin/Houses/index"));
            } else {
                $this->error("ÐÞ¸ÄÊ§°Ü");
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
     * [·¿¼ä/´²Î»Ìí¼Ó]
     **/
    public function add_room(){
        if ( !empty($_POST) ) {
            $Mroom = M('room');
            $upload = new \Think\Upload();// ÊµÀý»¯ÉÏ´«Àà
            $upload->maxSize   =     3145728 ;// ÉèÖÃ¸½¼þÉÏ´«´óÐ¡
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// ÉèÖÃ¸½¼þÉÏ´«ÀàÐÍ
            $upload->rootPath  =     './Uploads/'; // ÉèÖÃ¸½¼þÉÏ´«¸ùÄ¿Â¼
            $upload->savePath  =     ''; // ÉèÖÃ¸½¼þÉÏ´«£¨×Ó£©Ä¿Â¼
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
            if ( !empty(I('post.bed_code')) ) {
                $data['bed_code'] = I('post.bed_code');
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
                $this->success("Éú³É³É¹¦,²¢¼ÌÐøÌí¼Ó");
            } else {
                $this->success("Éú³É³É¹¦",U("Admin/Houses/detail_house",array('house_code'=>$data['house_code'])));
            }
           
        } else {
            $add_type = I("add_type");
            $title = ($add_type=='room')? '·¿¼ä':'´²Î»'; 
            $house_code = I('house_code');

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
     * [·¿¼ä/´²Î»ÐÞ¸Ä]
     **/
    public function update_room(){
        if ( !empty($_POST) ) {
            $id = I("post.id");
            $Mroom = M('room');
            $upload = new \Think\Upload();// ÊµÀý»¯ÉÏ´«Àà
            $upload->maxSize   =     3145728 ;// ÉèÖÃ¸½¼þÉÏ´«´óÐ¡
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// ÉèÖÃ¸½¼þÉÏ´«ÀàÐÍ
            $upload->rootPath  =     './Uploads/'; // ÉèÖÃ¸½¼þÉÏ´«¸ùÄ¿Â¼
            $upload->savePath  =     ''; // ÉèÖÃ¸½¼þÉÏ´«£¨×Ó£©Ä¿Â¼
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
            $this->success("·¿¼äÐÞ¸Ä³É¹¦",U("Admin/Houses/detail_house",array('house_code'=>$data['house_code'])));
            
        } else {
            $id = I('id');
            $house_code = I('house_code');
            $rent_out_type = I('rent_out_type');
            $title = $rent_out_type=='bed'? '´²Î»':'·¿¼ä';

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
     * [·¿ÎÝÏêÏ¸Ò³]
     **/
    public function detail_house(){
        $house_code = I('get.house_code');
        $roomList = D("Houses")->getRoomList($house_code);
        $this->assign('room_list',$roomList);
        $this->assign('house_code',$house_code);
        $this->display("Common/header");
        $this->display("Common/nav");
        $this->display("detail-house");
        $this->display("Common/footer");
    }

    /**
     * [·¿¼ä/´²Î»É¾³ý]
     **/
    public function delete_room(){
        $id = I('id');
        $DRoom = D('Houses');
        $result = $DRoom->deleteRoom($id);
        if ($result) {
            $this->success('É¾³ý³É¹¦');
        } else {
            $this->error("É¾³ýÊ§°Ü");
        }
    }

    /**
    * [²é¿´ÕËµ¥]
    **/
    public function check_bill(){
        $charge_id = I("charge_id");
        $house_id = I("house_id");
        $year = I("year"); //µ±Äê
        $month = I("month"); //µ±ÔÂ
        
        $DHouses = D("houses");
        $DArea = D("area");
        $house_code = $DHouses->getHouseCodeById($house_id);
        $house_info = $DHouses->getHouse($house_code);
        $area_info = $DArea->getAreaById($house_info['area_id']); //Ë®µçÆøµ¥¼Û
        $energy_stair_arr = explode(",",$area_info['energy_stair']); //½×ÌÝµç·Ñµ¥¼Û
        foreach ($energy_stair_arr AS $key=>$val) {
            $energy_stair[] = explode("-",$val);//½×ÌÝËã·¨Êý×é
        }

        $DChargeBill = D("charge_bill");
        $bill_list = $DChargeBill->showChargeBillList($house_code,$year,$month);

        $DChargeHouse = D("charge_house");
        $charge_house_info = $DChargeHouse->getChargeHouseInfo($house_id,$year,$month);

        //Êä³ö×ÜË®µçÆøµÄ¼ÆËã
        //·¿ÎÝ×Üµç·Ñ
        $room_total_energy_fee = 0;
        foreach ( $bill_list AS $k=>$v ) {
            $room_total_energy_fee += $v['room_energy_fee'];
        }

        $add_energy = $charge_house_info['start_energy'] - $charge_house_info['end_energy'];
        $total_energy_fee = get_energy_fee($add_energy,$energy_stair);
        $this->assign("total_energy_fee",$total_energy_fee);

        $this->assign("energy_unit",$energy_unit);
        $this->assign("add_energy",$add_energy);
        $this->assign("room_total_energy_fee",$room_total_energy_fee);
        $this->assign("public_energy_fee",$bill_list[0]['public_energy_fee']);

        $add_water = $charge_house_info['start_water'] - $charge_house_info['end_water'];
        $public_water_fee = $add_water*$area_info['water_unit'];
        $this->assign("water_unit",$area_info['water_unit']);
        $this->assign("add_water",$add_water);
        $this->assign("public_water_fee",$public_water_fee);
        $add_gas = $charge_house_info['start_gas'] - $charge_house_info['end_gas'];
        $public_gas_fee = $add_gas*$area_info['gas_unit'];
        $this->assign("gas_unit",$area_info['gas_unit']);
        $this->assign("add_gas",$add_gas);
        $this->assign("public_gas_fee",$public_gas_fee);
        $this->assign("energy_stair",$area_info['energy_stair']);//½×ÌÝ

        $DAcccount = D("account");
        foreach ( $bill_list AS $key=>$val ) {
            $room_info = array();
            $room_info = $DHouses->getRoom($val['room_id']);
            $realname = '';
            $realname = $DAcccount->getFieldById($val['account_id'],"realname");

            $bill_list[$key]['room_code'] = $room_info['room_code'];
            $bill_list[$key]['bed_code'] = $room_info['bed_code'];
            $bill_list[$key]['realname'] = $realname;
        }
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
    * [·¢ËÍÕËµ¥µ½×â¿ÍÊÖ»ú]
    **/
    public function send_bill(){
        $house_id = I("house_id");
        $year = I("year"); //µ±Äê
        $month = I("month"); //µ±ÔÂ
        $arr_id = explode(",",I("arr_id"));

        $DChargeHouse = D("charge_house");
        $DChargeBill = M("charge_bill");
        $DAccount = D("account");
        //´´À¶½Ó¿Ú
        Vendor('ChuanglanSms.chuanglanSmsApi');
        $clapi  = new \ChuanglanSmsApi();
        $success_id = array();

        foreach ( $arr_id AS $val ) {
            $charge_info = $DChargeBill->field("account_id,total_fee,should_pay_date")->where(array("id"=>$val))->find();
            $mobile = $DAccount->getFieldById($charge_info['account_id'],"mobile");
            $realname = $DAccount->getFieldById($charge_info['account_id'],"realname");
            $msg = 'Ç×°®µÄÀÖÎÑÐ¡Ö÷,ÄãµÄ'.$month.'ÔÂ·ÝÕÊµ¥ÒÑ³öÂ¯,×Ü½ð¶îÎª'.$charge_info['total_fee'].',ÇëÔÚ'.$charge_info['should_pay_date'].'Ç°´¦Àí~,¿ìÀ´²é¿´ http://'.$_SERVER['HTTP_HOST'];
            $result = $clapi->sendSMS($mobile, $msg,'true');
            $result = $clapi->execResult($result);
            if($result[1]==0){
                $DChargeBill->where(array("id"=>$val))->save(array("is_send"=>1));
                $success[$val] = $realname;
            }

            $send_time = date("Y-m-d H:i:s",time());
            M("sms_log")->add(array("mobile"=>$mobile,"message"=>$msg,"send_time"=>$send_time,"result"=>serialize($result)));
        }
        die(json_encode($success));
    }

    /**
    * [ÐÞ¸ÄÕËµ¥]
    **/
    public function update_bill(){
        $charge_house_id = I("charge_house_id");
        $house_id = I("house_id");
        $year = I("year");
        $month = I("month");
        $room_list = I("room_list");
        $DHouses = D("houses");
        $DArea = D("area");
        $DChargeHouse = D("charge_house");
        $DChargeBill = D("charge_bill");
        $lastDate = date("Y-m",strtotime($year."-".$month) - 1);
        $lastYear = date("Y",strtotime($lastDate));
        $lastMonth = date("m",strtotime($lastDate));

        $house_code = $DHouses->getHouseCodeById($house_id);
        $house_info = $DHouses->getHouse($house_code);
        $area_info = $DArea->getAreaById($house_info['area_id']); //Ë®µçÆøµ¥¼Û

        $energy_unit = $area_info['energy_unit']; //µç·Ñµ¥¼Û
        $water_unit = $area_info['water_unit']; //Ë®·Ñµ¥¼Û
        $gas_unit = $area_info['gas_unit']; //Æø·Ñµ¥¼Û
        $energy_stair_arr = explode(",",$area_info['energy_stair']); //½×ÌÝµç·Ñµ¥¼Û
        $energy_stair = array();
        foreach ($energy_stair_arr AS $key=>$val) {
            $energy_stair[] = explode("-",$val);//½×ÌÝËã·¨Êý×é
        }

        //ÐÞ¸Ä·¿ÎÝÕËµ¥ÐÅÏ¢µÄË®µçÆø¶ÈÊý
        $SDQdata['end_energy'] = I("end_energy");
        $SDQdata['start_energy'] = I("start_energy");
        $SDQdata['end_water'] = I("end_water");
        $SDQdata['start_water'] = I("start_water");
        $SDQdata['end_gas'] = I("end_gas");
        $SDQdata['start_gas'] = I("start_gas");      
        $uResult = $DChargeHouse->updateSDQ($charge_house_id,$SDQdata);

        //ÐÞ¸ÄÂ¼ÈëÊÇµÄË®µçÆø¶ÈÊý
        $MAmmeterHouse = M("ammeter_house");
        $ammeter_house_now_where['house_id'] = $house_id;
        $ammeter_house_now_where['input_year'] = $year;
        $ammeter_house_now_where['input_month'] = $month;
        $ammeter_house_now_save['modify_time'] = date("Y-m-d H:i:s",time());
        $ammeter_house_now_save['start_energy'] = I("start_energy");
        $ammeter_house_now_save['start_water'] = I("start_water");
        $ammeter_house_now_save['start_gas'] = I("start_gas");
        $ammeter_house_now_save['energy_add'] = I("start_energy") - I("end_energy");
        $ammeter_house_now_save['water_add'] = I("start_water") - I("end_water");
        $ammeter_house_now_save['gas_add'] = I("start_gas") - I("end_gas");
        
        $ammeter_house_last_where['house_id'] = $house_id;
        $ammeter_house_last_where['input_year'] = $lastyear;
        $ammeter_house_last_where['input_month'] = $lastyear;
        $ammeter_house_last_save['modify_time'] = date("Y-m-d H:i:s",time());
        $ammeter_house_last_save['end_energy'] = I("end_energy");
        $ammeter_house_last_save['end_water'] = I("end_water");
        $ammeter_house_last_save['end_gas'] = I("end_gas");
        
        //·¿ÎÝ×Üµç·Ñ
        $add_energy = $SDQdata['start_energy'] - $SDQdata['end_energy'];
        $total_energy_fee = get_energy_fee($add_energy,$energy_stair);

        //Ñ­»·»ñÈ¡·¿¼äµç±íµÄµç·Ñ
        $room_total_energy_fee = 0;
        foreach ( $room_list AS $k=>$v ) {
            $room_add_energy = $v['start_room_energy'] - $v['end_room_energy'];
            $room_energy_fee = get_energy_fee($room_add_energy,$energy_stair);
            $room_total_energy_fee += $room_energy_fee;
        }

        if ( $house_info['type'] == 1 ) { 
            $public_energy_fee = $total_energy_fee - $room_total_energy_fee;//¹«¹²ÇøÓòµç·Ñ = ×Üµç·Ñ - ·¿¼ä×Üµç·Ñ
        } else {
            $public_energy_fee = $total_energy_fee; //¹«¹²ÇøÓòµç·Ñ
        }

        $add_water  = $SDQdata['start_water'] - $SDQdata['end_water'];
        $public_water_fee = $add_water * $water_unit; //¹«¹²Ë®·Ñ
        $add_gas = $SDQdata['start_gas'] - $SDQdata['end_gas'];
        $public_gas_fee = $add_gas * $gas_unit; //¹«¹²Æø·Ñ

        

        $sum_person_day = 0;
        foreach($room_list AS $key=>$val){
            $sum_person_day += $val['person_day'];
        }

        foreach ($room_list AS $key2=>$val2) {

            $val2['energy_fee'] = ceil((($public_energy_fee / $sum_person_day)*$val2['person_day'])*100)/100;
            $val2['water_fee'] = ceil((($public_water_fee / $sum_person_day)*$val2['person_day'])*100)/100;
            $val2['gas_fee'] = ceil((($public_gas_fee / $sum_person_day)*$val2['person_day'])*100)/100;
            $val2['modify_time'] = date("Y-m-d H:i:s",time());

            $add_roomD = $val2['start_room_energy'] - $val2['end_room_energy'];
            //½×ÌÝËã·¨ ·¿¼äµç·Ñ
            $val2['room_energy_add'] = $add_roomD;
            $val2['room_energy_fee'] = get_energy_fee($add_roomD,$energy_stair);
            $val2['public_energy_fee'] = $public_energy_fee;
            $val2['total_fee'] = $val2['energy_fee']+$val2['water_fee']+$val2['gas_fee']+$val2['room_energy_fee']+$val2['rent_fee']+$val2['service_fee']+$val2['wgfee_unit']+$val2['rubbish_fee']+$val2['wx_fee'];

            $DChargeBill->updateChargeBill($key2,$val2);
            $DChargeBill->updateTotalPersonDay($house_code,$sum_person_day);
        }
        $this->success("ÐÞ¸Ä³É¹¦");
    }

    /**
    * [Éú³ÉÕËµ¥]
    * ¸ù¾Ý·¿¼äÊÇ·ñ´æÔÚaccount_idÀ´´¦Àí
    **/
    public function create_bill(){
        $charge_id = I("charge_id");
        $house_id = I("house_id");
        $year = I("year"); //µ±Äê
        $month = I("month"); //µ±ÔÂ
        $lastDate = date("Y-m",strtotime($year."-".$month) - 1);
        $lastYear = date("Y",strtotime($lastDate));
        $lastMonth = date("m",strtotime($lastDate));

        //µã»÷Éú³ÉÏÈÅÐ¶ÏÊÇ·ñÒÑ¾­Éú³É¹ýÁË
        $is_has_create = M("charge_house")->where(array("house_id"=>$house_id,"input_year"=>$year,"input_month"=>$month,"is_create"=>1))->find();
        if ( !empty($is_has_create) ) {
            die(json_encode(array("info"=>"ÒÑÉú³É")));
        }
        $DChargeHouse = D("charge_house");
        $DHouses = D("houses");
        $DArea = D("area");
        
        $house_code = $DHouses->getHouseCodeById($house_id);
        $person_count = $DHouses->getPersonCountByCode($house_code); //¸Ã·¿ÎÝ×ÜÈËÊý
        $room_count = $DHouses->getRoomCountByCode($house_code); //¸Ã·¿ÎÝµÄ·¿¼äÊýÁ¿
        $bed_count = $DHouses->getBedCountByCode($house_code); //¸Ã·¿ÎÝµÄ´²Î»ÊýÁ¿
        $sum_person_day = $DHouses->getPersonDayCount($house_code,$year,$month); //¸Ã·¿ÎÝ×ÜÈËÈÕ 

        $house_info = $DHouses->getHouse($house_code); 

        $area_info = $DArea->getAreaById($house_info['area_id']); //Ë®µçÆøµ¥¼Û
        $energy_unit = $area_info['energy_unit']; //µç·Ñµ¥¼Û ´íÎóÂß¼­£¬×Üµç·ÑÃ»ÓÐµ¥¼Û£¬¸ù¾Ý½×ÌÝµç·ÑËã
        $water_unit = $area_info['water_unit']; //Ë®·Ñµ¥¼Û
        $gas_unit = $area_info['gas_unit']; //Æø·Ñµ¥¼Û
        $energy_stair_arr = explode(",",$area_info['energy_stair']); //½×ÌÝµç·Ñµ¥¼Û
        $rubbish_fee = $area_info['rubbish_fee']; //È¼ÆøÀ¬»ø·Ñ

        foreach ($energy_stair_arr AS $key=>$val) {
            $energy_stair[] = explode("-",$val);//½×ÌÝËã·¨Êý×é
        }

        $DAmmeter = D("ammeter_house");
        $ammeter = $DAmmeter->getAmmeterByIdAndDate($house_id,$year,$month);
        $last_ammeter = $DAmmeter->getAmmeterByIdAndDate($house_id,$lastYear,$lastMonth);

        if (count($ammeter)==0) die(json_encode(array("info"=>"¸ÃÔÂÃ»Â¼ÈëË®µçÆø")));
        if (count($last_ammeter)==0) {
            //ÉÏ¸öÔÂµÄÎª¿ÕÊ±»ñÈ¡·¿Ô´³õÊ¼»¯Ë®µçÆø
            $last_ammeter['total_water'] = $house_info['init_water'];
            $last_ammeter['total_energy'] = $house_info['init_energy'];
            $last_ammeter['total_gas'] = $house_info['init_gas'];
        }
        //±£´æË®µçÆøÊý¾Ýµ½¸Ã·¿ÎÝÕËµ¥(charge_house)ÖÐ
        $SDQdata['start_energy'] = $ammeter['total_energy'];
        $SDQdata['end_energy'] = $last_ammeter['total_energy'];
        $SDQdata['start_water'] = $ammeter['total_water'];
        $SDQdata['end_water'] = $last_ammeter['total_water'];
        $SDQdata['start_gas'] = $ammeter['total_gas'];
        $SDQdata['end_gas'] = $last_ammeter['total_gas'];
        $SDQupdateResult = $DChargeHouse->updateSDQ($charge_id,$SDQdata);//ÐÞ¸ÄË®µçÆø

        //·¿ÎÝ×Üµç·Ñ
        $add_energy = $ammeter['total_energy'] - $last_ammeter['total_energy'];
        $total_energy_fee = get_energy_fee($add_energy,$energy_stair);


        if ( $house_info['type'] == 1 ) {
            //·¿¼ä×Üµç·Ñ
            $room_total_energy_fee = $DHouses->get_room_total_energy_fee($house_code,$year,$month,$energy_stair);
            $public_energy_fee = $total_energy_fee - $room_total_energy_fee;//¹«¹²ÇøÓòµç·Ñ = ×Üµç·Ñ - ·¿¼ä×Üµç·Ñ
        } else {
            $public_energy_fee = $total_energy_fee; //¹«¹²ÇøÓòµç·Ñ
        }

        //·¿ÎÝ×ÜË®·Ñ
        $add_water = $ammeter['total_water'] - $last_ammeter['total_water'];
        $public_water_fee = $add_water * $water_unit;

        //·¿ÎÝ×ÜÆø·Ñ
        $add_gas = $ammeter['total_gas'] - $last_ammeter['total_gas'];
        $public_gas_fee = $add_gas * $gas_unit;

        //»ñÈ¡·¿¼äµç±íÐÅÏ¢ Ã»ÓÐÉÏ¸öÔÂµÄÔò»ñÈ¡ºÏÍ¬ÖÐµÄ³õÊ¼»¯µç±í
        $DAmmeterRoom = D("ammeter_room");
        $MContract = M("contract");
        $DContract = D("contract");

        //$room_list = $DHouses->getRoomList($house_code);//·¿¼äÁÐ±í »ñÈ¡·¿¼äµÄÎÊÌâÊÇÓÐÎÊÌâµÄ£¬¼ÙÈç¸Ã·¿¼äÓÐÍË·¿µÈ²Ù×÷£¬¾Í²»ÄÜ»ñÈ¡ÍË·¿µÄ×â¿ÍµÄË®µçÆøÐÅÏ¢ÁË¸Ä³É»ñÈ¡ºÏÍ¬ÁÐ±í
        //»ñÈ¡ºÏÍ¬ÁÐ±í
        $contract_list = $DContract->getContractListByDateForDailyBill($house_code,$year,$month);

        foreach ( $contract_list AS $key=>$val ) {
            $ht_start_date = $val['start_time']; //ºÏÍ¬¿ªÊ¼ÈÕ
            $ht_start_day = date("d",strtotime($ht_start_date));
            $ht_end_date = $val['end_time']; //ºÏÍ¬½áÊøÈÕ
            $ht_actual_end_time = $val['actual_end_time']; //Êµ¼ÊÍË·¿ÈÕÆÚ ÅÐ¶ÏÊÇ·ñ´æÔÚÒÑ¾­ÍË·¿ÁË

            $ht_end_year = date("Y",strtotime($ht_end_date)); //ºÏÍ¬½áÊøÔÂ
            $ht_end_month = date("m",strtotime($ht_end_date)); //ºÏÍ¬½áÊøÔÂ
            $ht_end_day = date("d",strtotime($ht_end_date));//ºÏÍ¬½áÊøÈÕµÄÈÕ
            $click_day = date("d",time());
            //ÅÐ¶Ï$val['contract_status'] != 1 Ê±ÊÇ·ÇÕý³£ºÏÍ¬×´Ì¬ ²»·¢ËÍÕËµ¥
            //ºÏÍ¬×´Ì¬ 1.Õý³£,2.»»·¿,3×ª×â 4.Õý³£ÍË×â 5.Î¥Ô¼ÍË×â
            //charge_bill type 1.ÔÂµ×Ë®µçÆø2.ÍË×âË®µçÆø½áËã 3.»»×âË®µçÆø½áËã 4.×ª×âË®µçÆø½áËã
            switch ( $val['contract_status'] ) {
                case 1:
                    $contract_list[$key]['type'] = 1;//Õý³£Çé¿ö
                    break;
                case 2:
                    $contract_list[$key]['type'] = 3;//3.»»·¿
                    break;
                case 3:
                    $contract_list[$key]['type'] = 4;//4.×ª·¿
                    break;
                case 4:
                case 5:
                    $contract_list[$key]['type'] = 2;//2.ÍË·¿
                    break;
            }

            //ÏÈÅÐ¶ÏÕâ·¿ÎÝÊÇ°´¼äµÄ»¹ÊÇ°´´²µÄ ·¿ÎÝÀàÐÍ 1£ººÏ×â°´¼ä °´¼äµÄ»°²Å»ñÈ¡·¿¼äµç±í 2£ººÏ×â°´´² 
            switch ($house_info['type']) {
                case 1:
                    $ammeter_room = $DAmmeterRoom->getAmmeterRoomByRoomId($val['room_id'],$year,$month);
                    $last_ammeter_room = $DAmmeterRoom->getAmmeterRoomByRoomId($val['room_id'],$lastYear,$lastMonth);
                    
                    if ( count($last_ammeter_room)==0 ) {
                        //ÉÏ¸öÔÂÃ»ÓÐË®µçÆøÐÅÏ¢Ôò»ñÈ¡ºÏÍ¬ÉÏµÄ³õÊ¼»¯µç±í
                        $roomD = $MContract->where(array("account_id"=>$val['account_id'],"room_id"=>$val['room_id']))->getField("roomD");
                        $last_ammeter_room['room_energy'] = !empty($roomD)?$roomD:0;
                    }
                    $add_roomD = $ammeter_room['room_energy'] - $last_ammeter_room['room_energy'];
                    $contract_list[$key]['room_energy_add'] = $add_roomD;

                    //·¿¼äµç·Ñ
                    $contract_list[$key]['room_energy_fee'] = get_energy_fee($add_roomD,$energy_stair);
                    $contract_list[$key]['start_room_energy'] = $ammeter_room['room_energy'];
                    $contract_list[$key]['end_room_energy'] = $last_ammeter_room['room_energy'];

                    $contract_list[$key]['wg_fee'] = $house_info['fee'] / $room_count; //ÎïÒµ·Ñ/·¿¼äÊý
                    $contract_list[$key]['rubbish_fee'] = $rubbish_fee / $room_count;
                    break;
                
                case 2:
                    $contract_list[$key]['wg_fee'] = $house_info['fee'] / $bed_count; //ÎïÒµ·Ñ/´²Î»Êý
                    $contract_list[$key]['rubbish_fee'] = $rubbish_fee / $bed_count;
                    break;
            }

            //ÈËÈÕ ÐèÒª¼ÆËã×â¿ÍÉêÇëµÄÈÕÊý
            $person_day = 0;
            $ht_person = $DContract->getPersonCount($val['account_id'],$val['room_id']);//ºÏÍ¬ÈËÊý
            //ÅÐ¶Ï×â¿ÍÊÇ·ñµ±ÔÂÈë×¡
            $result = $DContract->isDateCheckInByDate($val['account_id'],$val['room_id'],$year,$month);

            if ( $result ) {
                //ÊÇÕâ¸öÔÂÈë×¡Ôò£¬ÈËÈÕ»ñÈ¡µÄÊÇ£¬×âÆÚ¿ªÊ¼µ½ÔÂÄ©µÄÈÕÊý
                $start_time =  $ht_start_date;//ºÏÍ¬¿ªÊ¼Ê±¼ä
                $firstday = date('Y-m-01', strtotime($year."-".$month)); //¸ÃÔÂµÚÒ»Ìì
                $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day")); //¸ÃÔÂ×îºóÒ»Ìì
                $person_day = round((strtotime($lastday)-strtotime($start_time))/86400)+1;      
            } else {
                //ÍË·¿ÁË ÅÐ¶ÏÊÇ·ñÕâ¸öÔÂÍËµÄ
                $ht_actual_end_year = date("Y",strtotime($ht_actual_end_time));
                $ht_actual_end_month = date("m",strtotime($ht_actual_end_time));
                if ( $ht_actual_end_time != 0 && $year == $ht_actual_end_year && $month == $ht_actual_end_month) {
                    //Õâ¸öÔÂ¾ÍÊÇÍË·¿µÄÔÂÊý ÄÇÃ´Õâ¸öÔÂµÄÈËÈÕ¾ÍÊÇµ½ÕâÒ»Ìì
                    $person_day = date("d",strtotime($ht_actual_end_time));
                    $rent_fee_des = "×â¿ÍÍË·¿";
                    $contract_list[$key]['rent'] = 0;
                    $contract_list[$key]['room_fee'] = 0;
                } else {
                    //²»ÊÇÕâ¸öÔÂÈë×¡Ò²²»ÊÇÕâ¸öÔÂÍË·¿µÄ£¬Ôò»ñÈ¡Õâ¸öÔÂµÄÈÕÊý
                    $person_day = date("t",strtotime($year."-".$month));
                }   
            }

            $person_day*=$ht_person;

            //¹«¹²ÇøÓò×Üµç·Ñ public_energy_fee         
            $contract_list[$key]['public_energy_fee'] = $public_energy_fee;
            //×ÜÈËÈÕtotal_person_day
            $contract_list[$key]['sum_person_day'] = $sum_person_day;
            //×Üµç
            $contract_list[$key]['total_energy'] = $ammeter['total_energy'];
            //×ÜË®
            $contract_list[$key]['total_water'] = $ammeter['total_water'];
            //×ÜÆø
            $contract_list[$key]['total_gas'] = $ammeter['total_gas'];
            //¹«Ì¯µç·Ñ
            $energy_fee = 0;
            $energy_fee = ($public_energy_fee / $sum_person_day)*$person_day;
            $contract_list[$key]['energy_fee'] = ceil($energy_fee*100)/100;
            //¹«Ì¯Ë®·Ñ
            $water_fee = 0;
            $water_fee = ($public_water_fee / $sum_person_day)*$person_day;
            $contract_list[$key]['water_fee'] = ceil($water_fee*100)/100;
            //¹«Ì¯Æø·Ñ
            $gas_fee = 0;
            $gas_fee = ($public_gas_fee / $sum_person_day)*$person_day;
            $contract_list[$key]['gas_fee'] = ceil($gas_fee*100)/100;


            //·¿×âÃèÊö ¸ÃÄê¸ÃÔÂ.ºÏÍ¬¿ªÊ¼ÈÕ~ÏÂÔÂ.ºÏÍ¬½áÊøÈÕ     
            $start_fee_des = $year."-".$month."-".$ht_start_day;
            $end_fee_des = date("Y-m-d",strtotime($start_fee_des."+1 month -1 day"));
            $rent_fee_des = $start_fee_des."µ½".$end_fee_des."·¿×â";
            //Èç¹ûÕâ¸öÔÂÊÇºÏÍ¬½áÊøÄêÔÂÒ»Ñù£¬ÔòÕâ¸öÔÂµÄ·¿×â²»ÓÃ½»£¬³ý·ÇÊÇ1ºÅ ÒòÎª1ºÅµÄ·¿×âÊÇ7ÔÂ1ºÅµ½7ÔÂ31ºÅ¡£
            if ( $ht_end_year == $year && $ht_end_month == $month && $ht_end_day != 1 ) {
                $rent_fee_des = "¸ÃÔÂÎªºÏÍ¬½áÊø";
                $contract_list[$key]['rent'] = 0;
                $contract_list[$key]['room_fee'] = 0;
            }

            //×î³Ù½É·ÑÈÕ
            $now_month_days = date("t",strtotime($year."-".$month));
            $end_fee_des_day = date("d",strtotime($end_fee_des));
            //Èç¹ûµ±ÔÂ½áÊøÈÕµÈÓÚ¸ÃÔÂµÄÈÕÊý£¬Ö¤Ã÷ÁËÕâ¸ö½áÊøÈÕÊÇ×îºóÒ»Ìì
            if ( $end_fee_des_day == 31 || $end_fee_des_day <= $click_day ) {
                $late_pay_day = $click_day+1;
            } else {
                $late_pay_day = $end_fee_des_day;
            }
            if ( $late_pay_day - C("should_pay_times") <= $click_day ) {
                $should_pay_day = $click_day+1;
            } else {
                $should_pay_day = $late_pay_day - C("should_pay_times");
            }
            $late_pay_date = $year."-".$month."-".$late_pay_day;
            $should_pay_date = $year."-".$month."-".$should_pay_day;

            $contract_list[$key]['late_pay_date'] = $late_pay_date;//×î³Ù½É·ÑÊ±¼ä
            $contract_list[$key]['should_pay_date'] = $should_pay_date;//Ó¦½É·ÑÊ±¼ä
            $contract_list[$key]['start_fee_des'] = $start_fee_des;//·¿×âÃèÊö¿ªÊ¼
            $contract_list[$key]['end_fee_des'] = $end_fee_des;//·¿×âÃèÊö½áÊø
            $contract_list[$key]['rent_fee_des'] = $rent_fee_des;//·¿×âÃèÊö
            $contract_list[$key]['ht_start_date'] = $ht_start_date;//ºÏÍ¬×âÆÚ¿ªÊ¼ÈÕ
            $contract_list[$key]['ht_end_date'] = $ht_end_date;//ºÏÍ¬×âÆÚ½áÊø
            $contract_list[$key]['person_day'] = $person_day;//ÈËÈÕ
            $contract_list[$key]['ammeter_room'] = $ammeter_room;//¸ÃÔÂË®µçÆøÐÅÏ¢
            $contract_list[$key]['last_ammeter_room'] = $last_ammeter_room;//ÉÏ¸öÔÂË®µçÆøÐÅÏ¢

            $contract_list[$key]['total_fee'] = $contract_list[$key]['rent']+$contract_list[$key]['room_energy_fee']+$contract_list[$key]['energy_fee']+$contract_list[$key]['water_fee']+$contract_list[$key]['gas_fee']+$contract_list[$key]['room_fee']+$contract_list[$key]['wg_fee']+$contract_list[$key]['rubbish_fee'];
            //²åÈëcharge_billÖÐ
            $DChargeBill = D("charge_bill");
            $result = $DChargeBill->addBill($contract_list[$key],$year,$month);
        }
        $DChargeHouse->setIsCreate($charge_id);
        die(json_encode(array("result"=>$result)));
    }

    /**
    * [²é¿´ÕËµ¥]
    **/
    public function bill_list(){
        $house_id = I("house_id");
        $DHouses = D("houses");
        $house_code = $DHouses->getHouseCodeById($house_id);

        $DChargeHouse = D("charge_house");
        $year = date("Y");
        $month = date("m");
        $DChargeHouse->createOneCharge($house_id,$year,$month);//ÅÐ¶Ïµ±ÔÂÊÇ·ñ´æÔÚÕËµ¥
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
    * [²é¿´ÕËµ¥]
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
            $this->success("ÐÞ¸Ä³É¹¦",U("Admin/Houses/bill_list",array("house_id"=>$house_id)));
        } else {
            $house_code = $DHouses->getHouseCodeById($house_id);
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

}