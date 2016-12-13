<?php
namespace Admin\Model;
use Think\Model;
/**
* [例行打款数据层]
*/
class BackBillModel extends Model{
	/**
	* [插入一条例行打款]
	* @param $arr 数组
	* @param $type 是什么类型 退款类型 1 退款	
	* @param 
	**/
	public function addOneBackBill($arr){
		$data['money'] = $arr['money'];
		$data['account_id'] = $arr['account_id'];
		$data['room_id'] = $arr['room_id'];
		$data['mobile'] = $arr['mobile'];
		$data['back_type'] = $arr['back_type'];
		$data['pay_account'] = $arr['pay_account'];
		$data['pay_type'] = $arr['pay_type'];
		$data['schedule_id'] = $arr['schedule_id'];
		$data['create_time'] = date("Y-m-d H:i:s",time());

		return $this->add($data);
	}

	/**
	* [获取未完成例行打款列表]
	* @param $is_affirm 0未确认 1为租客确认 2为财务确认
	* @param $back_type 1为例行打款到帐号 2为打款到余额
	**/
	public function showBackBillList($is_finish = 0,$is_affirm = 0,$back_type = 1){

		$list = $this->where(array("is_finish"=>$is_finish,"is_affirm"=>$is_affirm,"back_type"=>$back_type))->select();
		$DRoom = D("room");
		$DAccount = D("account");
		$pay_type = C("pay_type");
		
		foreach ( $list AS $key=>$val ) {
			$room_info = $DRoom->getRoomInfoById($val['room_id']);
			$list[$key]['room_info'] = $room_info;
			$list[$key]['pay_type_name'] = $pay_type[$val['pay_type']];
			$list[$key]['realname'] = $DAccount->getFieldById($val['account_id'],"realname");
		}
		return $list;
	}

	/**
	* [设置为完成]
	**/
	public function setFinish($id){
		return $this->where(array("id"=>$id))->save(array("is_finish"=>1));
	}
}

?>