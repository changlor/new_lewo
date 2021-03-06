<?php
namespace Home\Model;
use Think\Model;
/**
* [待办数据层]
*/
class ScheduleModel extends BaseModel {
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

	public function has($where)
	{
		$keys = array_keys($where);
		$field = $keys[0];
		$row = $this->selectField($where, $field);
		return $row === '0' || $row === 0 || !empty($row);
	}

	public function selectField($where, $field)
	{
		return $this->select($where)->getField($field);
	}

	public function insert($data)
	{
		return $this->table->add($data);
	}

	public function delete($where)
	{
		return $this->table->where($where)->delete();
	}

	public function insertSchedule($pay)
	{
		return $this->insert($pay);
	}

	public function selectSchedule($where, $field)
	{
		return $this->select($where, $field)->find();
	}

	public function deleteSchedule($where)
	{
		return $this->delete($where);
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
	 * [管家待办表]
	 **/
	public function getScheduleBySteward($steward_id){
		$DEvent = D('events');
		$sArr = $this->table
			->field([
				'ANY_VALUE(lewo_room.house_code) AS house_code', 
				'ANY_VALUE(lewo_room.room_sort) AS room_sort',
				'ANY_VALUE(lewo_room.room_code) AS room_code', 
				'ANY_VALUE(lewo_room.bed_code) AS bed_code',
				'ANY_VALUE(lewo_schedule.status) AS status', 
				'ANY_VALUE(lewo_schedule.schedule_type) AS schedule_type',
				'ANY_VALUE(lewo_schedule.event_id) AS event_id',
				'ANY_VALUE(lewo_account.realname) AS realname', 
				'ANY_VALUE(lewo_account.mobile) AS mobile'
			])
			->join("lewo_room ON lewo_schedule.room_id = lewo_room.id", 'left')
			->join("lewo_account ON lewo_schedule.account_id = lewo_account.id", 'left')
			->group('lewo_schedule.event_id')
			->where(['lewo_schedule.steward_id'=>$steward_id])
			->select();

		foreach( $sArr AS $key=>$val ){
			$eventLists = $DEvent->getEventList($val['event_id']);
			$sArr[$key]['eventLists'] = $eventLists;
			//待办工作类型 1：退房 2：转房 3：换房 4：缴定 5:例行打款
			$sArr[$key]['schedule_type_name'] = C('schedule_type_arr')[$val['schedule_type']];
		}
		return $sArr;
	}

	/**
	 * [获取该id的待办信息]
	 **/
	public function getScheduleByID($id){
		return $this->table->field("lewo_schedule.*,lewo_account.mobile,lewo_account.realname,lewo_account.card_no,lewo_account.contact2,lewo_account.email")->join("lewo_account ON lewo_schedule.account_id = lewo_account.id")->where(array("lewo_schedule.id"=>$id))->find();
	}
	/**
	 * [设置待办完成]
	 **/
	public function setFinish($id){
		return $this->table->where(array("id"=>$id))->save(array("is_finish"=>1));
	}

	/**
	* [生成一条待办][首次生成]
	* @param $account_id 租客id
	* @param $room_id 房间/床位id
	* @param $schedule_type 待办工作类型 1：退房 2：转房 3：换房 4：缴定 5:例行打款
	* @param $status 状态
	* @param $admin_type 帐号类型： 1管家 2财务 3后勤			
	* 待办状态 1）退房：1租客申请，2管家录入水电气，3财务发送账单，4租客确认账单，5财务点击完成。
	* 2）转房3）换房同上。4）缴定：1管家录入缴定，2管家已跟租客签约
	* @param $data 插入该数据
	* 
	**/
	public function addOneSchedule($data = array()){
		if ( count($data) > 0 ) {
			$data['create_time'] = date("Y-m-d H:i:s",time());
			return $this->table->add($data);
		} else {
			return false;
		}
	}

	/**
	* [插入一条待办][方法一][方法二见create_new_schedule]
	* @param scchedule_id 待办id 指的是上一步流程的id,获取同样的信息，以至于可以每一条待办都是独立的
	* @param schedule_type 待办类型 1：退房 2：转房 3：换房 4：缴定 5：例行打款 参考config
	* @param status 流程的状态 参考数据库文档
	* @param admin_type 操作人的类型 (1=>'管家',2=>'财务',3=>'后勤',4=>'出纳',99=>'管理员'),	 参考config
	* @return 插入的id 
	**/
	public function addNewSchdule($schedule_id,$schedule_type,$status,$admin_type, $is_finish = 0, $is_detele = 0){
		if ( empty($schedule_id) || empty($schedule_type) || empty($status) || empty($admin_type) ) {
			return false;
		}
		$DAccount = D("account");
		$schedule_info = $this->table->where(array("id"=>$schedule_id))->find();
		$data['account_id'] = $schedule_info['account_id'];
		$data['room_id'] = $schedule_info['room_id'];
		$data['mobile'] = $schedule_info['mobile'];
		$data['pay_account'] = $schedule_info['pay_account'];
		$data['appoint_time'] = $schedule_info['appoint_time'];
		$data['check_out_type'] = $schedule_info['check_out_type'];
		$data['steward_id'] = $schedule_info['steward_id'];
		$data['zS'] = !empty($schedule_info['zs'])? $schedule_info['zs']:0;
		$data['zD'] = !empty($schedule_info['zd'])? $schedule_info['zd']:0;
		$data['zQ'] = !empty($schedule_info['zq'])? $schedule_info['zq']:0;
		$data['roomD'] = !empty($schedule_info['roomd'])? $schedule_info['roomd']:0;
		$data['wx_fee'] = $schedule_info['wx_fee'];
		$data['msg'] = $schedule_info['msg'];
		$data['pay_type'] = $schedule_info['pay_type'];
		$data['wx_des'] = $schedule_info['wx_des'];
		$data['schedule_type'] = $schedule_type;
		$data['status'] = $status;
		$data['create_time'] = date("Y-m-d H:i:s",time());
		$data['admin_type'] = $admin_type;
		$data['is_finish'] = $is_finish;
		$data['is_detele'] = $is_detele;
		$data['last_schedule_id'] = $schedule_id;
		return M("schedule")->add($data);
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
		if (is_null($input['steward_id'])) {
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
	* [获取退房信息][根据account_id和room_id获取相关的退房信息]
	* @param account_id 租客id
	* @param room_id 房间id
	**/
	public function getTFinfo($account_id,$room_id){
		return $this->table->where(array("account_id"=>$account_id,"room_id"=>$room_id,"is_delete"=>0,"schedule_type"=>C("schedule_type_tf")))->order("status asc")->select();
	}

	/**
	* [获取换房信息]
	**/
	public function getHFinfo($account_id,$room_id){
		return $this->table->where(array("account_id"=>$account_id,"room_id"=>$room_id,"is_delete"=>0,"schedule_type"=>C("schedule_type_hf")))->order("status asc")->select();
	}

	/**
	* [获取转房信息]
	**/
	public function getZFinfo($account_id,$room_id){
		return $this->table->where(array("account_id"=>$account_id,"room_id"=>$room_id,"is_delete"=>0,"schedule_type"=>C("schedule_type_zf")))->order("status asc")->select();
	}

	/**
	* [获取退房未完成的数量]
	* @param schedule_type 待办类型 'admin_type_gj'=>1,'admin_type_cw'=>2,'admin_type_hq'=>3,
	* @param is_finish 是否完成
	* @return count
	**/
	public function getScheduleCount($steward_id, $schedule_type,$is_finish = 0){
		if (!is_numeric($schedule_type) || empty($schedule_type)) {
			return parent::response([false, '待办类型不存在']);
		}
		$where['schedule_type'] = $schedule_type;
		$where['is_finish'] = $is_finish;
		$where['steward_id'] = $steward_id;

		return $this->table->where($where)->count();
	}

	/**
	* [管家查看待办]
	**/
	public function getScheduleInfo($pro_id, $where){
		$where['pro_id'] = $pro_id;
		$scheduleInfo = $this->select($where)->order('status asc')->find();
		$scheduleInfo['check_item'] = unserialize($scheduleInfo['check_item']);
		$scheduleInfo['check_out_goods'] = unserialize($scheduleInfo['check_out_goods']);
		$scheduleInfo['admin_type_name'] = C('admin_type_arr')[$scheduleInfo['admin_type']];
		
		return $scheduleInfo;
	}
}

?>