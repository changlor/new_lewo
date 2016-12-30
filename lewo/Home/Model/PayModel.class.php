<?php
namespace Home\Model;
use Think\Model;
/**
* [支付表]
*/
class PayModel extends BaseModel {

	protected $table;
protected $tableName = 'pay';
	protected $field = [
		'pro_id', 'account_id', 'room_id', 
		'price',
		'pay_money', 'pay_status',
		'bill_type', 'bill_des',
		'is_show', 'is_send',
		'create_time', 'should_date', 'last_date',
		'input_year', 'input_month',
		'favorable', 'favorable_des',
	];

	public function __construct()
	{
		parent::__construct();
		$this->table = M($this->tableName);
	}
	
	public function select($where, $field)
	{
		$field = empty($field) ? '' : $field;
		$where = empty($where) ? '' : $where;
		$field = is_array($field) ? implode(',', $field) : $field;
		return $this->table->field($field)->where($where);
	}

	public function selectField($where, $field)
	{
		return $this->select($where)->getField($field);
	}

	public function selectPay($where, $field)
	{
		return $this->select($where, $field)->find();
	}

	public function update($where)
    {
        $field = empty($field) ? '' : $field;
        $where = empty($where) ? '' : $where;
        $field = is_array($field) ? implode(',', $field) : $field;
        return $this->table->where($where);
    }

    public function updatePay($where, $updateInfo)
    {
        return $this->update($where)->save($updateInfo);
    }

	public function insert($pay)
	{
		return $this->table->add($pay);
	}

    public function join($joinTable, $where, $field)
    {
        return parent::join($this->table, $joinTable)->field($field)->where($where);
    }
	
	public function insertPay($pay)
	{
		return $this->insert($pay);
	}

	public function getPayList($where, $field)
	{
		return $this->select($where, $field)->find();
	}

	public function getPayLists($where, $field)
	{
		return $this->select($where, $field)->select();
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
		$pdata['modify_log']    = ' ';
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

		$bill_list = $this->table
					->alias('p')
					->field('a.realname,p.*,r.house_code')
					->join('lewo_account a ON a.id=p.account_id','left')
					->join('lewo_room r ON r.id=p.room_id','left')
					->where($where)
					->order(' p.create_time desc,p.input_year desc,p.input_month desc')
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

    public function deleteDepositBill($input)
    {
        // 获取模型实例
        $DSchedule = D('schedule'); $DRoom = D('room');
        // 获取scheduleId
        $scheduleId = I('get.scheduleId');
        if (!$DSchedule->has(['id' => $scheduleId])) {
            return parent::response([false, '不存在该待办任务！']);
        }
        $roomId = $DSchedule->selectField(['id' => $scheduleId], 'room_id');
        // 修改此记录为已处理
        $DSchedule->updateSchedule(['id' => $scheduleId], ['is_finish' => 1]);
        // 修改房屋状态
        $DRoom->updateRoom(['id' => $roomId], ['status' => 0, 'account_id' => 0]);
        return parent::response([true, '']);
    }

	public function postDepositBill($input)
	{
		// 获取模型实例
		$DAccount = D('account'); $DSchedule = D('schedule'); $DRoom = D('room');
		// 获取管家stewardId
        $stewardId = $_SESSION['steward_id'];
        if (!is_numeric($stewardId)) {
        	return parent::response([false, '权限出错！']);
        }
		// 获取缴定后约定来住的时间
		$appointTime = $input['appointTime'];
		if (strtotime($appointTime) < time()) {
            return parent::response([false, '约定时间不能少于现在的时间']);
        }
        // 获取房间roomId
        $roomId = $input['roomId'];
        if (!is_numeric($roomId)) {
        	return parent::response([false, '房间信息有误！']);
        }
        // 获取房间状态
        $roomStatus = $DRoom->selectField(['id' => $roomId], 'status');
        // 如果房屋状态为已签约，则说明已签约过了，不能再继续签约了
        if ($roomStatus == 1) {
        	return parent::response([false, '房屋已经是签约状态了！']);
        }
        // 获取缴定金额
        $money = $input['money'];
        if (!is_numeric($money)) {
        	return parent::response([false, '缴定金额类型错误！']);
        }
        // 获取租客realName
        $realName = trim($input['realName']);
        if (empty($realName)) {
        	return parent::response([false, '租客姓名不能为空！']);
        }
        // 生成proId
        $proId = getOrderNo();
        // 获取备注
        $msg = trim($input['msg']);
        // 获取租客电话
        $mobile = $input['mobile'];
        $pattern = '/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/i';
        if (!preg_match($pattern, $mobile)) {
        	return parent::response([false, '租客手机格式不对！']);
        }
        // 设置租客默认密码（如果租客未注册过系统）
        $passward = md5(123456);
        // 设置租客默认注册时间（如果租客未注册过系统）
        $registerTime = date('Y-m-d H:i:s', time());
        // 通过手机查询是否有注册过系统，成功则返回accountId
        $accountId = $DAccount->selectField(['mobile' => $mobile], 'id');
        // 不是数字说明未注册过系统
        if (!is_numeric($accountId)) {
        	// 插入account数据
        	$account = [];
        	$account['realname'] = $realName;
        	$account['passward'] = $passward;
        	$account['mobile'] = $mobile;
        	$account['registerTime'] = $registerTime;
        	// 插入并返回accountId
        	$accountId = $DAccount->insertAccount($account);
        }
        // 缴定后插入代办数据
        $schedule = [];
        $schedule['room_id'] = $roomId;
        $schedule['money'] = $money;
        $schedule['mobile'] = $mobile;
        $schedule['account_id'] = $accountId;
        // 代办表类型
        $schedule['schedule_type'] = 4;
        // 流程状态
        $schedule['status'] = 1;
        $schedule['appoint_time'] = $appointTime;
        $schedule['msg'] = $msg;
        $schedule['steward_id'] = $stewardId;
        $schedule['admin_type'] = C('admin_type_gj');
        $DSchedule->insertSchedule($schedule);
        // 插入pay表
        $pay = [];
        $pay['account_id'] = $accountId;
        $pay['room_id'] = $roomId;
        // 账单类型，1为定金
        $pay['bill_type'] = 1;
        // 支付类型 管家代收
        $pay['pay_type'] = 11;
		$pay['price'] = $money;
		$pay['pro_id'] = $proId;
		$pay['create_time'] = date('Y-m-d H:i:s', time());
		$pay['input_year'] = date('Y', time());
		$pay['input_month'] = date('m', time());
		$pay['should_date'] = date('Y-m-d H:i:s', time());
		$pay['last_date'] = date('Y-m-d H:i:s', time());
        $pay['steward_id'] = $stewardId;
        $pay['pay_money'] = $money;
        $pay['pay_time'] = date('Y-m-d H:i:s', time());
		// 支付状态，0为未支付
		$pay['pay_status'] = 2;
		// 定金账单默认为已发送，无需发送提醒租客
		$pay['is_send'] = 1;
		$pay['modify_log'] = ' ';
		$this->insertPay($pay);
		// 修改房屋状态为1，已缴定
		$affectedRows = $DRoom->updateRoom(['id' => $roomId], ['account_id' => $accountId, 'status' => 1]);
		if (!($affectedRows > 0)) {
			return parent::response([false, '房屋状态修改出错！']);
		}
		return parent::response([true, '']);
	}

	/*
	 * author: changle
	 * desc: get stedward bills depends on bills
	 */
	public function getBills($input)
    {
        // 获取模型实例
        $DContract = D('contract');
        // 获取管家stewardId
        $stewardId = $_SESSION['steward_id'];
        if (!is_numeric($stewardId)) {
            return parent::response([false, '权限出错！']);
        }
        // 获取搜索关键词
        $keyWord = $input['keyWord'];
        if (!empty($keyWord) && strpos($keyWord, '-') !== false) {
            $keyWordChips = explode('-', $keyWord);
            $building = isset($keyWordChips[0]) ? $keyWordChips[0] : '';
            $floor = isset($keyWordChips[1]) ? $keyWordChips[1] : '';
            $doorNo = isset($keyWordChips[2]) ? $keyWordChips[2] : '';
        }
        if (!empty($keyWord) && strpos($keyWord, '-') === false) {
            // 模糊查询语句
            $fuzzyEnquiry = "lewo_account.realname LIKE '%". $keyWord ."%' OR lewo_houses.house_code LIKE '%".$keyWord."%' OR lewo_area.area_name LIKE '%".$keyWord."%' ";
        }
        // 获取查找类型
        $type = empty($input['type']) ? 'unpaid' : $input['type'];
        // 选择pay表数据
        // --关联相关表
        $joinTable = [
            'pay(room)' => 'room_id(id)',
            'room(houses)' => 'house_id(id)',
            'pay(account)' => 'account_id(id)',
            'houses(area)' => 'area_id(id)',
            'pay(charge_bill)' => 'pro_id(pro_id)',
            'pay(contract)' => 'pro_id(pro_id)',
        ];
        // --过滤条件
        $filters = [
            'lewo_pay.is_show' => 1,
            'lewo_pay.is_send' => 1,
            'lewo_houses.steward_id' => $stewardId,
            'lewo_houses.building' => $building,
            'lewo_houses.floor' => $floor,
            'lewo_houses.door_no' => $doorNo,
            '_string' => $fuzzyEnquiry,
        ];
        $filters['lewo_pay.pay_status'] = $type == 'unpaid' ? ['neq', 1] : 1;
        $filters = array_filter($filters, function ($value) {
            return $value === 0 || !!$value;
        });
        // --选取字段
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
        $bills = $this->join($joinTable, $filters, $field)->order('lewo_pay.pay_status asc, lewo_pay.last_date asc, lewo_pay.input_year desc, lewo_pay.input_month desc')->select();
		foreach ($bills as $key => $value) {
			$bills[$key]['bill_type'] = C('bill_type')[$value['bill_type']];
			$bills[$key]['rent_type'] = '压' . str_replace('_', '付', $value['rent_type']);
			$bills[$key]['bill_des'] = empty($value['bill_des']) ? '无' : $value['bill_des'];
			$bills[$key]['total_daily_room_fee'] = $value['room_energy_fee'] + $value['water_fee'] + $value['energy_fee'] + $value['gas_fee'] + $value['rubbish_fee'];
			$bills[$key]['count_down_days'] = -floor((time() - strtotime($value['last_date'])) / 60 /60 /24);
		}
		return parent::response(['true', '', $bills]);
	}
}
