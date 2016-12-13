<?php
namespace Admin\Model;
use Think\Model;
/**
* [房源数据层]
*/
class HousesModel extends Model{
	/**
	 * [获取房源列表]
	 **/
	public function getHousesList($where){
		$year = date("Y",time());
		$month = date("m",time());
		$MSteward = M('admin_user');
		$MRoom = M("room");
		$MArea = M("area");
		$MCharge_bill = M("charge_bill");
		$MChargeHouse = M("charge_house");
		$houses = M('houses')
				->alias('h')
				->field("h.id AS house_id,h.area_id,h.house_code,h.type,h.steward_id,h.create_time,h.floor,h.door_no,h.building,h.region_id,area.area_name,area.city_id")
				->join('lewo_area area ON area.id=h.area_id')
				->where($where)
				->select();

		$MAmmeterhouse = M("ammeter_house");
		$where = array();
		foreach($houses AS $key=>$val){
			$houses[$key]['city_id'] = C('city_id')[$val['city_id']];
			$yz_count = $MRoom->where(array('house_code'=>$val['house_code'],'status'=>C('room_yz')))->count();//已租数量
			if ($yz_count>0) {
				$houses[$key]['is_checkin'] = true;
			} else {
				$houses[$key]['is_checkin'] = false;
			}
			$is_send = $MChargeHouse->where(array("house_id"=>$val['house_id'],"input_year"=>$year,"input_month"=>$month,"is_send"=>1))->count();
			if ( $is_send > 0 ){
				$houses[$key]['is_send'] = true;
			} else {
				$houses[$key]['is_send'] = false;
			}

			$steward_info = array();
			$steward_info = $MSteward->where(array('id'=>$val['steward_id']))->find();
			$houses[$key]['steward_nickname'] = $steward_info['nickname'];
			$houses[$key]['steward_mobile'] = $steward_info['mobile'];

			$where['cb.house_code'] = $val['house_code'];
			$where['p.input_year'] = $year;
			$where['p.input_month'] = $month;
			$total_count = $MCharge_bill
							->alias('cb')
							->join('lewo_pay p ON p.pro_id=cb.pro_id')
							->where($where)
							->count();
			$where['p.is_send'] = 1;

			$sended_count = $MCharge_bill
							->alias('cb')
							->join('lewo_pay p ON p.pro_id=cb.pro_id')
							->where($where)
							->count();
			$houses[$key]['total_count'] = $total_count;//总数量
			$houses[$key]['sended_count'] = $sended_count;//已发送数量
		}

		return $houses;
	}

	/**
	 * [获取房屋信息]
	 **/
	public function getHouse($house_code){
		return $this->where(array('house_code'=>$house_code))->find();
	}
	/**
	 * [获取房屋信息]
	 **/
	public function getHouseById($house_id){
		return $this->where(array('id'=>$house_id))->find();
	}

	/**
	 * [获取全部房间/床位信息]
	 **/
	public function getAllRoom(){
		$MRoom = M('room');
		return $MRoom->select();
	}

	/**
	 * [获取房源中的房间/床位]
	 **/
	public function getRoomList($house_code){
		$MRoom = M("room");
		$roomList = $MRoom->where(array('house_code'=>$house_code,'is_show'=>1))->order('room_sort asc')->select();
		$DContract = M("contract");
		$DAccount = D("account");
		foreach($roomList AS $key=>$val){		
			$account_info = $DAccount->getInfoById($val['account_id']);
			$contract_list = $DContract->where(array("account_id"=>$val['account_id'],"room_id"=>$val['id'],"pay_status"=>1,"contract_status"=>1))->find();
			$roomList[$key]['realname'] = $account_info['realname'];
			$roomList[$key]['mobile'] = $account_info['mobile'];
			$roomList[$key]['contract_rent'] = $contract_list['rent'];
			if (!empty($val['bed_code'])) {
				$roomList[$key]['rent_out_type'] = 'bed';
				$roomList[$key]['rent_out_type_name'] = '床';
			} else {
				$roomList[$key]['rent_out_type'] = 'room';
				$roomList[$key]['rent_out_type_name'] = '间';
			}
		}
		return $roomList;
	}

	/**
	 * [获取房间/床位信息]
	 **/
	public function getRoom($id){
		$MRoom = M("room");
		return $MRoom->where(array('id'=>$id))->find();
	}
	/**
	 * [删除房间/床位]
	 **/
	public function deleteRoom($id){
		$MRoom = M("room");
		$is_has_account = $MRoom->where(array('id'=>$id))->getField('account_id');
		if ( !empty($is_has_account) ) {
			return false;
		} else {
			return $MRoom->where(array('id'=>$id))->save(array('is_show'=>0));
		}
	}
	/**
	 * [获取房屋编码]
	 **/
	public function getHouseCodeById($hosue_id){
		return $this->where(array('id'=>$hosue_id))->getField("house_code");
	}
	/**
	 * [获取房屋ID]
	 **/
	public function getHouseIdByCode($house_code){
		return $this->where(array('house_code'=>$house_code))->getField("id");
	}
	/**
	* [查该该房屋租了多少人][房屋总人数]
	**/
	public function getPersonCountByCode($house_code){
		return M("room")->where(array("status"=>2,"house_code"=>$house_code))->count();
	}
	/**
	* [查看该房屋有多少间房间]
	**/
	public function getRoomCountByCode($house_code){
		$room_count = M("room")->where(array("house_code"=>$house_code,"room_type"=>1))->count();

		return $room_count;
	}
	/**
	* [查看该房屋有多少间床位]
	**/
	public function getBedCountByCode($house_code){
		$bed_count = M("room")->where(array("house_code"=>$house_code,"room_type"=>2))->count();

		return $bed_count;
	}
	/**
	* [查该该房间租了多少人]
	**/
	public function getRoomPersonCountByCode($room_code){
		return M("room")->where(array("status"=>2,"room_code"=>$room_code))->count();
	}
	/**
	* [获取该房间某年某月的总人日]
	**/
	public function getPersonDayCount($house_code,$year,$month){
		$DContract = D("contract");
		//获取与该月有关联的房间
		$contract_list = $DContract->getContractListByDateForDailyBill($house_code,$year,$month,1);

		$sum_person_day = 0;
		foreach ($contract_list AS $key=>$val) {
            $person_day = 0;
            $person_day = get_person_day($year,$month,$val);//获取人日
	        $sum_person_day += $person_day;
		}	
		return $sum_person_day;
	}
	/**
	* [针对退房-获取该房间的总人日]
	* [获取该房间某年某月的总人日]
	* @param house_code 房间编号
	* @param appoint_time 约定时间
	**/
	public function TFgetPersonDayCount($house_code,$appoint_time){
		$appoint_year = date("Y",strtotime($appoint_time));
		$appoint_month = date("m",strtotime($appoint_time));
		$appoint_day = date("d",strtotime($appoint_time));
		$DContract = D("contract");
		//获取与该月有关联的房间
		$contract_list = $DContract->getContractListByDateForDailyBill($house_code,$appoint_year,$appoint_month);
		$sum_person_day = 0;
		foreach ($contract_list AS $key=>$val) {
			$person_day = 0;
	        $person_day = get_person_day($appoint_year,$appoint_month,$val,$appoint_time);//获取人日
	        $sum_person_day += $person_day;
		}	
		return $sum_person_day;
	}

	/**
	* [生成账单 要获取公共区域的费用则要获取总的房间电费]
	* @param house_code 房间编号
	* @param year 年
	* @param month 月
	* @param energy_stair 阶梯算法
	* @return 总电费
	**/
	public function get_room_total_energy_fee($house_code,$year,$month,$energy_stair){	
		//上一个月
        $lastDate = date("Y-m",strtotime($year."-".$month."- 1 month"));
        $lastYear = date("Y",strtotime($lastDate));
        $lastMonth = date("m",strtotime($lastDate));

		$DAmmeterRoom = D("ammeter_room");
        $MContract = M("contract");
        $DContract = D("contract");
        $MRoom = M("room");
		$room_list = $MRoom->where(array("house_code"=>$house_code))->select();

		$room_total_energy_fee = 0;
		foreach( $room_list AS $key=>$val ){
			$ammeter_room = $DAmmeterRoom->getAmmeterRoomByRoomId($val['id'],$year,$month);
            $last_ammeter_room = $DAmmeterRoom->getAmmeterRoomByRoomId($val['id'],$lastYear,$lastMonth);
            $room_list[$key]['ammeter_room'] = $ammeter_room;
            $room_list[$key]['last_ammeter_room'] = $last_ammeter_room;
            if ( count($last_ammeter_room)==0 ) {
                //上个月没有水电气信息则获取合同上的初始化电表
                $roomD = $MContract->where(array("account_id"=>$val['account_id'],"room_id"=>$val['room_id']))->getField("roomD");
                $last_ammeter_room['room_energy'] = !empty($roomD)?$roomD:0;
            }
            $add_roomD = $ammeter_room['room_energy'] - $last_ammeter_room['room_energy'];

            //房间电费
            $room_energy_fee = get_energy_fee($add_roomD,$energy_stair);
            $room_total_energy_fee += $room_energy_fee;

		}
		return $room_total_energy_fee;
	}

	/**
	* [获取房间楼层]
	**/
	public function getBuilding($house_code){
		return $this->field("area_name,building,floor,door_no")->where(array('house_code'=>$house_code))->find();
	}
}

?>