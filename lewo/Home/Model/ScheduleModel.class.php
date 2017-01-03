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
		$affectedRows = $this->update(['id' => $sccheduleId], $scheduleUpdateInfo);
		return parent::response([true, '']);
	}

	/**
	 * [管家待办表]
	 **/
	public function getScheduleBySteward($steward_id){
		$sArr = $this->table
			->field("lewo_room.house_code,lewo_room.room_code,lewo_room.bed_code,lewo_schedule.*,lewo_account.realname,lewo_account.mobile")
			->join("lewo_room ON lewo_schedule.room_id = lewo_room.id")
			->join("lewo_account ON lewo_schedule.account_id = lewo_account.id")
			->where(array('steward_id'=>$steward_id,'is_finish'=>0))
			->select();
		foreach( $sArr AS $key=>$val ){
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
            $data['create_date'] = date("Y-m-d",time());
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
		$data['create_date'] = $schedule_info['create_date'];
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
	public function create_new_schedule($param){
		if (is_null($param['schedule_type'])) {
			return parent::response([false, '待办类型参数不存在']);
		}
		if (is_null($param['account_id'])) { 
			return parent::response([false, '租客ID参数不存在']);
		}
		if (is_null($param['room_id'])) {
			return parent::response([false, '房间ID参数不存在']);
		}
		if (is_null($param['status'])) {
			return parent::response([false, '待办状态参数不存在']);
		}

		$data = array();
		$data['pro_id'] 		= getOrderNo();
		$data['account_id'] 	= $param['account_id'];
		$data['room_id']    	= $param['room_id'];
		$data['schedule_type']  = $param['schedule_type'];
		$data['status']			= $param['status'];
		$data['create_time']	= date('Y-m-d H:i:s', time());

		$data['create_date']	= is_null($param['create_date'])
								? date('Y-m-d', time()) 
								: $param['create_date'];
		$data['mobile'] 		= is_null($param['mobile'])
								? 0 
								: $param['mobile'];
		$data['money']      	= is_null($param['money'])
								? 0
								: $param['money'];
		$data['pay_account'] 	= is_null($param['pay_account'])
								? 0
								: $param['pay_account'] ;
		$data['pay_type'] 		= is_null($param['pay_type'])
								? 0
								: $param['pay_type'];
		$data['appoint_time'] 	= is_null($param['appoint_time'])
								? 0
								: $param['appoint_time'];
		$data['msg'] 			= is_null($param['msg'])
								? ''
								: $param['msg'];
		$data['is_finish']      = is_null($param['is_finish'])
								? 0
								: $param['is_finish'];
		$data['steward_id']     = is_null($param['steward_id'])
								? 0
								: $param['steward_id'];
		$data['admin_type']     = is_null($param['admin_type'])
								? 0
								: $param['admin_type'];
		$data['zS']				= is_null($param['zS'])
								? 0
								: $param['zS'];
		$data['zD']				= is_null($param['zD'])
								? 0
								: $param['zD'];
		$data['zQ']				= is_null($param['zQ'])
								? 0
								: $param['zQ'];
		$data['roomD']			= is_null($param['roomD'])
								? 0
								: $param['roomD'];
		$data['wx_fee']         = is_null($param['wx_fee'])
								? 0
								: $param['wx_fee'];
		$data['wx_des']			= is_null($param['wx_des'])
								? ''
								: $param['wx_des'];
		$data['check_item']     = is_null($param['check_item'])
								? 0
								: $param['check_item'];
		$data['check_out_goods']= is_null($param['check_out_goods'])
								? 0
								: $param['check_out_goods'];
		$data['check_out_type'] = is_null($param['check_out_type'])
								? 0
								: $param['check_out_type'];

		$res = $this->table->add($data);

		if ( $res ) {
			return parent::response([true, '待办生成成功!']);
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
		return $this->where(array("account_id"=>$account_id,"room_id"=>$room_id,"is_delete"=>0,"schedule_type"=>C("schedule_type_tf")))->order("status asc")->select();
	}

	/**
	* [获取换房信息]
	**/
	public function getHFinfo($account_id,$room_id){
		return $this->where(array("account_id"=>$account_id,"room_id"=>$room_id,"is_delete"=>0,"schedule_type"=>C("schedule_type_hf")))->order("status asc")->select();
	}

	/**
	* [获取转房信息]
	**/
	public function getZFinfo($account_id,$room_id){
		return $this->where(array("account_id"=>$account_id,"room_id"=>$room_id,"is_delete"=>0,"schedule_type"=>C("schedule_type_zf")))->order("status asc")->select();
	}

	/**
	* [获取退房未完成的数量]
	* @param schedule_type 待办类型 'admin_type_gj'=>1,'admin_type_cw'=>2,'admin_type_hq'=>3,
	* @param is_finish 是否完成
	* @return count
	**/
	public function getScheduleCount($steward_id, $schedule_type,$is_finish = 0,$admin_type = null){
		if (empty($schedule_type)) {
			return false;
		}
		$where['schedule_type'] = $schedule_type;
		$where['is_finish'] = $is_finish;
		$where['steward_id'] = $steward_id;
		if ( !empty($admin_type) ) {
			$where['admin_type'] = $admin_type;
		}
		return $this->where($where)->count();
	}

	/**
	* [管家查看待办]
	**/
	public function getScheduleInfo($pro_id, $where){
		$where['pro_id'] = $pro_id;
		$scheduleInfo = $this->select($where)->order('status asc')->find();
		$scheduleInfo['check_item'] = unserialize($scheduleInfo['check_item']);
		$scheduleInfo['check_out_goods'] = unserialize($scheduleInfo['check_out_goods']);

		return $scheduleInfo;
	}
}

?>