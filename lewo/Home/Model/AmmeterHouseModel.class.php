<?php
namespace Home\Model;
use Think\Model;
/**
* [房屋电表数据层]
*/
class AmmeterHouseModel extends BaseModel{
	private $table;
	protected $tableName = 'ammeter_house';

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
	public function checkHouseAmmeterByDate($house_id,$year,$month){
		$result = $this->table->where(array('house_id'=>$house_id,'input_year'=>$year,'input_month'=>$month))->find();

		if ( is_null($result) ) {
			$data['status'] = 0;
			$data['house_id'] = $house_id;
			$data['input_year'] = $year;
			$data['input_month'] = $month;
			$id = $this->table->add($data);
		} 

		//检查该房源下的房间是否生成了当月的电表
        $DAmmeterRoom = D("ammeter_room");
        $DHouses = D("houses");
        $houses_info = $DHouses->getHouseById($house_id);
        //如果房屋类型不等于2：床位，则要生成房间电表
        if ( $houses_info['type'] != 2 ){
	        $room_list = $DHouses->getRoomList($houses_info['house_code']);
	        foreach( $room_list AS $key=>$val ){
	            $DAmmeterRoom->checkRoomAmmeterByDate($val['id'],$house_id,$year,$month);
	        }
        }
	}
	/**
	* [获取获取数据 by ammeter_id]
	**/
	public function getAmmeterById($id){
		return $this->table->where(array('id'=>$id))->find();
	}
	/**
	* [获取该房屋id的水电气列表]
	**/
	public function getHouseAmmeterById($house_id){
		return $this->table->where(array('house_id'=>$house_id))->order("input_year desc,input_month desc")->select();
	}
	/**
	* [完成修改水电气]
	**/
	public function updateAmmeterById($amme_id,$post){
		$data['status'] = 1;
		$data['total_water'] = $post['zS'];
		$data['total_energy'] = $post['zD'];
		$data['total_gas'] = $post['zQ'];
		$data['steward_id'] = $_SESSION['steward_id'];
		$data['input_date'] = date("Y-m-d H:i:s",time());
		return $this->table->where(array('id'=>$amme_id))->save($data);
	}
	/**
	* [获取最新水电气]
	**/
	public function getFirstInfoByHouseId($house_id){
		return $this->table->field('house_id,input_month,input_year,total_water,total_energy,total_gas')->where(array("house_id"=>$house_id))->order("input_month desc,input_year desc")->find();
	}

	/**
	* [判断录入的水电气是否少于上次录入]
	**/
	public function verifyAmmeter($house_id, $verifyData){
		if (is_null($house_id) || !is_numeric($house_id)) {
			return parent::response([false, '房屋ID不存在']);
		}
		if (is_null($verifyData)) {
			return parent::response([false, '验证数组不存在']);
		}
		// 获取一条最新的水电气信息
		$ammeter_house = $this->getFirstInfoByHouseId($house_id);

		if ( $verifyData['total_water'] < $ammeter_house['total_water'] ) {
			return parent::response([false, '录入的水度数:'.$verifyData['total_water'].'少于最新水度数:'.$ammeter_house['total_water'].'，请检查!']);
        }
        if ( $verifyData['total_energy'] < $ammeter_house['total_energy'] ) {
        	return parent::response([false, '录入的电度数:'.$verifyData['total_energy'].'少于最新电度数:'.$ammeter_house['total_energy'].'，请检查!']);
        }
        if ( $verifyData['total_gas'] < $ammeter_house['total_gas'] ) {
        	return parent::response([false, '录入的气度数:'.$verifyData['total_gas'].'少于最新气度数:'.$ammeter_house['total_gas'].'，请检查!']);
        }
        return parent::response([true, '验证成功']);
	}
}

?>