<?php
namespace Admin\Model;
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