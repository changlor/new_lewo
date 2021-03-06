<?php
namespace Admin\Model;
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
	* [获取获取数据 by ammeter_id]
	**/
	public function getAmmeterById($id){
		return $this->table->where(array('id'=>$id))->find();
	}
	/**
	* [获取该房屋id的水电气列表]
	**/
	public function getHouseAmmeterById($house_id){
		return $this->table->where(array('house_id'=>$house_id))->order("id desc")->select();
	}
	/**
	* [获取这年这月这房屋id的水电气信息]
	**/
	public function getAmmeterByIdAndDate($house_id,$year,$month){
		return $this->table->where(array('house_id'=>$house_id,'input_year'=>$year,'input_month'=>$month))->find();
	}
	/**
	* [获取该房屋id最新两条的水电气信息]
	**/
	public function getTwoInfoAmmeterById($house_id){
		return $this->table->where(array("house_id"=>$house_id))->limit("0,2")->order("id desc,input_date desc")->select();
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
		return M("ammeter_house")->where(array('id'=>$amme_id))->save($data);
	}

	/**
	* [获取最新水电气]
	**/
	public function getFirstInfoByHouseId($house_id){
		return $this->table->where(array("house_id"=>$house_id))->order("input_month desc,input_year desc")->find();
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
}

?>