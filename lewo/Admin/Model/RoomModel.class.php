<?php
namespace Admin\Model;
use Think\Model;
/**
* [房间数据层]
*/
class RoomModel extends Model{
	/**
	* [入住成功后修改房间状态]
	**/
	public function setRoomStatus($id,$status){
		return $this->where(array("id"=>$id))->save(array("status"=>$status));
	}
	/**
	* [入住成功后修改房间居住人]
	**/
	public function setRoomPerson($id,$account_id){
		return $this->where(array("id"=>$id))->save(array("account_id"=>$account_id));
	}
	/**
	 * [获取房屋编码]
	 **/
	public function getHouseCodeById($room_id){
        return $this->where(array("id"=>$room_id))->getField("house_code");
	}
	/**
	 * [获取房间编码]
	 **/
	public function getRoomCodeById($room_id){
        return $this->where(array("id"=>$room_id))->getField("room_code");
	}
	/**
	 * [获取床位编码]
	 **/
	public function getBedCodeById($room_id){
        return $this->where(array("id"=>$room_id))->getField("bed_code");
	}
	/**
	* [获取room的id][一个]
	**/
	public function getRoomIdByCode($room_code){
		return $this->where(array("room_code"=>$room_code))->getField("id");
	}
	/**
	* [获取room的id][多个]
	**/
	public function getRoomsIdByCode($room_code){
		$where['room_code'] = array("LIKE","%".$room_code."%");
		$list = $this->field("id")->where($where)->select();
		$id_str = "";
		foreach( $list AS $key=>$val ){
			$id_str .= $val['id'].",";
		}
		$id_str = substr($id_str, 0, -1);
		return $id_str;
	}
	/**
	* [获取房间部分信息]
	**/
	public function getRoomInfoById($room_id){
		return $this->field("room_code,bed_code,house_code")->where(array("id"=>$room_id))->find();
	}
	/**
	* [获取房间信息]
	**/
	public function getRoomById($room_id){
		return $this->where(array("id"=>$room_id))->find();
	}
}

?>