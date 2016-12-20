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

	/*
	 * author: changle
	 * desc: get stedward bills depends on bills
	 */
	public function getBills($where, $type = ''){
		$type = empty($type) ? 'all' : $type;
		$steward_id = $_SESSION['steward_id'];
		$MPay = M('pay');
		$filters = [
			'lewo_pay.is_show' => 1,
			'lewo_pay.is_send' => 1,
			'lewo_houses.steward_id' => $steward_id,
		];
		$field = [
			//lewo_pay
			'lewo_pay.room_id', 'lewo_pay.pro_id',
			'lewo_pay.price', 'lewo_pay.pay_money', 'lewo_pay.pay_status', 'lewo_pay.bill_type', 'lewo_pay.bill_des', 'lewo_pay.account_id',
			'lewo_pay.is_show', 'lewo_pay.is_send',
			'lewo_pay.create_time', 'lewo_pay.should_date', 'lewo_pay.last_date',
			'lewo_pay.favorable', 'lewo_pay.favorable_des',
			//lewo_contract
			'lewo_contract.deposit', 'lewo_contract.rent', 'lewo_contract.fee', 'lewo_contract.rent_type',
			//lewo_charge_bill
			'lewo_charge_bill.water_fee',
			'lewo_charge_bill.room_energy_fee',
			'lewo_charge_bill.wx_fee',
			'lewo_charge_bill.rubbish_fee',
			'lewo_charge_bill.energy_fee',
			'lewo_charge_bill.gas_fee',
			'lewo_charge_bill.rent_fee',
			'lewo_charge_bill.wgfee_unit',
			'lewo_charge_bill.service_fee',
			//lewo_houses
			'lewo_houses.building', 'lewo_houses.door_no', 'lewo_houses.floor',
			//lewo_area
			'lewo_area.area_name',
			//lewo_account
			'lewo_account.realname',
		];
		$field = implode(',', $field);
		$bills = $this->field($field)
		->join('lewo_room ON lewo_room.id = lewo_pay.room_id', 'left')
		->join('lewo_houses ON lewo_houses.id = lewo_room.house_id', 'left')
		->join('lewo_account ON lewo_account.id = lewo_pay.account_id', 'left')
		->join('lewo_area ON lewo_area.id = lewo_houses.area_id', 'left')
		->join('lewo_charge_bill ON lewo_charge_bill.pro_id = lewo_pay.pro_id', 'left')
		->join('lewo_contract ON lewo_contract.pro_id = lewo_pay.pro_id', 'left')
		->where($filters)
		->where($where)
		->order('lewo_pay.pay_status asc, lewo_pay.last_date asc, lewo_pay.input_year desc, lewo_pay.input_month desc')
		->select();

		foreach($bills as $key => $value){
			$bills[$key]['bill_type'] = C('bill_type')[$value['bill_type']];
			$bills[$key]['rent_type'] = '压' . str_replace('_', '付', $value['rent_type']);
			$bills[$key]['bill_des'] = empty($value['bill_des']) ? '无' : $value['bill_des'];
			$bills[$key]['total_daily_room_fee'] = $value['room_energy_fee'] + $value['water_fee'] + $value['energy_fee'] + $value['gas_fee'] + $value['rubbish_fee'];
			$bills[$key]['count_down_days'] = -floor((time() - strtotime($value['last_date'])) / 60 /60 /24);
		}
		
		return $bills;
	}
}

?>