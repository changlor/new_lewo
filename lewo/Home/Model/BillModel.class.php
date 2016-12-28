<?php
namespace Home\Model;
use Think\Model;
/**
 * bill模型类
 */
class BillModel extends BaseModel {
    protected $table;
    protected $tableName = 'pay';

    public function __construct()
    {
        parent::__construct();
        $this->table = M($this->tableName);
    }

    public function getBills($where)
    {
        $steward_id = parent::steward_id;
        if (!is_numeric($steward_id)) {
            $this->login(); die();
        }
    }

    public function select($where, $field)
    {
        $field = empty($field) ? '' : $field;
        $where = empty($where) ? '' : $where;
        $field = is_array($field) ? implode(',', $field) : $field;
        return $this->table->field($field)->where($where);
    }

    public function join($joinTable, $where, $field)
    {
        return parent::join($this->table, $joinTable)->field($field)->where($where);
    }

    public function getStewardCollectionBill($input)
    {
        // 获取模型实例
        $DPay = D('pay');
        // 获取proId
        $proId = $input['proId'];

        if (!is_numeric($proId)) {
            return parent::response([false, '访问出错！']);
        }
        // 有问题的版本(代替改)
        $joinTable = [
            // 'pay.pro_id' => 'contract.pro_id',
            'pay(contract)' => 'pro_id(pro_id)',
            'pay(account)' => 'account_id(id)',
            'pay(room)' => 'room_id(id)',
            'pay(charge_bill)' => 'pro_id(pro_id)',
            'room(houses)' => 'house_code(house_code)',
            'houses(area)' => 'area_id(id)',
        ];
        $field = [
            // account
            'lewo_account.realname',
            // area
            'lewo_area.area_name', 'lewo_area.id',
            // contract
            'lewo_contract.pro_id',
            'lewo_contract.deposit', 'lewo_contract.rent',
            'lewo_contract.fee', 'lewo_contract.wg_fee',
            // charge_bill
            'lewo_charge_bill.pro_id',
            'lewo_charge_bill.water_fee',
            'lewo_charge_bill.room_energy_fee',
            'lewo_charge_bill.wx_fee',
            'lewo_charge_bill.wx_des',
            'lewo_charge_bill.rubbish_fee',
            'lewo_charge_bill.energy_fee',
            'lewo_charge_bill.gas_fee',
            'lewo_charge_bill.rent_fee',
            'lewo_charge_bill.wgfee_unit',
            'lewo_charge_bill.service_fee',
            // room
            'lewo_room.room_code', 'lewo_room.house_code',
            // houses
            'lewo_houses.area_id',
            'lewo_houses.building',
            'lewo_houses.floor',
            'lewo_houses.door_no',
            //pay
            'lewo_pay.price', 'lewo_pay.bill_type',
        ];
        $payList = $this->join($joinTable, ['lewo_pay.pro_id' => $proId], $field)->find();
        $payClassify['合同'] = [
            'actual_deposit' => ['押金', $payList['deposit'], 'need_modify'],
            'actual_rent' => ['房租', $payList['rent'], 'need_modify'],
            'fee' => ['服务费', $payList['fee']],
            'wg_fee' => ['物业费', $payList['wg_fee']],
            'should_price' => ['总金额', $payList['price']],
            'actual_price' => ['实收金额', $payList['price']],
        ];

        $payClassify['日常'] = [
            'rent_fee' => ['房租', $payList['rent_fee']],
            'total_daily_room_fee' => [
                '水电气',
                $payList['room_energy_fee'] +
                $payList['water_fee'] +
                $payList['energy_fee'] +
                $payList['gas_fee'] +
                $payList['rubbish_fee'],
            ],
            'wx_fee' => ['欠费', $payList['wx_fee']],
            'wx_des' => ['欠费描述', $payList['wx_des'], 'readonly'],
            'wgfee_unit' => ['物管费', $payList['wgfee_unit']],
            'service_fee' => ['服务费', $payList['service_fee']],
            'should_price' => ['总金额', $payList['price']],
            'actual_price' => ['实收金额', $payList['price'], 'update_price'],
        ];
        $payClassify['others'] = [
            'should_price' => ['总金额', $payList['price']],
            'actual_price' => ['实收金额', $payList['price'], 'update_price'],
        ];

        $accountInfo = [
            'realname' => ['租客', $payList['realname']],
            'area_name' => ['小区楼层', $payList['area_name'] . '(' . $payList['building'] . '-' . $payList['floor'] . '-' . $payList['door_no'] . ')'],
        ];

        $payList['pay_classify'] = isset($payClassify[C('bill_type')[$payList['bill_type']]])
        ? $payClassify[C('bill_type')[$payList['bill_type']]]
        : $payClassify['others'];
        $payList['accountInfo'] = $accountInfo;

        return parent::response([true, '', $payList]);
    }

    public function putStewardCollectionBill($input)
    {
        // 获取模型实例
        $DPay = D('pay');
        $DHouses = D('houses');
        $DRoom = D('room');
        $DContract = D('contract');
        $DChargeBill = D('charge_bill');
        // 获取当前操作的管家id
        $currentStewardId = $_SESSION['steward_id'];
        // 获取订单id
        $proId = $input['proId'];
        // 获取roomId
        $roomId = $DPay->selectField(['pro_id' => $proId], 'room_id');
        // 获取houseId
        $houseId = $DRoom->selectField(['id' => $roomId], 'house_id');
        // 获取stewardId
        $stewardId = $DHouses->selectField(['id' => $houseId], 'steward_id');
        // 如果获取到的stewardId不等于当前操作的管家id，则为非法操作
        if ($stewardId != $currentStewardId) {
            return parent::response([false, '非法操作，权限错误！']);
        }
        // 获取isSend
        // --如果isSend == 0，说明该账单代收是未发送账单，为非法操作
        $isSend = D('Pay')->selectField(['pro_id' => $proId], 'is_send');
        if ($isSend == 0) {
            return parent::response([false, '非法操作，权限错误！']);
        }
        // 获取payType
        $payType = $input['payType'];
        if (!is_numeric($payType)) {
            return parent::response([false, '支付类型数据错误！']);
        }
        // 获取payMoney
        $payMoney = $input['payMoney'];
        if (!is_numeric($payMoney) || $payMoney == 0) {
            return parent::response([false, '总金额数据错误！']); 
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
            return parent::response([false, '押金数据类型错误']);
        }
        if (!is_numeric($actualRent) && $billType ==2) {
            return parent::response([false, '租金数据类型错误']);
        }
        // 租客id
        $accountId = $payList['account_id'];
        // 房间id
        $roomId = $payList['room_id'];
        // 如果实际收取金额大于支付金额，则返回数据错误
        if ($payMoney > $payList['price']) {
            return parent::response([false, '输入的金额大于应付金额']);
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
            $duePay['price'] = $duePrice;
            // 欠款账单默认为已发送，1为已发送
            $duePay['is_send'] = 1;
            // 插入欠款账单
            $res = $DPay->insertPay($duePay);
            if (!$res) {
                return parent::response([false, '欠款账单生成失败！']);
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
                $filters = [];
                $filters['account_id'] = $accountId;
                $filters['room_id'] = $roomId;
                $filters['contract_status'] = 1;
                $res = $DContract->updateContract($filters, ['rent_date' => $rentDate]);
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
        $payUpdateInfo['steward_id'] = $currentStewardId;
        $payUpdateInfo['modify_log'] = $modifyLog;
        // 更新pay表
        $res = $DPay->updatePay(['pro_id' => $proId], $payUpdateInfo);
        if (!$res) {
            return parent::response([false, '修改账单失败!']);
            // $this->error('修改账单失败!', U('Steward/steward_collection', ['pro_id' => $pro_id]),3);
        } else {
            return parent::response([true, '管家代收成功']);
            // $this->success('管家代收成功', U('Steward/allbills'),3);
        }
    }
}