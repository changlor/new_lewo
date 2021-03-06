<?php
namespace Admin\Model;
use Think\Model;
/**
* [房间电表数据层]
*/
class AmmeterRoomModel extends Model{
	/**
	* [检查当月是否生成水电气，不存在则生成一条数据，状态为0]
	**/
	public function checkRoomAmmeterByDate($room_id,$house_id,$year,$month){
		$result = $this->where(array('room_id'=>$room_id,'house_id'=>$house_id,'input_year'=>$year,'input_month'=>$month))->find();
		if ( empty($result) ) {
			$data['status'] = 0;
			$data['room_id'] = $room_id;
			$data['house_id'] = $house_id;
			$data['input_year'] = $year;
			$data['input_month'] = $month;
			$id = $this->add($data);
			return $id;
		} else {
			return false;
		}
	}
	
	/**
	* [根据房屋id、年月获取房间电表]
	**/
	public function getAmmeterRoom($house_id,$year,$month){
		$aRoom_list = M("ammeter_room")->field("lewo_room.room_code,lewo_room.bed_code,lewo_ammeter_room.*")->join("lewo_room ON lewo_room.id = lewo_ammeter_room.room_id")->where(array('house_id'=>$house_id,'input_year'=>$year,'input_month'=>$month))->select();

		return $aRoom_list;
	}
	/**
	* [根据房间id、年月获取房间电表]
	**/
	public function getAmmeterRoomByRoomId($room_id,$year,$month){
		$aRoom_list = M("ammeter_room")->field("lewo_room.room_code,lewo_room.bed_code,lewo_ammeter_room.*")->join("lewo_room ON lewo_room.id = lewo_ammeter_room.room_id")->where(array('room_id'=>$room_id,'input_year'=>$year,'input_month'=>$month))->find();

		return $aRoom_list;
	}
	/**
	* [完成修改水电气]
	**/
	public function updateAmmeterRoomById($id,$val){
		$data['status'] = 1;
		$data['room_energy'] = $val;
		$data['steward_id'] = $_SESSION['steward_id'];
		$data['input_date'] = date("Y-m-d H:i:s",time());
		return $this->where(array('id'=>$id))->save($data);
	}
	/**
	* [获取最新的一条]
	**/
	public function getFirstInfo($room_id){
		return $this->where(array("room_id"=>$room_id))->order("input_month desc,input_year desc")->find();
	}
}

?>