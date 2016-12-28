<?php
namespace Home\Model;
use Think\Model;
/**
* [房间电表数据层]
*/
class AmmeterRoomModel extends BaseModel{
	private $table;
	protected $tableName = 'ammeter_room';

	public function __construct()
    {
        parent::__construct();
    	$this->table = M($this->tableName);
    }

    public function select($where, $field)
	{
		$field = empty($field) ? '' : $field;
		$where = empty($where) ? '' : $where;
		$field = is_array($field) ? implode(',', $field) : $field;
		return $this->table->field($field)->where($where);
	}
	/**
	* [检查当月是否生成水电气，不存在则生成一条数据，状态为0]
	**/
	public function checkRoomAmmeterByDate($room_id,$house_id,$year,$month){
		$result = $this->table->where(array('room_id'=>$room_id,'house_id'=>$house_id,'input_year'=>$year,'input_month'=>$month))->find();
		if ( is_null($result) ) {
			$data['status'] = 0;
			$data['room_id'] = $room_id;
			$data['house_id'] = $house_id;
			$data['input_year'] = $year;
			$data['input_month'] = $month;
			$id = $this->table->add($data);
			return $id;
		} else {
			return false;
		}
	}
	
	/**
	* [根据房屋id、年月获取房间电表]
	**/
	public function getAmmeterRoom($house_id,$year,$month){
		$aRoom_list = $this->table
					->alias('ar')
					->field("r.id,r.room_code,r.bed_code,ar.*")
					->join("lewo_room r ON r.id = ar.room_id")
					->where(array('ar.house_id'=>$house_id,'ar.input_year'=>$year,'ar.input_month'=>$month))
					->order("r.room_sort asc")
					->select();

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
		return $this->table->where(array('id'=>$id))->save($data);
	}

	/**
	* [获取房间最新的电度数]
	**/
	public function getFirstInfoByRoomId($room_id){
		return $this->table->where(array('room_id'=>$room_id,'status'=>1))->order('input_year desc , input_month desc')->limit('0,1')->find();
	}

	/**
	* [验证房屋电度数]
	**/
	public function verifyAmmeterRoom($roomId, $verifyData){
		if (is_null($roomId) || !is_numeric($roomId)) {
			return parent::response([false, '房间ID不存在']);
		}
		if (is_null($verifyData)) {
			return parent::response([false, '验证数据不存在']);
		}
		$last_ammeter_room_info = $this->getFirstInfoByRoomId($roomId);
		if ( !is_null($last_ammeter_room_info) ) {
            if ( $verifyData['room_energy'] < $last_ammeter_room_info['room_energy'] ) {
                return parent::response([false, '该房间电度数低于上个月' . $last_ammeter_room_info['room_energy'] . '!请重新录入']);
            }
        }
        return parent::response([true, '验证成功']);
	}
}

?>