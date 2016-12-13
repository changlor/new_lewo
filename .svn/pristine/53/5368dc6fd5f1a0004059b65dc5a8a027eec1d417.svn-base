<?php
namespace Admin\Model;
use Think\Model;
/**
* [房间水电气账单数据层]
*/
class ChargeBillModel extends Model{
	/**
	* [插入账单表]
	**/
	public function addBill($arr,$year,$month){
		$data['pro_id'] 			= $pdata['pro_id'] = getOrderNo();
		$data['room_id'] 			= $pdata['room_id'] = $arr['room_id'];
		$data['account_id'] 		= $pdata['account_id'] = $arr['account_id'];
		$data['input_month'] 		= $pdata['input_month'] = $month;
		$data['input_year'] 		= $pdata['input_year'] = $year;
		$data['total_fee'] 			= $pdata['price'] = $arr['total_fee'];
		$data['create_time']		= $pdata['create_time'] 		= date('Y-m-d H:i:s',time());
		$pdata['should_date'] 		= $arr['should_pay_date'];
		$pdata['last_date'] 		= $arr['late_pay_date'];
		$pdata['pay_status'] 		= 0;
		$pdata['bill_type'] 		= $arr['bill_type'];
		$pdata['is_send']			= 0;
		$data['house_id'] 			= $arr['house_id'];
		$data['house_code'] 		= $arr['house_code'];
		$data['water_fee'] 			= $arr['water_fee'];
		$data['public_energy_fee'] 	= $arr['public_energy_fee'];
		$data['energy_fee'] 		= $arr['energy_fee'];
		$data['gas_fee'] 			= $arr['gas_fee'];
		$data['rubbish_fee'] 		= $arr['rubbish_fee'];
		$data['person_day'] 		= $arr['person_day'];
		$data['total_person_day'] 	= $arr['sum_person_day'];
		
		$data['total_energy'] 		= $arr['total_energy'];
		$data['total_water'] 		= $arr['total_water'];
		$data['total_gas'] 			= $arr['total_gas'];
		$data['wgfee_unit'] 		= $arr['wg_fee'];
		$data['start_energy'] 		= !empty($arr['start_energy'])? $arr['start_energy']: 0;
		$data['end_energy'] 		= !empty($arr['end_energy'])? $arr['end_energy']: 0;
		$data['start_water'] 		= !empty($arr['start_water'])? $arr['start_water']: 0;
		$data['end_water'] 			= !empty($arr['end_water'])? $arr['end_water']: 0;
		$data['start_gas'] 			= !empty($arr['start_gas'])? $arr['start_gas']: 0;
		$data['end_gas'] 			= !empty($arr['end_gas'])? $arr['end_gas']: 0;
		$data['start_room_energy'] 	= !empty($arr['start_room_energy'])? $arr['start_room_energy'] : 0;
		$data['end_room_energy'] 	= !empty($arr['end_room_energy'])? $arr['end_room_energy'] : 0;
		$data['room_energy_fee'] 	= !empty($arr['room_energy_fee'])? $arr['room_energy_fee'] : 0;
		$data['room_energy_add'] 	= !empty($arr['room_energy_add'])? $arr['room_energy_add'] : 0;
		
		$data['rent_fee'] 			= $arr['rent'];
		$data['rent_des'] 			= $arr['rent_fee_des'];
		$data['service_fee'] 		= $arr['fee'];
		$data['is_send'] 			= 0;//未发送
		$data['wx_fee']				= '';
		$data['wx_des'] 			= '';
		
		$data['rent_date_old'] 		= $arr['rent_date_old'];
		$data['rent_date_to'] 		= $arr['rent_date_to'];
		$data['type'] 				= $arr['type']; //日常
		/*$result = $this->where(array("room_id"=>$data['room_id'],"input_year"=>$year,"input_month"=>$month))->find();因为会出现一间房间多个合同情况，例如一个合同正常，一个合同退房*/

		$this->add($data);
		$result = M('pay')->add($pdata);
		return $result;
	}

	/**
	* [查看账单列表]
	**/
	public function showChargeBillList($house_code,$year,$month,$type){
		$pay_list = M('charge_bill')
					->alias('cb')
					->field('cb.*,p.pay_status,p.pay_time,p.price,p.should_date,p.last_date,cb.id AS cb_id,p.id AS p_id,a.realname,r.room_sort,r.room_code,r.bed_code')
					->join('lewo_pay p ON cb.pro_id = p.pro_id')
					->join('lewo_account a ON p.account_id = a.id')
					->join('lewo_room r ON p.room_id = r.id')
					->where(array("cb.house_code"=>$house_code,"p.input_year"=>$year,"p.input_month"=>$month,"cb.type"=>$type))
					->order('r.room_sort asc,r.bed_code asc')
					->select();

		return $pay_list;
	}

	/**
	* [查看非正常账单列表]
	**/
	public function showAbnormalChargeBillList($house_code,$year,$month){
		return $this->where(array("house_code"=>$house_code,"input_year"=>$year,"input_month"=>$month,"type"=>array("NOT IN","1")))->select();
	}

	/**
	* [修改账单]
	**/
	public function updateChargeBill($pro_id,$data){
		$MChargeBill = M('charge_bill');
		$MPay = M('pay');
		$save['end_energy'] = $data['end_energy'];
		$save['start_energy'] = $data['start_energy'];
		$save['end_water'] = $data['end_water'];
		$save['start_water'] = $data['start_water'];
		$save['end_gas'] = $data['end_gas'];
		$save['start_gas'] = $data['start_gas'];
		$save['end_room_energy'] = $data['end_room_energy'];
		$save['start_room_energy'] = $data['start_room_energy'];
		$save['person_day'] = $data['person_day'];
		$save['rent_fee'] = $data['rent_fee'];
		$save['rent_des'] = $data['rent_des'];
		$save['service_fee'] = $data['service_fee'];
		$save['wgfee_unit'] = $data['wgfee_unit'];
		$save['rubbish_fee'] = $data['rubbish_fee'];
		$save['public_energy_fee'] = $data['public_energy_fee'];
		$save['energy_fee'] = $data['energy_fee'];
		$save['water_fee'] = $data['water_fee'];
		$save['gas_fee'] = $data['gas_fee'];
		$save['wx_fee'] = $data['wx_fee'];
		$save['wx_des'] = $data['wx_des'];
		
		$save['total_person_day'] = $data['sum_person_day'];
		$save['modify_time'] = $data['modify_time'];
		$save['room_energy_fee'] = $data['room_energy_fee'];
		$save['room_energy_add'] = $data['room_energy_add'];
		$save['extra_public_energy_fee'] = $data['extra_public_energy_fee']; 
        $save['extra_public_water_fee']  = $data['$extra_public_water_fee'];      
        $save['extra_public_gas_fee']    = $data['$extra_public_gas_fee'];  

		$psave['price'] = $save['total_fee'] = $data['total_fee'];
		$psave['should_date'] = $data['should_date'];
		$psave['last_date']   = $data['last_date'];
		$MChargeBill->where(array("pro_id"=>$pro_id))->save($save);
		$MPay->where(array("pro_id"=>$pro_id))->save($psave);
	}
	/**
	* [修改该房屋编码的全部总人日字段]
	**/
	public function updateTotalPersonDay($house_code,$day){
		return $this->where(array("house_code"=>$house_code,"input_year"=>$year,"input_month"=>$month))->save(array("total_person_day"=>$day));
	}
	/**
	* [获取总人日]
	**/
	public function getPersonDayCount($house_code,$year,$month){
		return $this->where(array("house_code"=>$house_code,"input_year"=>$year,"input_month"=>$month))->sum("person_day");
	}
	/**
	* [日常账单]
	**/
	public function showDailyBillList(){
		$list = $this->where(array("is_delete"=>0))->order("id desc")->select();
		$DRoom = D("room");
		$DAccount = D("account");
		$DArea = D("area");
		$DContract = D("contract");
		foreach ($list AS $key=>$val) {
			$list[$key]['room_code'] = $DRoom->getRoomCodeById($val['room_id']);
			$list[$key]['bed_code'] = $DRoom->getBedCodeById($val['room_id']);
			$list[$key]['realname'] = $DAccount->getFieldById($val['account_id'],"realname");
			$list[$key]['mobile'] = $DAccount->getFieldById($val['account_id'],"mobile");
			$list[$key]['SDQtotal'] = $val['water_fee'] + $val['gas_fee'] + $val['energy_fee'] + $val['room_energy_fee'];
			$list[$key]['area_name'] = $DArea->getAreaInfoByCode($val['house_code']);
			$list[$key]['ht_start_date'] = $DContract->getContractStartDate($val['account_id'],$val['room_id']);
			$list[$key]['ht_end_date'] = $DContract->getContractEndDate($val['account_id'],$val['room_id']);
			$list[$key]['period'] = $DContract->getPeriod($val['account_id'],$val['room_id']);
			$list[$key]['deposit'] = $DContract->getDeposit($val['account_id'],$val['room_id']);
			$pay_type = C("pay_type");
			$list[$key]['pay_type_name'] = $pay_type[$val['pay_type']];
			//最迟缴费倒计时
			$startdate=strtotime($val['should_pay_date']);
			$enddate=strtotime(date("Y-m-d",time()));
			$count_down_days=round(($startdate-$enddate)/86400);
			$list[$key]['count_down_days'] = $count_down_days;
			switch ($val['type']) {
				case 1:
					$list[$key]['type_name'] = "日常";
					break;
				case 2:
					$list[$key]['type_name'] = "退房";
					break;
				case 3:
					$list[$key]['type_name'] = "转房";
					break;
				case 4:
					$list[$key]['type_name'] = "换房";
					break;
			}
		}
		return $list;
	}
	/**
	* [获取chargebill中的支付信息]
	**/
	public function getPayInfo($pro_id){
		$pay_info = M('pay')
					->alias('p')
					->field('cb.*,c.*,p.*,a.mobile')
					->join('lewo_charge_bill cb ON cb.pro_id=p.pro_id','left')
					->join('lewo_contract c ON c.pro_id=p.pro_id','left')
					->join('lewo_account a ON a.id=p.account_id','left')
					->where(array("p.pro_id"=>$pro_id))
					->find();
		return $pay_info;
	}

	/**
	* [获取该租客未支付列表]
	**/
	public function getNotPayList($account_id){
		$list = $this->where(array("account_id"=>$account_id,"pay_status"=>0))->select();
		$DRoom = D("room");
		$DAccount = D("account");
		$DArea = D("area");
		$DContract = D("contract");
		foreach ($list AS $key=>$val) {
			$list[$key]['room_code'] = $DRoom->getRoomCodeById($val['room_id']);
			$list[$key]['bed_code'] = $DRoom->getBedCodeById($val['room_id']);
			$list[$key]['realname'] = $DAccount->getFieldById($val['account_id'],"realname");
			$list[$key]['mobile'] = $DAccount->getFieldById($val['account_id'],"mobile");
			$list[$key]['SDQtotal'] = $val['water_fee'] + $val['gas_fee'] + $val['energy_fee'] + $val['room_energy_fee'];
			$list[$key]['area_name'] = $DArea->getAreaInfoByCode($val['house_code']);
			$list[$key]['ht_start_date'] = $DContract->getContractStartDate($val['account_id'],$val['room_id']);
			$list[$key]['ht_end_date'] = $DContract->getContractEndDate($val['account_id'],$val['room_id']);
			$list[$key]['period'] = $DContract->getPeriod($val['account_id'],$val['room_id']);
			$list[$key]['deposit'] = $DContract->getDeposit($val['account_id'],$val['room_id']);
			$pay_type = C("pay_type");
			$list[$key]['pay_type_name'] = $pay_type[$val['pay_type']];
			//最迟缴费倒计时
			$startdate=strtotime($val['should_pay_date']);
			$enddate=strtotime(date("Y-m-d",time()));
			$count_down_days=round(($startdate-$enddate)/86400);
			$list[$key]['count_down_days'] = $count_down_days;
			switch ($val['type']) {
				case 1:
					$list[$key]['type_name'] = "日常";
					break;
				case 2:
					$list[$key]['type_name'] = "退房";
					break;
				case 3:
					$list[$key]['type_name'] = "转房";
					break;
				case 4:
					$list[$key]['type_name'] = "换房";
					break;
			}
		}
		return $list;
	}

	/**
    * [修改支付][1支付宝][2微信][3余额抵押]
    **/
    public function setPayStatus($charge_id,$pay_type){
    	return $this->where(array("id"=>$charge_id))->save(array("pay_type"=>$pay_type,"pay_status"=>1));
    }
	
}

?>