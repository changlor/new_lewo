<?php
namespace Admin\Model;
use Think\Model;
/**
* [小区数据层]
*/
class AreaModel extends BaseModel{
	protected $table;
    protected $tableName = 'area';

    public function __construct()
    {
        parent::__construct();
        $this->table = M($this->tableName);
    }

    public function getBills($where)
    {
        $steward_id = parent::steward_id;
        if (!is_numeric($steward_id)) {
            $this->login(); die();
        }
    }

    public function select($where, $field)
    {
        $field = empty($field) ? '' : $field;
        $where = empty($where) ? '' : $where;
        $field = is_array($field) ? implode(',', $field) : $field;
        return $this->table->field($field)->where($where);
    }
	/**
	* [获取小区列表]
	**/
	public function getareaList(){
		return $this->table
		->field(['id', 'area_name', 'city_id', 'is_show', 'energy_unit', 'water_unit', 'gas_unit', 'rubbish_fee', 'energy_stair'])
		->where(['is_show'=>1])
		->select();
	}
	public function getAreaById($id){
		return $this->table->where(array("id"=>$id))->find();
	}

	/**
	* [获取小区名字]
	**/
	public function getAreaNameById($id){
		return $this->table->where(array("id"=>$id))->getField("area_name");
	}
	/**
	* [获取房源的小区名 ]
	**/
	public function getAreaInfoByCode($house_code){
		$area_id = M("houses")->where(array("house_code"=>$house_code))->getField("area_id");
		return $this->table->where(array("id"=>$area_id))->getField("area_name");
	}
	/**
	* [获取该小区下的所有房屋编码]
	**/
	public function getHouseCodeListByAreaId($area_id){
		$house_code_list = M("houses")->field("house_code")->where(array("area_id"=>$area_id))->select();
		$str = "";
		foreach($house_code_list AS $key=>$val){
			$str .= $val['house_code'].",";
		}
		$str = substr($str, 0, -1);
		return $str;
	}
}
?>