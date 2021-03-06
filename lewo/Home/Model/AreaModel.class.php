<?php
namespace Home\Model;
use Think\Model;
/**
* [小区数据层]
*/
class AreaModel extends Model{
	/**
	* [获取小区列表]
	**/
	public function getareaList(){
		return $this->select();
	}
	public function getAreaById($id){
		return $this->where(array("id"=>$id))->find();
	}

	/**
	* [获取小区名字]
	**/
	public function getAreaNameById($id){
		return $this->where(array("id"=>$id))->getField("area_name");
	}
	
	/**
	* [获取房源的小区名 ]
	**/
	public function getAreaInfoByCode($house_code){
		$area_id = M("houses")->where(array("house_code"=>$house_code))->getField("area_id");
		return M("area")->where(array("id"=>$area_id))->getField("area_name");
	}
}
?>