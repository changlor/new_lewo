<?php
namespace Home\Model;
use Think\Model;
/**
* [合同数据层]
*/
class ContractModel extends Base {
	protected $table;
	protected $tableName = 'contract';

	public function __construct()
    {
        parent::__construct();
    	$this->table = M($this->tableName);
    }

    public function insert($contract)
    {
    	return $this->table->add($contract);
    }

	public function addContract($input)
	{
		// 获取模型
        $DAccount = D('account');
        $DPay = D('pay');
        // 房屋id
        $roomId = $input['roomId'];
        // 租客id
        $accountId = $input['accountId'];
        // 租客姓名
        $realName = $input['realName'];
        // 租客电话
        $mobile = $input['mobile'];
        // 紧急联系人电话
        $contact2 = $input['contact2'];
        // 租客身份证号
        $idNo = $input['idNo'];
        // 租客邮箱
        $email = $input['email'];
        // 房租
        $rent = $input['rent'];
        // 租客人数
        $personCount = $input['personCount'];
        // 合租人电话，如果有
        $hzMobile = $input['hzMobile'];
        // 合租人身份证，如果有
        $hzCardNo = $input['hzCardNo'];
        // 物业费
        $wgFee = $input['wgFee'];
        // 服务费
        $fee = $input['fee'];
        // 付款方式
        $rentType = $input['rentType'];
        // 租期开始日
        $startDate = $input['startDate'];
        // 租期截止日
        $endDate = $input['endDate'];
        // 房间电表
        $roomD = $input['roomD'];
        // 押金
        $deposit = $input['deposit'];
        // 优惠
        $favorable = $input['favorable'];
        // 优惠描述
        $favorableDes = $input['favorableDes'];
        // 是否有换房余额抵扣
        $isDeduct = $input['is_deduct'];
        // 合同总金额
        $total = $input['total'];
        // 实际支付金额
        $paytotal = $input['paytotal'];
        // 缴定抵扣
        $bookDeposit = $input['bookDeposit'];

        // 根据mobile获取account列表
        $accountList = $DAccount->getAccountList(['mobile' => $mobile]);
        // 如果获取不到
        if (!is_array($accountList)) {
        	$DAccount = D('account');
        	// 插入帐号
        	$account = [];
			$account['realname'] = $realName;
			$account['password'] = md5("123456");
			// 默认密码
			$account['mobile'] = $mobile;
			$account['contact2'] = $contact2;
			$account['card_no'] = $idNo;
			$account['email'] = $email;
			$account['register_time'] = date("Y-m-d H:i:s",time());
			// 插入account并返回account_id
			$accountId = $DAccount->insert($account);
        } else {
        	$account_id = $account_info['id'];
			$account = [];
			$account['realname'] 		= $realName;
			$account['contact2'] 		= $contact2;
			$account['card_no'] 		= $idNo;
			$account['email'] 			= $email;
			// 更新account数据
			$DAccount->update($account, ['id' => $account_id]);
        }
        $res = [];
        $res['success'] = true;
        // 计算起始年月
        $startYear = date('Y', strtotime($startDate));
		$startMonth = date('m', strtotime($endDate));
		// 获取账单号
		$proId = getOrderNo();
		// 获取操作时间
		$createTime = date('Y-m-d H:i:s', time());
		// 获取支付方式
		$rentType = explode('_', $rentType);
		// 押几
		$depositCount = $rentType['0'];
		// 付几
		$payCount = $rentType['1'];
		//房租到期日
		$rentDate = date('Y-m-d', strtotime($cData['start_time'].' +'.$payCount.' month -1 day'));
		// 合租信息
		$hzInfo = [];
		$hzInfo['hzRealname'] 	= $hzRealname;
		$hzInfo['hzMobile'] 	= $hzMobile;
		$hzInfo['hzCardNo'] 	= $hzCardNo;
		$hzInfo = serialize($hzInfo);
		// 合影
		$photo = !empty($photo) ? $post['photo'] : '';
		// 管家id
		$stewardId = $_SESSION['steward_id'];
		// contract数据
		$contract = [];
		$contract['start_time'] = $startDate;
		$contract['end_time'] = $endDate;
		$contract['pro_id'] = $proId;
		$contract['account_id'] = $accountId;
		$contract['room_id'] = $roomId;
		$contract['create_time'] = $createTime;
		$contract['deposit'] = $deposit;
		$contract['wg_fee'] = $wgFee;
		$contract['book_deposit'] = $bookDeposit;
		$contract['period'] = $payCount;
		$contract['steward_id'] = $stewardId;
		$contract['rent_type'] = $rentType;
		$contract['rent_date'] = $rentDate;
		$contract['rent'] = $rent;
		$contract['fee'] = $fee;
		$contract['contact2'] = $contact2;
		$contract['person_count'] = $personCount;
		$contract['cotenant'] = $hzInfo;
		$contract['roomD'] = $roomD;
		$contract['total_fee'] = $total;
		$contract['photo'] = $photo;
		$contract['pay_total'] = $paytotal;
		// pay数据
		$pay = [];
		$pay['pay_status'] = 0;
		$pay['bill_type'] = 2;
		$pay['is_send']	= 0;
		$pay['pro_id'] = $pro_id;
		$pay['account_id'] = $accountId;
		$pay['room_id'] = $roomId;
		$pay['create_time'] = $createTime;
		$pay['input_year'] = $startYear;
		$pay['input_month'] = $startMonth;
		$pay['should_date'] = $createTime;
		$pay['last_date'] = $createTime;
		$pay['favorable'] = $favorable;
		$pay['favorable_des'] = $favorableDes;
		$pay['price'] = $paytotal;
		// 是否使用余额抵扣
		/*
		if (!empty($isDeduct)) {
			$balance = $DAccount->select(['id' => $accountId], ['balance']);//余额
			$contract['balance'] = $balance;
					
			if ($post['paytotal'] > $balance) {
				$post['paytotal'] = $paytotal - $balance;
				$balance = 0; 
			} else {
				$post['paytotal'] = 0;
				$balance = $balance - $post['paytotal'];//如果余额大于应支付金额，应支付0，修改帐号的余额
			}
			$MAccount->where(array("id"=>$id))->save(array("balance"=>$change_balance));
		}
		*/
		// 插入contract数据
		$this->table->insert($contract);
		// 插入pay数据
		$res['id'] = $DPay->insert($pay);
		$res['account_id'] = $account_id;
		return $result;
	}
	/**
	 * [插入一条新的合同]
	 **/
	public function add($post){
		$MAccount = M("account");
		$MContract = M("contract");
		$MPay = M("pay");
		$account_info = $MAccount->where(array("mobile"=>$post['mobile']))->find();

		if ( is_null($account_info) ) {
			//插入帐号
			$data['realname'] 		= $post['realName'];
			$data['password'] 		= md5("123456");//默认密码
			$data['mobile'] 		= $post['mobile'];
			$data['contact2'] 		= $post['contact2'];
			$data['card_no'] 		= $post['idNo'];
			$data['email'] 			= $post['email'];
			$data['register_time'] 	= date("Y-m-d H:i:s",time());

			$id = $MAccount->add($data);
		} else {
			$id = $account_info['id'];
			$save = array();
			$save['realname'] 		= $post['realName'];
			$save['contact2'] 		= $post['contact2'];
			$save['card_no'] 		= $post['idNo'];
			$save['email'] 			= $post['email'];

			$MAccount->where(array('id'=>$id))->save($save);
		}
		$result['result'] = true;
		//判断account_id 是否已经存在了一个未支付的合同账单
		/*$notpay = $MPay->where(array("account_id"=>$id,"pay_status"=>0,'bill_type'=>2))->select();
		if ( count($notpay) > 0 ) {
			$result['result'] 		= false;
			$result['error_msg'] 	= "已存在未支付合同";
			return $result;
		}*/
		$cData['start_time'] 	= $post['startDate'];
		$cData['end_time'] 		= $post['endDate'];
		$start_year 			= date("Y",strtotime($cData['start_time']));
		$start_month 			= date("m",strtotime($cData['start_time']));
		
		$cData['pro_id'] 		= $pData['pro_id'] 		= getOrderNo();//账单号可以用作pro_id
		$cData['account_id'] 	= $pData['account_id'] 	= $id;
		$cData['room_id'] 		= $pData['room_id'] 	= $post['room_id'];
		$pData['pay_status'] 	= 0;
		$cData['create_time'] 	= $pData['create_time'] = date("Y-m-d H:i:s",time());
		$pData['bill_type'] 	= 2;
		$pData['input_year'] 	= $start_year;//批次是合同开始日的年月
		$pData['input_month'] 	= $start_month;
		$pData['should_date'] 	= date("Y-m-d",time());
		$pData['last_date'] 	= date("Y-m-d",time());
		$pData['is_send']		= 0;
		$pData['favorable']     = $post['favorable'];//优惠
		$pData['favorable_des'] = $post['favorable_des'];

		$cData['deposit'] 		= $post['deposit'];
		$cData['wg_fee']		= $post['wg_fee'];
		$cData['book_deposit']	= $post['bookDeposit'];
		$rentType 				= explode("_",$post['rentType']);
		$depositCount 			= $rentType['0'];//押几
		$payCount 				= $rentType['1'];//付几
		$cData['period'] 		= $payCount;
		$cData['steward_id'] 	= !empty($_SESSION['steward_id'])?$_SESSION['steward_id']:0;
		$cData['rent_type'] 	= $post['rentType'];
		//房租到期日
		$cData['rent_date']     = date('Y-m-d',strtotime($cData['start_time'].' +'.$payCount.' month -1 day'));

		/*$cData['rent_date'] 	= correct_date(date("Y",strtotime($start_year."-".$start_month."+".$cData['period']." month")),date("m",strtotime($start_year."-".$start_month."+".$cData['period']." month")),date("d",strtotime($cData['end_time'])));*/ //房租到期日
		$cData['rent'] 			= $post['rent'];
		$cData['fee'] 			= $post['fee'];
		$cData['contact2'] 		= $post['contact2'];
		$cData['person_count'] 	= $post['personCount'];
		$hz_info['hzRealname'] 	= $post['hzRealname'];
		$hz_info['hzMobile'] 	= $post['hzMobile'];
		$hz_info['hzCardNo'] 	= $post['hzCardNo'];
		$cData['cotenant'] 		= serialize($hz_info);
		$cData['roomD'] 		= $post['roomD'];
		$cData['total_fee'] 	= $post['total'];//总金额
		$cData['photo'] 		= !is_null($post['photo'])?$post['photo']:"";//合影

		//是否使用余额抵扣
		if ( !empty($post['is_deduct']) ) {
			$balance = $MAccount->where(array("id"=>$id))->getField("balance");//余额
			$cData['balance'] = $balance;
					
			if ( $post['paytotal'] > $balance ) {
				$post['paytotal'] = $post['paytotal'] - $balance;
				$change_balance = 0; 
			} else {
				$post['paytotal'] = 0;
				$change_balance = $balance - $post['paytotal'];//如果余额大于应支付金额，应支付0，修改帐号的余额
			}
			$MAccount->where(array("id"=>$id))->save(array("balance"=>$change_balance));
		}

		$cData['pay_total'] = $pData['price'] = $post['paytotal'];//应支付金额

		$MContract->add($cData); //再插入contract表
		$result['id'] = $MPay->add($pData);	//先插入pay表中
		$result['account_id'] = $id;
		return $result;
	}

	/**
	* [根据用户id获取最新合同]
	**/
	public function getNewContractById($account_id){
		$where['account_id'] = $account_id;
		$where['contract_status'] = 1;//正常签约
		$order = "id desc";
		$result = $this->table->where($where)->order($order)->find();
		return $result;
	}

	/**
	* [获取未支付合同]
	**/
	public function getNotPayContract($account_id){
		$pay_info = M("pay")
				->alias('p')
				->field('c.*,p.*')
				->join("lewo_contract c ON p.pro_id=c.pro_id")
				->where(array("p.account_id"=>$account_id,"p.bill_type"=>C("bill_type_ht"),"p.pay_status"=>0,"is_show"=>1))
				->order("p.id desc")
				->find();

        $MHouses = M("houses");
        $MArea = M("area");
        $MRoom = M("room");
        $MAccount = M("account");
        if ( !is_null($pay_info) ) {
        	$house_code = $MRoom->where(array("id"=>$pay_info['room_id']))->getField("house_code");
        	$realname = $MAccount->where(array('id'=>$account_id))->getField("realname");
	        //根据house_code 判断广州或重庆
	        $area_id = $MHouses->where(array("house_code"=>$house_code))->getField("area_id");
	        $city_id = $MArea->where(array("id"=>$area_id))->getField("city_id");
	        $city_name = C("city_id")[$city_id];
	        $pay_info['bill_info'] = $realname."合同".$city_name."账单";
        } else {
        	return false;
        }

        return $pay_info;
	}
}

?>