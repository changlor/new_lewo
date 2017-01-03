<?php
namespace Home\Model;
use Think\Model;
/**
* [房间数据层]
*/
class RoomModel extends BaseModel {
	protected $table;
	protected $tableName = 'room';

	public function __construct()
    {
        parent::__construct();
    	$this->table = M($this->tableName);
    }

    public function select($where, $field)
	{
		$field = empty($field) ? '' : $field;
		$where = empty($where) ? '' : $where;
		return $this->table->field($field)->where($where);
	}

	public function update($where)
    {
        $field = empty($field) ? '' : $field;
        $where = empty($where) ? '' : $where;
        $field = is_array($field) ? implode(',', $field) : $field;
        return $this->table->where($where);
    }

	public function updateRoom($where, $updateInfo)
    {
        return $this->update($where)->save($updateInfo);
    }

    public function selectRoom($where, $field)
    {
    	return $this->select($where, $field)->find();
    }

	public function selectField($where, $field)
	{
		return $this->select($where)->getField($field);
	}

    public function insert($contract)
    {
    	return $this->table->add($contract);
    }



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
	* [根据用户id获取租客房间信息][获取入住的信息]
	**/
	public function getRoomInfoByAcId($id){
		return $this->where(array("account_id"=>$id,"status"=>2))->find();
	}
	/**
	 * [获取房间/床位信息]
	 **/
	public function getRoom($id){
		$MRoom = M("room");
		$MHouses = M("houses");
		$MSteward = M("admin_user");
		$room_info = $MRoom->where(array('id'=>$id))->find();
		$house_info = $MHouses->field("area_id,address,floor,door_no,building,steward_id,address")->where(array('house_code'=>$room_info['house_code']))->find();
		$steward_info = $MSteward->field("id,username,nickname,mobile")->where(array('id'=>$house_info['steward_id']))->find();
		$room_info['area_name'] = M("area")->where(array("id"=>$house_info['area_id']))->getField("area_name");
		if ( !empty($room_info['room_images_01']) ) {
			$room_info['room_images'][] = $room_info['room_images_01'];
		}
		if ( !empty($room_info['room_images_02']) ) {
			$room_info['room_images'][] = $room_info['room_images_02'];
		}
		if ( !empty($room_info['room_images_03']) ) {
			$room_info['room_images'][] = $room_info['room_images_03'];
		}
		if ( !empty($room_info['room_images_04']) ) {
			$room_info['room_images'][] = $room_info['room_images_04'];
		}

		$room_info['house_info'] = $house_info;
		$room_info['steward_info'] = $steward_info;
		$room_info['address'] = $house_info['address'];
		return $room_info;
	}
	/**
	* [获取该房间的人数]
	**/
	public function getRoomPersonByCode($room_code){
		return $this->where(array("status"=>2,"room_code"=>$room_code))->count();
	}
	/**
	* [获取房间管家]
	**/
	public function getRoomSteward($room_id){
		$MRoom = M("room");
		$MHouse = M("houses");
		$MAdmin = D("admin_user");
		$house_code = $MRoom->where(array("id"=>$room_id))->getField("house_code");
		$steward_id = $MHouse->where(array("house_code"=>$house_code))->getField("steward_id");
		//需考虑以后是多个管家对一个房源 steward_id 有可能是一个字符串 : 4,7,23 三个管家id这样
		$steward_info = $MAdmin->getStewardInfo($steward_id);
		return $steward_info;
	}
}

?>