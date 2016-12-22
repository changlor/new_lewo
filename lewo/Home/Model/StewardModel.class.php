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
        // 获取订单id
        $proId = $input['proId'];
        // 获取模型实例
        $DPay = D('pay'); $DHouses = D('houses'); $DRoom = D('room');
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
        if (is_numeric($payType)) {
        	return [false, '数据错误！'];
        }
        // 获取payMoney
        $payMoney = $input['payMoney'];
        if (is_numeric($payMoney)) {
        	return [false, '数据错误！'];	
        }
        // 获取payTime
        $payTime = date('Y-m-d H:i:s');
        // 管家代收是已支付
        $payStatus = 1;
        // 合同账单
        $contract = [];
        // 实际交付押金
        $contract['actual_deposit'] = $input['actualDeposit'];
        // 实际交付租金
        $contract['actual_rent'] = $input['actualRent'];
        // 获取payList
        $payList = $DPay->getPayList(['pro_id' => $proId]);
        // 账单类型
        $billType = $payList['bill_type'];
        // 租客id
        $accountId = $payList['account_id'];
        // 房间id
        $roomId = $payList['room_id'];
        // 修改日志
        $modifyLog  = $payList['modify_log'];
        $modify['修改人'] = '管家(' . $_SESSION['steward_nickname'] . '|' . $_SESSION['steward_user'] . ')时间:' . date('Y-m-d H:i:s') . '代收';
        $modify['支付状态'] = '未支付-><b style="color: green;">已支付</b>';
        $modify['支付方式'] = C('pay_type')[$pay_type];
        $modify['支付金额'] = $pay_info['pay_money'] . '-><b style="color: green;">' . $pay_money . '</b>';
        $modify['支付时间'] = '<b style="color: green;">' . $pay_time . '</b>';
        $modify['备注'] = '管家代收';
        // 拼接字符
        foreach ($modify as $key => $value) {
            $modifyLog .= '<br>' . $key . ': ' . $value;
        }
        // 如果实际收取金额大于支付金额，则返回数据错误
        if ($payMoney > $payList['price']) {
        	return [false, '输入的金额大于应付金额'];
        }
        // 如果实际收取金额小于支付金额，则生成欠款
        if ($payMoney < $payList['price']) {           
            switch ($billType) {
                case 2:
                case 7:
                    $due_bill_type = 7;
                    break;
                case 3:
                case 8:
                    $due_bill_type = 8;
                    break;
                default:
                    $due_bill_type = 9;
                    break;
            }

            $due_price = $pay_info['price'] - $pay_money;
            $lewo_pay_due = [
                'account_id' => $account_id,
                'room_id' => $room_id,
                'bill_type' => $due_bill_type,
                'input_year' => $pay_info['input_year'],
                'input_month' => $pay_info['input_month'],
                'should_date' => $pay_info['should_date'],
                'last_date' => $pay_info['last_date'],
                'input_month' => $pay_info['input_month'],
                'input_year' => $pay_info['input_year'],
                'price' => $due_price,
                'is_send' => 1,
            ];
            $res = $DPay->create_bill($lewo_pay_due);
            if (!$res) {
                $this->error('欠款账单生成失败!');
            }
        }
        // 修改房屋合同信息
        switch ($bill_type) {
            case 2:
                // 合同
                $DRoom = D('room');
                // roomstatus
                // 0 未租, 1 缴纳定金, 2 已住
                $DRoom->setRoomStatus($room_id, 2);
                $DRoom->setRoomPerson($room_id, $account_id);
                //修改合同正常
                $lewo_contract = [
                    'actual_rent' => $contract_bill['actual_rent'],
                    'actual_deposit' => $contract_bill['actual_deposit'],
                    'contract_status' => 1,
                ];
                M('contract')->where(['pro_id' => $pro_id])->save($lewo_contract);
            break;
            case 3:
                // 日常
                // 修改合同信息
                $charge_info = M('charge_bill')->where(['pro_id' => $pro_id])->find();
                $rent_date = $charge_info['rent_date_to']; //房租到期日
                $MContract->where([
                    'account_id' => $account_id, 
                    'room_id' => $room_id, 
                    'contract_status'=>1
                ])->save(['rent_date' => $rent_date]);
            break;
        }
        dump($payList);
        exit;
        /*
        // 获取模型实例
        $MContract  = M('contract'); $MPay = M('pay'); $DPay = D('pay');
        M()->startTrans();
        // 获取支付信息
        $pay_info = $MPay->where(['pro_id' => $pro_id])->find();
        // 所有类型账单通用数据
        $pay_type = I('pay_type');
        $pay_money = I('actual_price');
        $pay_time = date('Y-m-d H:i:s');
        // 管家代收是已支付
        $pay_status = 1;
        // 合同账单
        $contract_bill = [
            'actual_deposit' => I('actual_deposit'),
            'actual_rent' => I('actual_rent'),
        ];
        
        // 账单类型
        $bill_type = $pay_info['bill_type'];
        // 租客id
        $account_id = $pay_info["account_id"];
        // 房间id
        $room_id = $pay_info["room_id"];
        // 修改日志
        $modify_log  = $pay_info['modify_log'];
        $modify['修改人'] = '管家(' . $_SESSION['steward_nickname'] . '|' . $_SESSION['steward_user'] . ')时间:' . date('Y-m-d H:i:s') . '代收';
        $modify['支付状态'] = '未支付-><b style="color: green;">已支付</b>';
        $modify['支付方式'] = C('pay_type')[$pay_type];
        $modify['支付金额'] = $pay_info['pay_money'] . '-><b style="color: green;">' . $pay_money . '</b>';
        $modify['支付时间'] = '<b style="color: green;">' . $pay_time . '</b>';
        $modify['备注'] = '管家代收';
        $modify_log = '';
        foreach ($modify as $key => $value) {
            $modify_log .= '<br>' . $key . ': ' . $value;
        }
        */
        
        // 未支付和未付完 则生成欠款
        if ($pay_money < $pay_info['price'] && $pay_status == 1) {           
            switch ($bill_type) {
                case 2:
                case 7:
                    $due_bill_type = 7;
                    break;
                case 3:
                case 8:
                    $due_bill_type = 8;
                    break;
                default:
                    $due_bill_type = 9;
                    break;
            }

            $due_price = $pay_info['price'] - $pay_money;
            $lewo_pay_due = [
                'account_id' => $account_id,
                'room_id' => $room_id,
                'bill_type' => $due_bill_type,
                'input_year' => $pay_info['input_year'],
                'input_month' => $pay_info['input_month'],
                'should_date' => $pay_info['should_date'],
                'last_date' => $pay_info['last_date'],
                'input_month' => $pay_info['input_month'],
                'input_year' => $pay_info['input_year'],
                'price' => $due_price,
                'is_send' => 1,
            ];
            $res = $DPay->create_bill($lewo_pay_due);
            if (!$res) {
                $this->error('欠款账单生成失败!');
            }
        }
        // 修改房屋合同信息
        switch ($bill_type) {
            case 2:
                // 合同
                $DRoom = D('room');
                // roomstatus
                // 0 未租, 1 缴纳定金, 2 已住
                $DRoom->setRoomStatus($room_id, 2);
                $DRoom->setRoomPerson($room_id, $account_id);
                //修改合同正常
                $lewo_contract = [
                    'actual_rent' => $contract_bill['actual_rent'],
                    'actual_deposit' => $contract_bill['actual_deposit'],
                    'contract_status' => 1,
                ];
                M('contract')->where(['pro_id' => $pro_id])->save($lewo_contract);
            break;
            case 3:
                // 日常
                // 修改合同信息
                $charge_info = M('charge_bill')->where(['pro_id' => $pro_id])->find();
                $rent_date = $charge_info['rent_date_to']; //房租到期日
                $MContract->where([
                    'account_id' => $account_id, 
                    'room_id' => $room_id, 
                    'contract_status'=>1
                ])->save(['rent_date' => $rent_date]);
            break;
        }

        // lewo_pay表修改内容
        $lewo_pay = [
            'pay_type' => $pay_type,
            'pay_status' => $pay_status,
            'pay_time' => $pay_time,
            'pay_money' => $pay_money,
            'modify_log' => $modify_log,
        ];
            
        $res = M('pay')->where(['pro_id' => $pro_id])->save($lewo_pay);
        if ($res == false){
            M()->rollback();
            $this->error('修改账单失败!', U('Steward/steward_collection', ['pro_id' => $pro_id]),3);
        } else {
            M()->commit();
            $this->success('管家代收成功', U('Steward/allbills'),3);
        }
	}
}

?>