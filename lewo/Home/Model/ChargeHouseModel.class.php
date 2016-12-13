<?php
namespace Home\Model;
use Think\Model;
/**
* [账单数据层]
*/
class ChargeHouseModel extends Model{
	/**
	* [根据房屋id获取房屋该月的水电气度数]
	**/
	public function getChargeHouseById($house_id,$year,$month){
		return $this->where(array("house_id"=>$house_id,"input_year"=>$year,"input_month"=>$month))->find();
	}
}

?>