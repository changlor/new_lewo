<?php
namespace Admin\Model;
use Think\Model;
/**
* [支付表]
*/
class PayModel extends BaseModel{
	protected $table;
	protected $tableName = 'pay';

	public function __construct()
    {
        parent::__construct();
    	$this->table = M($this->tableName);
    }

    public function select($where, $field)
	{
		$field = empty($field) ? '*' : $field;
		$where = empty($where) ? '' : $where;
		return $this->table->field($field)->where($where);
	}

	public function selectPay($where, $field){
		return $this->select($where, $field)->select();
	}

	public function selectField($where, $field)
	{
		return $this->select($where)->getField($field);
	}

	/**
	* [获取某月的日常账单数量]
	**/
	public function getBillCount($param)
	{	
		if (is_null($param['bill_type'])) {
			return parent::response([false, '账单类型不存在']);
		}
		if (is_null($param['input_year']) || is_null($param['input_month'])) {
			return parent::response([false, '年月不存在']);
		}
		$where = [
			'bill_type' => $param['bill_type'],
			'input_year' => $param['input_year'],
			'input_month' => $param['input_month'],
			'is_show' => 1
		];
		return $this->select($where)->count();
	}

	/**
	* [获取租客未支付的账单]
	**/
	public function getNotPayList($input){
		if (is_null($input['account_id']) || !is_numeric($input['account_id'])){
			return parent::response([false, '租客ID不存在']);
		}
		if (is_null($input['room_id']) || !is_numeric($input['room_id'])) {
			return parent::response([false, '房间ID不存在']);
		}
		$where['lewo_pay.account_id'] = $input['account_id'];
		$where['lewo_pay.room_id'] = $input['room_id'];
		$where['lewo_pay.bill_type'] = $input['bill_type'];
		$where['lewo_pay.pay_status'] = $input['pay_status'];
		$where['lewo_pay.input_month'] = $input['input_month'];
		$where['lewo_pay.input_year'] = $input['input_year'];
		$where['lewo_pay.is_show'] = !is_null($input['is_show']) ? $input['is_show'] : 1;
		$where['lewo_pay.is_send'] = !is_null($input['is_send']) ? $input['is_send'] : 1;
		$whereFilter = array_filter($where, function($v){
			return !is_null($v) && trim($v) != '' ? true : false;
		});
		$field = [
			'lewo_pay.room_id', 'lewo_pay.price', 'lewo_pay.bill_type',
			'lewo_pay.create_time', 'lewo_pay.account_id', 'lewo_pay.pro_id',
			'lewo_pay.input_month', 'lewo_pay.input_year'
		];
		return $this->selectPay($whereFilter, $field);
	}

	/**
	* [根据pro_id获取账单的详情]
	**/
	public function getBillDetail($payInfo){
		if (is_null($payInfo['pro_id']) || empty($payInfo['pro_id'])) {
			return parent::response([false, '账单ID不存在']);
		}
		if (is_null($payInfo['bill_type']) || empty($payInfo['bill_type'])) {
			return parent::response([false, '账单类型不存在']);
		}
		$DContract = D('contract');
		$DChargeBill = D('charge_bill');
		switch ($payInfo['bill_type']) {
			case C('bill_type_ht'):
				// 合同
				break;
				
			case C('bill_type_rc'):
				return $DChargeBill->getChargeBill($payInfo['pro_id']);
				break;
		}
	}

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
		$pdata['input_year']    = !is_null($param['input_year'])? $param['input_year'] : date('Y',time());
		$pdata['input_month']   = !is_null($param['input_month'])? $param['input_month'] : date('m',time());
		$pdata['should_date']	= !is_null($param['should_date'])? $param['should_date'] : date('Y-m-d H:i:s',time());
		$pdata['last_date']		= !is_null($param['last_date'])? $param['last_date'] : date('Y-m-d H:i:s',time());
		$pdata['pay_status'] 	= !is_null($param['pay_status'])? $param['pay_status'] : 0;
		$pdata['is_send']		= 1;
		$result = $this->table->add($pdata);
		
		return $result;
	}
}

?>