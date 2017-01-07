<?php
namespace Admin\Model;
use Think\Model;
/**
* [待办数据层]
*/
class ScheduleModel extends BaseModel{
	private $table;
	protected $tableName = 'schedule';

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

	public function selectSchedule($where, $field){
		return $this->select($where, $field)->select();
	}

	public function join($joinTable, $where, $field)
    {
        return parent::join($this->table, $joinTable)->field($field)->where($where);
    }

    public function has($where)
	{
		$keys = array_keys($where);
		$field = $keys[0];
		$row = $this->selectField($where, $field);
		return $row === '0' || $row === 0 || !empty($row);
	}

    public function update($where, $data)
	{
		return $this->table->where($where)->save($data);
	}

	public function updateSchedule($where, $updateInfo)
	{
		return $this->update($where, $updateInfo);
	}

    public function finishedSchedule($input)
	{
		// 获取scheduleId
		$scheduleId = $input['scheduleId'];
		if (!$this->has(['id' => $scheduleId])) {
			return parent::response([false, '不存在该待办事件！']);
		}
		// 将该scheduleId对应的待办事件状态修改为已完成
		$scheduleUpdateInfo = [
			'is_finish' => 1
		];
		// 
		if ($this->has(['is_finish' => 1])) {
			return parent::response([false, '该待办事件已完成！']);
		}
		$affectedRows = $this->updateSchedule(['id' => $scheduleId], $scheduleUpdateInfo);
		if (!$affectedRows) {
			return parent::response([false, '待办状态修改失败']);
		} else {
			return parent::response([true, '']);
		}
	}

	/**
	* [获取待办列表]
	**/
	public function getScheduleList(){
		$DRoom = D("room");
		$DAccount = D("account");

		$field = [
			// schedule
			'schedule.account_id', 'schedule.create_time', 
			'schedule.schedule_type', 'schedule.status', 
			'schedule.room_id', 'schedule.is_finish', 
			'schedule.steward_id', 'schedule.admin_type', 
			'schedule.is_delete', 'schedule.id',
			// room
			'room.house_code',
			'room.room_sort', 'room.bed_code',
			// account
			'account.realname',
			// admin_user
			'admin_user.nickname' => 'steward_nickname'
		];

		$list = $this->table
		->alias('schedule')
		->field($field)
		->join('lewo_room room ON room.id = schedule.room_id')
		->join('lewo_account account ON account.id = schedule.account_id')
		->join('lewo_admin_user admin_user ON admin_user.id = schedule.steward_id')
		->where(['is_finish'=>0])
		->select();

		foreach( $list AS $key=>$val ){
			//待办工作类型 1：退房 2：转房 3：换房 4：缴定 5:例行打款
			switch ($val['schedule_type']) {
				case 1:
					switch ($val['status']) {
						case 1:
							$list[$key]['schedule_type_name'] = '租客申请';
							break;
						case 2:
							$list[$key]['schedule_type_name'] = '退房水电气';
							break;
						case 3:
							$list[$key]['schedule_type_name'] = '退房账单发送';
							break;
						case 4:
							$list[$key]['schedule_type_name'] = '等待租客确认';
							break;
						case 5:
							$list[$key]['schedule_type_name'] = '确认账单';
							break;
						case 6:
							$list[$key]['schedule_type_name'] = '退房完成';
							break;
						default:
							$list[$key]['schedule_type_name'] = '未定义';
							break;
					}
					break;
				case 2:
					$list[$key]['schedule_type_name'] = '转房';
					break;
				case 3:
					$list[$key]['schedule_type_name'] = '换房';
					break;
				case 4:
					$list[$key]['schedule_type_name'] = '缴定';
					break;
				case 5:
					$list[$key]['schedule_type_name'] = '例行打款';
					break;
				default:
					$list[$key]['schedule_type_name'] = '不存在类型';
					break;
			}
		}	
		return $list;
	}

	/**
	* [修改退房待办]
	**/
	public function putSchedule($input){
		if (!is_numeric($input['schedule_id']) || is_null($input['schedule_id']) ) {
			return parent::response([false, '待办ID不存在']);
		}
		$schedule = [];
		$schedule['total_energy'] = $input['total_energy'];
		$schedule['total_water'] = $input['total_water'];
		$schedule['total_gas'] = $input['total_gas'];
		// 序列化房间电表
        $total_room_energy = serialize(i_array_column($input['total_room_energy'], 'end_room_energy', 'room_id'));
		$schedule['total_room_energy'] = $total_room_energy;
		$schedule['wx_fee'] = $input['wx_fee'];
		$schedule['wx_des'] = $input['wx_des'];
		$res = $this->updateSchedule(['id'=>$input['schedule_id']], $schedule);
		return parent::response([true, '修改成功']);
	}

	/**
	* [获取待办信息]
	**/
	public function getScheduleInfo($id){
		return $this->table
		->alias('schedule')
		->field('schedule.*, account.realname')
		->join('lewo_account account ON account.id = schedule.account_id')
		->where(['schedule.id'=>$id])
		->find();
	}

	/**
	* [获取全部待办]
	**/
	public function getAllSchedule($admin_type){
		$DRoom = D("room");
		$DAccount = D("account");
		if ( !empty($admin_type) ) {
			$where['admin_type'] = $admin_type;
		}
		$list = $this->table->where($where)->select();
		foreach( $list AS $key=>$val ){
			$room_info = $DRoom->getRoomInfoById($val['room_id']);
			$list[$key]['room_info'] = $room_info;

			$realname = $DAccount->getFieldById($val['account_id'],"realname");
			$list[$key]['realname'] = $realname;
			//待办工作类型 1：退房 2：转房 3：换房 4：缴定 5:例行打款
			switch ($val['schedule_type']) {
				case 1:
					switch ($val['status']) {
						case 1:
							$list[$key]['schedule_type_name'] = '租客申请';
							break;
						case 2:
							$list[$key]['schedule_type_name'] = '退房水电气';
							break;
						case 3:
							$list[$key]['schedule_type_name'] = '退房发送账单';
							break;
						case 4:
							$list[$key]['schedule_type_name'] = '等待租客确认';
							break;
						case 5:
							$list[$key]['schedule_type_name'] = '确认账单';
							break;
						case 6:
							$list[$key]['schedule_type_name'] = '退房完成';
							break;
						default:
							$list[$key]['schedule_type_name'] = '退房';
							break;
					}
					
					break;
				case 2:
					$list[$key]['schedule_type_name'] = '转房';
					break;
				case 3:
					$list[$key]['schedule_type_name'] = '换房';
					break;
				case 4:
					$list[$key]['schedule_type_name'] = '缴定';
					break;
				case 5:
					$list[$key]['schedule_type_name'] = '例行打款';
					break;
				default:
					$list[$key]['schedule_type_name'] = '不存在类型';
					break;
			}
		}	
		return $list;
	}

	/**
	* [2016-12-8 10:46][author:feng]
	* [方法二]
	* [插入一条待办]
	* @param schedule_type 待办工作类型 1：退房 2：转房 3：换房 4：缴定 5:例行打款
	* @param status 待办状态 1）退房：1租客申请，2管家录入水电气，3财务发送账单，4租客确认账单，5财务点击完成。2）转房3）换房同上。4）缴定：1管家录入缴定，2管家已跟租客签约
	* @param pay_type 1=>'支付宝',2=>'微信',3=>'银行卡',4=>'现金'
	**/
	public function postSchedule($input){
		if (is_null($input['schedule_type'])) {
			return parent::response([false, '待办类型参数不存在']);
		}
		if (is_null($input['account_id'])) { 
			return parent::response([false, '租客ID参数不存在']);
		}
		if (is_null($input['room_id'])) {
			return parent::response([false, '房间ID参数不存在']);
		}
		if (is_null($input['status'])) {
			return parent::response([false, '待办状态参数不存在']);
		}
		// 后台账号ID
		if (is_null($input['username_id'])) {
			return parent::response([false, '非法操作']);
		}

		$data = array();
		$data['event_id'] 		= !is_null($input['event_id']) ? $input['event_id'] : getOrderNo();
		$data['account_id'] 	= $input['account_id'];
		$data['room_id']    	= $input['room_id'];
		$data['schedule_type']  = $input['schedule_type'];
		$data['status']			= $input['status'];
		$data['create_time']	= date('Y-m-d H:i:s', time());
		$data['steward_id']     = $input['steward_id'];
		$data['money']      	= is_null($input['money'])
								? 0
								: $input['money'];
		$data['pay_account'] 	= is_null($input['pay_account'])
								? 0
								: $input['pay_account'] ;
		$data['pay_type'] 		= is_null($input['pay_type'])
								? 0
								: $input['pay_type'];
		$data['appoint_time'] 	= is_null($input['appoint_time'])
								? 0
								: $input['appoint_time'];
		$data['msg'] 			= is_null($input['msg'])
								? ''
								: $input['msg'];
		$data['is_finish']      = is_null($input['is_finish'])
								? 0
								: $input['is_finish'];;
		$data['admin_type']     = is_null($input['admin_type'])
								? 0
								: $input['admin_type'];
		$data['total_water']	= is_null($input['total_water']) || empty($input['total_water'])
								? 0
								: $input['total_water'];
		$data['total_energy']	= is_null($input['total_energy']) || empty($input['total_energy'])
								? 0
								: $input['total_energy'];
		$data['total_gas']		= is_null($input['total_gas']) || empty($input['total_gas'])
								? 0
								: $input['total_gas'];
		$data['total_room_energy']	= is_null($input['total_room_energy'])
								? 0
								: $input['total_room_energy'];
		$data['wx_fee']         = is_null($input['wx_fee']) || empty($input['wx_fee'])
								? 0
								: $input['wx_fee'];
		$data['wx_des']			= is_null($input['wx_des'])
								? ''
								: $input['wx_des'];
		$data['check_item']     = is_null($input['check_item'])
								? 0
								: $input['check_item'];
		$data['check_out_goods']= is_null($input['check_out_goods'])
								? 0
								: $input['check_out_goods'];
		$data['check_out_type'] = is_null($input['check_out_type'])
								? 0
								: $input['check_out_type'];

		$data = array_filter($data, function($v){
			return (!is_null($v) && $v != '') ? true : false;
		});
		
		$res = $this->table->add($data);

		if ( $res ) {
			return parent::response([true, '待办生成成功!', ['event_id'=>$data['event_id']]]);
		} else {
			return parent::response([false, '待办生成失败!']);
		}
	}

	/**
	 * [设置待办完成]
	 **/
	public function setFinish($id){
		return $this->table->where(array("id"=>$id))->save(array("is_finish"=>1));
	}

	/**
	* [获取退房未完成的数量]
	* @param schedule_type 待办类型 'admin_type_gj'=>1,'admin_type_cw'=>2,'admin_type_hq'=>3,
	* @param is_finish 是否完成
	* @return count
	**/
	public function getScheduleCount($schedule_type,$is_finish = 0,$admin_type = null){
		if (empty($schedule_type)) {
			return false;
		}
		$where['schedule_type'] = $schedule_type;
		$where['is_finish'] = $is_finish;
		if ( !empty($admin_type) ) {
			$where['admin_type'] = $admin_type;
		}
		return $this->table->where($where)->count();
	}
}

?>