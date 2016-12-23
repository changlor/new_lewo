<?php
namespace Home\Model;
use Think\Model;
/**
* [支付表]
*/
class StewardModel extends BaseModel {

	private $table;
	protected $tableName = 'admin_user';
	private $stewardId;

	public function __construct()
	{
		parent::__construct();
		$this->init();
	}

	private function init()
	{
		$this->table = M($this->tableName);
		$this->stewardId = $_SESSION['steward_id'];
		if (!is_numeric($this->stewardId)) {
			return [false, '非法操作，未登录！'];
		}
	}
	
	public function select($where, $field)
	{
		$field = empty($field) ? '' : $field;
		$where = empty($where) ? '' : $where;
		$field = implode(',', $field);
		return $this->table->field($field)->where($where);
	}

	public function insert($pay)
	{
		return $this->table->add($pay);
	}
	
	public function stewardCollection($input)
	{
		// 获取模型实例
        $DPay = D('pay');
        $DHouses = D('houses');
        $DRoom = D('room');
        $DContract = D('contract');
        $DChargeBill = D('charge_bill');
        // 获取订单id
        $proId = $input['proId'];
        // 获取roomId
        $roomId = $DPay->selectField(['pro_id' => $proId], 'room_id');
        // 获取houseId
        $houseId = $DRoom->selectField(['id' => $roomId], 'house_id');
        // 获取stewardId
        $stewardId = $DHouses->selectField(['id' => $houseId], 'steward_id');
        if ($stewardId != $this->stewardId) {
        	return [false, '非法操作，权限错误！'];
        }
        // 获取isSend
        // --如果isSend == 0，说明该账单代收是未发送账单，为非法操作
        $isSend = D('Pay')->selectField(['pro_id' => $proId], 'is_send');
        if ($isSend == 0) {
        	return [false, '非法操作，权限错误！'];
        }
        // 获取payType
        $payType = $input['payType'];
        if (!is_numeric($payType)) {
        	return [false, '支付类型数据错误！'];
        }
        // 获取payMoney
        $payMoney = $input['payMoney'];
        if (!is_numeric($payMoney) || $payMoney == 0) {
        	return [false, '总金额数据错误！'];	
        }
        // 获取payTime
        $payTime = date('Y-m-d H:i:s');
        // 管家代收是已支付
        // --2为管家代收
        $payStatus = 2;
        // 实际交付押金
        $actualDeposit = $input['actualDeposit'];
        // 实际交付租金
        $actualRent = $input['actualRent'];
        // 获取payList
        $payList = $DPay->getPayList(['pro_id' => $proId]);
        // 账单类型
        $billType = $payList['bill_type'];
        if (!is_numeric($actualDeposit) && $billType == 2) {
        	return [false, '押金数据类型错误'];
        }
        if (!is_numeric($actualRent) && $billType ==2) {
        	return [false, '租金数据类型错误'];
        }
        // 租客id
        $accountId = $payList['account_id'];
        // 房间id
        $roomId = $payList['room_id'];
        // 如果实际收取金额大于支付金额，则返回数据错误
        if ($payMoney > $payList['price']) {
        	return [false, '输入的金额大于应付金额'];
        }
        // 如果实际收取金额小于支付金额，则生成欠款
        if ($payMoney < $payList['price']) {           
            // 判断账单类型
            switch ($billType) {
            	// 合同类型
                case 2:
                case 7:
                	// 欠款合同账单
                    $dueBillType = 7;
                    break;
                // 日常类型
                case 3:
                case 8:
                	// 欠款日常账单
                    $dueBillType = 8;
                    break;
                default:
                	// 其他欠款账单
                    $dueBillType = 9;
                    break;
            }
            // 欠款金额
            $duePrice = $payList['price'] - $payMoney;
            // 欠款账单数据
            $duePay = [];
            $duePay['account_id'] = $accountId;
            $duePay['room_id'] = $roomId;
            $duePay['bill_type'] = $dueBillType;
            $duePay['input_year'] = $payList['input_year'];
            $duePay['input_month'] = $payList['input_month'];
            $duePay['should_date'] = $payList['should_date'];
            $duePay['last_date'] = $payList['last_date'];
            $duePay['price'] = $payList['price'];
            // 欠款账单默认为已发送，1为已发送
            $duePay['is_send'] = 1;
            // 插入欠款账单
            $res = $DPay->insertPay($duePay);
            if (!$res) {
            	return [false, '欠款账单生成失败！'];
                // $this->error('欠款账单生成失败!');
            }
        }
        // 修改房屋合同信息
        switch ($billType) {
            // 合同类型
            case 2:
                // roomStatus，0 未租, 1 缴纳定金, 2 已住
                $DRoom->updateRoom(['id' => $room_id], ['status' => 2]);
                // 修改
                $DRoom->updateRoom(['id' => $room_id], ['account_id' => $accountId]);
                // 修改合同正常
                $contractUpdateInfo = [];
                $contractUpdateInfo['actual_rent'] = $actualRent;
                $contractUpdateInfo['actual_deposit'] = $actualDeposit;
                // 1为正常类型
                $contractUpdateInfo['contract_status'] = 1;
                $DContract->updateContract(['pro_id' => $proId], $contractUpdateInfo);
            break;
            // 日常类型
            case 3:
                // 修改合同信息
            	$chargeBillList = $DChargeBill->selectChargeBill(['pro_id' => $proId]);
            	// 房租到期日
                $rentDate = $chargeBillList['rent_date_to'];
                // 更新合同到期日
                $contractFilters = [];
                $contractFilters['account_id'] = $accountId;
                $contractFilters['room_id'] = $roomId;
                $contractFilters['contract_status'] = 1;
                $res = $DContract->updateContract($contractFilters, ['rent_date' => $rentDate]);
            break;
        }

        // 修改日志
        $modifyLog  = $payList['modify_log'];
        $modify['修改人'] = '管家(' . $_SESSION['steward_nickname'] . '|' . $_SESSION['steward_user'] . ')时间:' . date('Y-m-d H:i:s') . '代收';
        $modify['支付状态'] = '未支付-><b style="color: green;">已支付</b>';
        $modify['支付方式'] = C('pay_type')[$payType];
        $modify['支付金额'] = $payList['pay_money'] . '-><b style="color: green;">' . $payMoney . '</b>';
        $modify['支付时间'] = '<b style="color: green;">' . $payTime . '</b>';
        $modify['备注'] = '管家代收';
        // 拼接字符
        foreach ($modify as $key => $value) {
            $modifyLog .= '<br>' . $key . ': ' . $value;
        }

        // pay表修改数据
        $payUpdateInfo = [];
        $payUpdateInfo['pay_type'] = $payType;
        $payUpdateInfo['pay_status'] = $payStatus;
        $payUpdateInfo['pay_time'] = $payTime;
        $payUpdateInfo['pay_money'] = $payMoney;
        $payUpdateInfo['modify_log'] = $modifyLog;
        // 更新pay表
        $res = $DPay->updatePay(['pro_id' => $proId], $payUpdateInfo);
        if (!$res) {
        	return [false, '修改账单失败!'];
            // $this->error('修改账单失败!', U('Steward/steward_collection', ['pro_id' => $pro_id]),3);
        } else {
        	return [true, '管家代收成功'];
            // $this->success('管家代收成功', U('Steward/allbills'),3);
        }
	}
}