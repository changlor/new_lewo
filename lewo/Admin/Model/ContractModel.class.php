<?php
namespace Admin\Model;
use Think\Model;
/**
* [合同数据层]
*/
class ContractModel extends BaseModel{
	private $table;
    protected $tableName = 'contract';

	public function __construct()
    {
        parent::__construct();
        $this->table = M($this->tableName);
    }

    public function select($where, $field)
	{
		$field = empty($field) ? '' : $field;
		$where = empty($where) ? '' : $where;
		return $this->table->field($field)->where($where);
	}

    public function selectField($where, $field)
	{
		return $this->select($where)->getField($field);
	}

	/**
	* [根据用户id和房间id获取合同]
	**/
	public function getContract($account_id,$room_id){
		return $this->table->where(array("account_id"=>$account_id,"room_id"=>$room_id))->find();
	}
	/**
	* [获取合同中的支付信息]
	**/
	public function getPayInfo($pro_id){
		$pay_info = $this->table
					->alias('c')
					->field('c.*,p.*')
					->join('lewo_pay p ON c.pro_id=p.pro_id')
					->where(array("c.pro_id"=>$pro_id))
					->find();
		return $pay_info;
	}
	/**
	 * [合同表][是否该月入住]
	 **/
	public function isDateCheckInByDate($account_id,$room_id,$year,$month){
        $ht_info = $this->table->where(array("account_id"=>$account_id,"room_id"=>$room_id,"contract_status"=>1))->find();
        $ht_year = date("Y",strtotime($ht_info['start_time']));
        $ht_month = date("m",strtotime($ht_info['start_time']));

        if ( $ht_year == $year && $ht_month == $month ) {
        	return true;
        } else {
        	return false;
        }
	}
	/**
	 * [是否该月退租]
	 **/
	public function isDateCheckOutByDate($account_id,$room_id,$year,$month){
        $actual_end_time = $this->where(array("account_id"=>$account_id,"room_id"=>$room_id))->getField("actual_end_time");
        $ht_year = date("Y",strtotime($actual_end_time));
        $ht_month = date("m",strtotime($actual_end_time));

        if ( $ht_year == $year && $ht_month == $month ) {
        	return true;
        } else {
        	return false;
        }
	}
	/**
	 * [获取两个时间进行判断，判断年月是否相同]
	 **/
	public function bothTimeIsEqual($time1,$time2){
        $time1_year = date("Y",strtotime($time1));
        $time1_month = date("m",strtotime($time1));
        $time2_year = date("Y",strtotime($time2));
        $time2_month = date("m",strtotime($time2));

        if ( $time1_year == $time2_year && $time1_month == $time2_month ) {
        	return true;
        } else {
        	return false;
        }
	}
	/**
	* [获取合同人数]
	**/
	public function getPersonCount($account_id,$room_id){
		return $this->table->where(array("account_id"=>$account_id,"room_id"=>$room_id))->getField("person_count");
	}
	/**
	* [获取合同租期开始时间]
	**/
	public function getContractStartDate($account_id,$room_id){
		return $this->table->where(array("account_id"=>$account_id,"room_id"=>$room_id))->getField("start_time");
	}
	/**
	* [获取合同租期结束时间]
	**/
	public function getContractEndDate($account_id,$room_id){
		return $this->table->where(array("account_id"=>$account_id,"room_id"=>$room_id))->getField("end_time");
	}
	/**
	* [获取合同房租到期日时间]
	**/
	public function getContractRentDate($account_id,$room_id){
		return $this->table->where(array("account_id"=>$account_id,"room_id"=>$room_id))->getField("rent_date");
	}
	/**
	* [获取缴费周期]
	**/
	public function getPeriod($account_id,$room_id){
		return $this->table->where(array("account_id"=>$account_id,"room_id"=>$room_id))->getField("period");
	}
	/**
	* [获取押金]
	**/
	public function getDeposit($account_id,$room_id){
		return $this->table->where(array("account_id"=>$account_id,"room_id"=>$room_id))->order('id desc')->getField("deposit");
	}
	/**
	* [获取合同列表]
	**/
	public function getContractList(){
		$list = $this->table->order("id desc")->where(array("is_delete"=>0))->select();
		$DAccount = D("account");
		$DRoom = D("room");
		foreach ( $list AS $key=>$val ) {
			$list[$key]['realname'] = $DAccount->getFieldById($val['account_id'],"realname");
			$list[$key]['mobile'] = $DAccount->getFieldById($val['account_id'],"mobile");
			$list[$key]['room_info'] = $DRoom->getRoomById($val['room_id']);
			$list[$key]['year'] = date("Y",strtotime($val['start_time']));
			$list[$key]['month'] = date("m",strtotime($val['start_time']));
			$rent_type = explode("_", $val['rent_type']);
			$list[$key]['rent_type_name'] = "押".$rent_type['0']."付".$rent_type['1'];
			$list[$key]['contract_status_name'] = C("contract_status_arr")[$val['contract_status']];
			$list[$key]['pay_type_name'] = C("pay_type")[$val['pay_type']];
		}
		return $list;
	}

	/**
	* [根据年月，获取该月和这房屋有关联的合同][默认正常合同]
	* @param $house_code 房屋编号
	* @param $year 年
	* @param $month 月
	* @param $contract_status 合同状态 1.正常,2.换房,3转租 4.正常退租 5.违约退租
	**/
	public function getContractListByDateForDailyBill($house_code,$year,$month,$contract_status = 1){
		$date = $year."-".$month;
		$datetime = strtotime($date);

		//获取房屋编号一下的房间id
		$room_id_arr = M("room")->field("id")->where(array("house_code"=>$house_code))->select();
		$contract_list = array();

		foreach ( $room_id_arr AS $val ) {
			//在合同表中获取了该房间id全部合同，
			$list = $this->table
					->alias('c')
					->field("a.realname,c.rent_date,c.period,c.contract_status,c.person_count,c.room_id,c.account_id,c.contract_status,c.actual_end_time,c.start_time,c.end_time,c.rent,c.fee,c.roomD,c.is_delete,r.room_code,r.house_code,r.room_fee,p.pay_status,p.id AS p_id,c.id AS c_id")
					->join("lewo_room r ON c.room_id = r.id",'left')
					->join("lewo_pay p ON p.pro_id = c.pro_id",'left')
					->join('lewo_account a ON a.id = c.account_id','left')
					->where(array("p.room_id"=>$val['id'],"p.bill_type"=>2,"p.pay_status"=>1,"c.is_delete"=>0,"c.contract_status"=>$contract_status))
					->select();

			foreach ( $list AS $k=>$v ) {
				$start_date_time = strtotime(date("Y",strtotime($v['start_time']))."-".date("m",strtotime($v['start_time'])));//合同开始年月
				$end_date_time = strtotime(date("Y",strtotime($v['end_time']))."-".date("m",strtotime($v['end_time'])));//合同结束年月
				$actual_end_date_time = strtotime(date("Y",strtotime($v['actual_end_time']))."-".date("m",strtotime($v['actual_end_time'])));//实际退房年月
				//如果生成账单的年月 小于 合同开始年月，说明是在该月签的合同
				if ( $datetime < $start_date_time) {	
					continue;//跳过
				}
				//判断生成账单的年月是否大于合同结束年月，是则不符合条件
				if ( $datetime > $end_date_time ) {
					continue;
				}
				//判断生成账单的年月是否大于实际退房年月，是则不符合条件
				if ( $v['actual_end_time'] != 0 ) {			
					if ( $datetime > $actual_end_date_time ) {
						continue;
					}
				}
				
				$contract_list[] = $v;
				
			}
		}
		return $contract_list;
	}

}

?>