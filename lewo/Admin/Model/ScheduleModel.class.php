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

	public function selectSchedule($where, $field){
		return $this->select($where, $field)->select();
	}

	public function join($joinTable, $where, $field)
    {
        return parent::join($this->table, $joinTable)->field($field)->where($where);
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
	* [获取待办信息]
	**/
	public function getScheduleInfo($id){
		return $this->table
		->where(['id'=>$id])
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
	* [插入一条待办]
	* @param scchedule_id 待办id 指的是上一步流程的id,获取同样的信息，以至于可以每一条待办都是独立的
	* @param schedule_type 待办类型 1：退房 2：转房 3：换房 4：缴定 5：例行打款 参考config
	* @param status 流程的状态 参考数据库文档
	* @param admin_type 操作人的类型 1管家 2财务 3后勤	 参考config
	* @return 插入的id 
	**/
	public function addNewSchdule($schedule_id,$schedule_type,$status,$admin_type, $is_finish = 0, $is_detele = 0){
		if ( empty($schedule_id) || empty($schedule_type) || empty($status) || empty($admin_type) ) {
			return false;
		}
		$DAccount = D("account");
		$schedule_info = $this->where(array("id"=>$schedule_id))->find();
		$data['account_id'] = $schedule_info['account_id'];
		$data['room_id'] 	= $schedule_info['room_id'];
		$data['mobile'] 	= $schedule_info['mobile'];
		$data['pay_account'] 	= $schedule_info['pay_account'];
		$data['appoint_time'] 	= $schedule_info['appoint_time'];
		$data['check_out_type'] = $schedule_info['check_out_type'];
		$data['steward_id'] 	= $schedule_info['steward_id'];
		$data['zS'] = !empty($schedule_info['zs'])? $schedule_info['zs']:0;
		$data['zD'] = !empty($schedule_info['zd'])? $schedule_info['zd']:0;
		$data['zQ'] = !empty($schedule_info['zq'])? $schedule_info['zq']:0;
		$data['roomD'] 	= !empty($schedule_info['roomd'])? $schedule_info['roomd']:0;
		$data['wx_fee'] = $schedule_info['wx_fee'];
		$data['msg'] 	= $schedule_info['msg'];
		$data['pay_type'] 	= $schedule_info['pay_type'];
		$data['wx_des'] 	= $schedule_info['wx_des'];
		$data['schedule_type'] 	= $schedule_type;
		$data['status'] 		= $status;
		$data['create_time'] 	= date("Y-m-d H:i:s",time());
		$data['create_date'] 	= $schedule_info['create_date'];
		$data['admin_type'] 	= $admin_type;
		$data['is_finish'] 		= $is_finish;
		$data['is_detele'] 		= $is_detele;
		$data['last_schedule_id'] = $schedule_id;
		return $this->table->add($data);
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