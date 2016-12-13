<?php
namespace Home\Model;
use Think\Model;
/**
* [支付表]
*/
class PayModel extends Model{
	/**
	* [生成一个账单]
	* @param $account_id 租客账号id
	* @param $room_id 房间id
	* @param $bill_type  账单类型 1定金 2合同 3日常 5拖欠押金 6其他 7合同欠款 8日常欠款 9其他欠款
	* @param $price 支付金额
	* @param $should_date 应支付时间
	* @param $last_date 最迟支付时间
	* @param $pay_status 支付状态
	**/
	public function create_bill($param){
		if ( is_null($param['account_id']) ) return false;
		if ( is_null($param['room_id']) ) 	 return false;
		if ( is_null($param['bill_type']) )  return false;
		if ( is_null($param['price']) ) 	 return false;

		$pro_id 				= getOrderNo();
		$pdata['pro_id'] 		= $pro_id;
		$pdata['bill_type'] 	= $param['bill_type'];
		$pdata['price'] 		= $param['price'];
		$pdata['create_time'] 	= date('Y-m-d H:i:s',time());
		$pdata['account_id'] 	= $param['account_id'];
		$pdata['room_id'] 		= $param['room_id'];
		$pdata['input_year']    = date('Y',time());
		$pdata['input_month']   = date('m',time());
		$pdata['should_date']	= !is_null($param['should_date'])? $param['should_date'] : date('Y-m-d',time());
		$pdata['last_date']		= !is_null($param['last_date'])? $param['last_date'] : date('Y-m-d',time());
		$pdata['is_send']       = 1;
		$result = $this->add($pdata);
		
		return $result;
	}

	/**
	* [获取账单]
	* @param $account_id 租客账号id
	* @param $bill_type  账单类型 1定金 2合同 3日常(is_send为1是租客才看到) 5拖欠押金 6其他 7合同欠款 8日常欠款 9其他欠款
	**/
	public function getPay($param){
		$bill_type_arr 	= C('bill_type');

		if (  is_null($param['account_id']) ) return false;
		if ( !is_null($param['not_bill_type']) )  $where['p.bill_type'] = array('NOT IN',$param['not_bill_type']);
		if ( !is_null($param['in_bill_type']) )  $where['p.bill_type'] = array('IN',$param['in_bill_type']);
		//不显示 9=>"错误扣除"
		if ( !is_null($param['not_pay_type']) )  $where['p.pay_type'] = array('NOT IN',$param['not_pay_type']);
		if ( !is_null($param['pay_status']) ) $where['p.pay_status'] 	= $param['pay_status'];

		if ( !is_null($param['bill_type']) )  {//如果单找一个类型的账单，则判断是否有存在
			$where['p.bill_type'] 	= $param['bill_type'];
			$bill_type_name			= $bill_type_arr[$param['bill_type']];
			if ( is_null($bill_type_name) ) return false; 
		}

		$where['p.account_id'] 	= $param['account_id'];
		$where['p.is_show'] 	= 1;
		$where['p.is_send']     = 1;
		if ( !is_null($param['pay_status']) ) $where['p.pay_status'] = $param['pay_status'];

		$bill_list = $this
					->alias('p')
					->field('a.realname,p.*,r.house_code')
					->join('lewo_account a ON a.id=p.account_id','left')
					->join('lewo_room r ON r.id=p.room_id','left')
					->where($where)
					->order('p.pay_status asc,p.input_year desc,p.input_month desc')
					->select();

		$MHouses 	= M('houses');
		$MArea 		= M('area');
		foreach ($bill_list as $key => $val) {
			//根据house_code 判断广州或重庆
            $area_id = $MHouses->where(array("house_code"=>$val['house_code']))->getField("area_id");
            $city_id = $MArea->where(array("id"=>$area_id))->getField("city_id");
            $city_name = C("city_id")[$city_id];

			$bill_list[$key]['bill_type_name'] = $bill_type_name = $bill_type_arr[$val['bill_type']];

			$realname = preg_replace('/(\/|\.|\%)/','', $val['realname']);

			$bill_list[$key]['bill_info'] = $realname.$val['input_year'].'年'.$val['input_month']."月".$bill_type_name.$city_name."账单";

			$startdate	=	time();
			$enddate	=	strtotime($val['should_date']);
			$days		=	round(($enddate-$startdate)/86400)+1;
    		$bill_list[$key]['days'] = $days;
		}

		return $bill_list;
	}
}

?>