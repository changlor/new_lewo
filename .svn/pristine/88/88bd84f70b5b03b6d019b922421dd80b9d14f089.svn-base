<?php
namespace Admin\Model;
use Think\Model;
/**
* [房屋电表数据层]
*/
class AmmeterHouseModel extends Model{
	/**
	* [获取获取数据 by ammeter_id]
	**/
	public function getAmmeterById($id){
		return $this->where(array('id'=>$id))->find();
	}
	/**
	* [获取该房屋id的水电气列表]
	**/
	public function getHouseAmmeterById($house_id){
		return $this->where(array('house_id'=>$house_id))->order("id desc")->select();
	}
	/**
	* [获取这年这月这房屋id的水电气信息]
	**/
	public function getAmmeterByIdAndDate($house_id,$year,$month){
		return $this->where(array('house_id'=>$house_id,'input_year'=>$year,'input_month'=>$month))->find();
	}
	/**
	* [获取该房屋id最新两条的水电气信息]
	**/
	public function getTwoInfoAmmeterById($house_id){
		return $this->where(array("house_id"=>$house_id))->limit("0,2")->order("id desc,input_date desc")->select();
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
		return $this->where(array("house_id"=>$house_id))->order("input_month desc,input_year desc")->find();
	}
}

?>