<?php
namespace Admin\Model;
use Think\Model;
/**
* [房屋水电气账单数据层]
*/
class ChargeHouseModel extends Model{
	/**
	* [检查当月账单是否存在]
	**/
	public function createOneCharge($house_id,$year,$month){
		$this->startTrans();
		$result = $this->where(array('house_id'=>$house_id,'input_year'=>$year,'input_month'=>$month))->find();

		if ( empty($result) ) {
			$data['house_id'] = $house_id;
			$data['input_year'] = $year;
			$data['input_month'] =$month;
			$data['is_send'] = 0;
			$data['is_create'] = 0;
			$this->add($data);
			$this->commit();
			return true;
		} else {
			return false;
		}
	}
	/**
	* [获取房屋账单信息]
	**/
	public function getChargeHouseInfo($house_id,$year,$month){
		return $this->where(array('house_id'=>$house_id,'input_year'=>$year,'input_month'=>$month))->find();
	}
	/**
	* [获取该房屋id的列表]
	**/
	public function getChargeList($house_id){
		$DHouses = D("houses");
		$house_code = $DHouses->getHouseCodeById($house_id);
		$list = $this->where(array('house_id'=>$house_id))->order("input_year desc,input_month desc")->select();
		//搜索该年该月已发送的账单
		$MCharge_bill = M("charge_bill");
		$MPay = M("pay");
		foreach( $list AS $key=>$val ){
			$where = array();
			$where['cb.house_code'] = $house_code;
			$where['p.input_year'] = $val['input_year'];
			$where['p.input_month'] = $val['input_month'];
			$where['cb.type'] = 1;
			$total_count =  $MPay
                            ->alias('p')
                            ->field('cb.*,p.*')
                            ->join('lewo_charge_bill cb ON cb.pro_id=p.pro_id ')
                            ->where($where)
                            ->count();
			$where['cb.is_send'] = 1;
			$sended_count = $MPay
                            ->alias('p')
                            ->field('cb.*,p.*')
                            ->join('lewo_charge_bill cb ON cb.pro_id=p.pro_id ')
                            ->where($where)
                            ->count();
			$list[$key]['total_count'] = $total_count;//总数量
			$list[$key]['sended_count'] = $sended_count;//已发送数量
		}
		
		return $list;
	}
	/**
	* [将账单改成以生成]
	**/
	public function setIsCreate($charge_id){
		return $this->where(array("id"=>$charge_id))->save(array("is_create"=>1));
	}
	/**
	* [更新房屋账单信息的水电气]
	**/
	public function updateSDQ($id,$data){
		return $this->where(array("id"=>$id))->save($data);
	}
	/**
	* [发送成功后修改状态]
	**/
	public function setIsSend($house_id,$year,$month){
		return $this->where(array("house_id"=>$house_id,"input_year"=>$year,"input_month"=>$month))->save(array("is_send"=>1));
	}
}

?>