<?php
namespace Home\Model;
use Think\Model;
/**
* [房源数据层]
*/
class HousesModel extends BaseModel {
	protected $table;
	protected $tableName = 'houses';

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

	public function join($joinTable, $where, $field)
    {
        return parent::join($this->table, $joinTable)->field($field)->where($where);
    }

	public function selectField($where, $field)
	{
		return $this->select($where)->getField($field);
	}

    public function insert($contract)
    {
    	return $this->table->add($contract);
    }

    public function getHouses($input)
    {
    	// 获取模型实例
    	$DRoom = D('room');
    	// 获取当前操作管家id
    	$stewardId = $_SESSION['steward_id'];
    	if (!is_numeric($stewardId)) {
    		return parent::response([false, '权限错误！']);
    	}
    	// 获取分类类别
    	$type = $input['type'];
    	// --如果分类为空，则默认获取当前管家管辖的房源
    	if (empty($type)) {
    		$type = 'steward';
    	}
    	// 获取搜索关键词
    	$keyWord = $input['keyWord'];
    	if (strpos($keyWord, '-') !== false) {
    		$chips = explode('-', $keyWord);
            // 房屋
            if (isset($chips['0'])) {
                $building = $chips['0'];
            }
            // 楼层
            if (isset($chips['1'])) {
                $floor = $chips['1'];
            }
            // 房号
            if (isset($chips['2'])) {
                $doorNo = $chips['2'];
            }
    	}
    	// 如果非空且不符合小区号，则开启模糊查询
    	if (strrpos($keyWord, '-') === false && !empty($keyWord)) {
    		$fuzzyQuery = 'lewo_houses.house_code LIKE \'%' . $keyWord . '%\' OR lewo_houses.area_name LIKE \'%' . $keyWord . '%\'';
    	}
    	// 设置过滤条件
    	$filters = [
    		'lewo_houses.building' => $building,
    		'lewo_houses.floor' => $floor,
    		'lewo_houses.door_no' => $doorNo,
    		'lewo_houses._string' => $fuzzyQuery
    	];
    	// 根据要搜索的房屋类别，增加过滤条件
    	switch ($type) {
    		case 'steward':
    			$filters['lewo_houses.steward_id'] = $stewardId;
    			break;
    	}
    	// 将未被赋值的过滤条件去除
    	$filters = array_filter($filters, function ($value) {
    		return is_numeric($value) || !!$value;
    	});
    	// 连接查询
    	$joinTable = [
            'area' => 'houses.area_id = area.id',
        ];
        $field = [
            // houses
            'lewo_houses.id' => 'houses_id',
            'lewo_houses.area_id',
            'lewo_houses.steward_id',
            'lewo_houses.house_code',
            'lewo_houses.type',
            'lewo_houses.building',
            'lewo_houses.floor',
            'lewo_houses.door_no',
            // area
            'lewo_area.area_name', 'lewo_area.id',
        ];
        $houses = $this->join($joinTable, $filters, $field)->order('lewo_houses.area_id desc, lewo_houses.building desc, lewo_houses.floor desc, lewo_houses.door_no desc')->select();
        foreach ($houses as $key => $value) {
			if ($yzCount > 0) {
				$houses[$key]['is_checkin'] = true;
			} else {
				$houses[$key]['is_checkin'] = false;
			}
			// 设置需要获取的房源的过滤规则
			$filters = ['house_code' => $value['house_code'], 'is_show' => 1];
			switch ($type) {
				case 'empty':
					$filters['lewo_room.status'] = 0;
					break;
				case 'is_let_out':
					$filters['lewo_room.status'] = ['not in', [0]];
					break;
	    		default:
    				break;
			}
			$field = [
				// lewo_room
				'lewo_room.id',
				'lewo_room.account_id',
				'lewo_room.room_code',
				'lewo_room.room_nickname',
				'lewo_room.room_sort',
				'lewo_room.room_type',
				'lewo_room.bed_code',
				'lewo_room.rent',
				'lewo_room.status',
				'lewo_room.is_show',
				'lewo_account.realname',
				'lewo_account.sex'
			];
			// 连接查询
    		$joinTable = [
                'account' => 'room.account_id = account.id',
	        ];
	        $houses[$key]['room_list'] = $DRoom->join($joinTable, $filters, $field)->order('lewo_room.room_sort asc')->select();
	        $houses[$key]['yz_count'] = 0;
	        $houses[$key]['count'] = count($houses[$key]['room_list']);
			$houses[$key]['is_checkin'] = false;
	        foreach ($houses[$key]['room_list'] as $ke => $val) {
	        	if ($val['status'] == 2) {
					$houses[$key]['is_checkin'] = true;
	        		$houses[$key]['yz_count']++;
	        	}
	        }
        }
        return $houses;
    }

	/**
	 * [获取房源列表]
	 **/
	public function getHousesList(){
		return $this->table->select();
	}

	/**
	 * [获取房屋信息]
	 **/
	public function getHouse($house_code){
		return $this->table->where(array('house_code'=>$house_code))->find();
	}
	/**
	 * [获取房屋信息]
	 **/
	public function getHouseById($house_id){
		return $this->table->where(array('id'=>$house_id))->find();
	}
	/**
	 * [获取房屋信息物业费]
	 **/
	public function getWYfee($house_code){
		return $this->table->where(array('house_code'=>$house_code))->getField("fee");
	}
	/**
	 * [获取房屋编码]
	 **/
	public function getHouseCodeById($hosue_id){
		return $this->table->where(array('id'=>$hosue_id))->getField("house_code");
	}
	/**
	* [获取房屋id]
	**/
	public function getHouseIdByCode($house_code){
		return $this->table->where(array("house_code"=>$house_code))->getField("id");
	}
	/**
	* [获取小区id根据房屋编码]
	**/
	public function getAreaIdByCode($house_code){
		return $this->table->where(array("house_code"=>$house_code))->getField("area_id");
	}

	/**
	 * [获取全部房间/床位信息并关联房屋信息]
	 **/
	public function getAllRoom($condition){
		$MRoom = M('room');
		$order = 'lewo_room.create_time desc';
		
		//搜索条件
		if( !empty($condition['region_id']) ){
			$where['region_id'] = $condition['region_id'];
		}
		if( !empty($condition['type']) ){
			$where['room_type'] = $condition['type'];
		}
		if( !empty($condition['sort']) ){
			$order = "lewo_room.rent ".$condition['sort'];
		}
		if( !empty($condition['money']) ){
			$mArr = explode("-",$condition['money']);
			if ( count($mArr) > 1 ) {
				$where['rent'] = array("BETWEEN",array($mArr['0'],$mArr['1']));
			} else {
				$where['rent'] = array("gt",$mArr['0']);
			}
		}

		$where['is_show'] = 1; 

		$arr = $MRoom->field("lewo_room.id,lewo_room.room_head_images,lewo_room.house_code,lewo_room.room_code,lewo_room.bed_code,lewo_houses.area_id,lewo_room.rent,lewo_room.room_parameter,lewo_houses.floor,lewo_houses.door_no,lewo_houses.building")->where($where)->join('lewo_houses ON lewo_room.house_code = lewo_houses.house_code')->order($order)->select();

		$MArea = M("area");
		foreach ($arr AS $key=>$val) {
			$arr[$key]['area_name'] = $MArea->where(array("id"=>$val['area_id']))->getField("area_name");
			$arr[$key]['room_parameter'] = unserialize($val['room_parameter']);
			if ( !empty($val['bed_code']) ) {
				$arr[$key]['rent_out_type'] = 'bed';
				$arr[$key]['rent_out_type_name'] = '床';
			} else {
				$arr[$key]['rent_out_type'] = 'room';
				$arr[$key]['rent_out_type_name'] = '间';
			}
		}
		return $arr;
	}

	/**
	 * [获取房源中的房间/床位]
	 **/
	public function getRoomList($house_code){
		$MRoom = M("room");
		$roomList = $MRoom->where(array('house_code'=>$house_code))->select();
		foreach($roomList AS $key=>$val){
			if (!empty($val['bed_code'])) {
				$roomList[$key]['rent_out_type'] = 'bed';
				$roomList[$key]['rent_out_type_name'] = '床';
			} else {
				$roomList[$key]['rent_out_type'] = 'room';
				$roomList[$key]['rent_out_type_name'] = '间';
			}
		}
		return $roomList;
	}

	/**
	 * [获取房间/床位信息]
	 **/
	public function getRoom($id){
		$MRoom = M("room");
		$MHouses = M("houses");
		$MSteward = M("admin_user");
		$room_info = $MRoom->where(array('id'=>$id))->find();
		$house_info = $MHouses->field("area_id,address,floor,door_no,building,steward_id")->where(array('house_code'=>$room_info['house_code']))->find();
		$steward_info = $MSteward->field("id,username,nickname,mobile")->where(array('id'=>$house_info['steward_id']))->find();
		$room_info['area_name'] = M("area")->where(array("id"=>$house_info['area_id']))->getField("area_name");
		if ( !empty($room_info['room_images_01']) ) {
			$room_info['room_images'][] = $room_info['room_images_01'];
		}
		if ( !empty($room_info['room_images_02']) ) {
			$room_info['room_images'][] = $room_info['room_images_02'];
		}
		if ( !empty($room_info['room_images_03']) ) {
			$room_info['room_images'][] = $room_info['room_images_03'];
		}
		if ( !empty($room_info['room_images_04']) ) {
			$room_info['room_images'][] = $room_info['room_images_04'];
		}

		$room_info['house_info'] = $house_info;
		$room_info['steward_info'] = $steward_info;
		$room_info['address'] = $house_info['address'];
		return $room_info;
	}

	/**
	 * [获取房间/床位详细信息中的其他房间/床位]
	 **/
	public function getOtherRoom($house_code,$not_id){
		$MRoom = M('room');
		$where['house_code'] = $house_code;
		$where['id'] = array('NOT IN',$not_id);
		$where['status'] = 0;
		$where['is_show'] = 1;
		$room_list = $MRoom->field("id,house_code,room_code,room_sort,room_area,room_parameter,rent,room_fee,status,room_images_01,room_images_02,room_images_03,room_images_04")->where($where)->select();
		foreach($room_list AS $key=>$val){
			$room_list[$key]['room_parameter'] = unserialize($val['room_parameter']);
			if ( !empty($val['room_images_01']) ) {
				$room_list[$key]['room_images'][] = $val['room_images_01'];
			}
			if ( !empty($val['room_images_02']) ) {
				$room_list[$key]['room_images'][] = $val['room_images_02'];
			}
			if ( !empty($val['room_images_03']) ) {
				$room_list[$key]['room_images'][] = $val['room_images_03'];
			}
			if ( !empty($val['room_images_04']) ) {
				$room_list[$key]['room_images'][] = $val['room_images_04'];
			}
		}
		return $room_list;
	}

	/**
	 * [管家页面中的房源显示]
	 * [显示房屋+房间/床位]
	 * [针对管家id进行显示]
	 **/
	public function getHouseAndRoom($where, $type = ''){
		//获取不同分类的房源，默认获取所有房源
		$type = empty($type) ? 'all' : $type;
		$MRoom = M("room");
		$MArea = M("area");
		/*
<<<<<<< HEAD
		$houses = $this->field("id,area_id,steward_id,house_code,type,building,floor,door_no")->where($where)->order("area_id desc, building desc,floor desc,door_no desc")->select();
=======
		*/

		$houses = $this->table->field("id,area_id,steward_id,house_code,type,building,floor,door_no")->where($where)->order("area_id desc, building desc,floor desc,door_no desc")->select();
//>>>>>>> masterTam/master

		foreach($houses as $key => $val){
			$count = $MRoom->where(array("house_code"=>$val['house_code'],"is_show"=>1))->count();
			$yz_count = $MRoom->where(array("house_code"=>$val['house_code'],"is_show"=>1,"status"=>C('room_yz')))->count();
			if ( $yz_count ) {
				$houses[$key]['is_checkin'] = true;
			} else {
				$houses[$key]['is_checkin'] = false;
			}
			//设置需要获取的房源的过滤规则
			$filters = array("house_code"=>$val['house_code'],"is_show"=>1);
			switch ($type) {
				case 'empty':
				$filters['lewo_room.status'] = 0;
				break;
				case 'is_let_out':
				$filters['lewo_room.status'] = array('not in',array(0));
				case 'all':
				default:
				break;
			}
			$houses[$key]['count'] = $count;
			$houses[$key]['yz_count'] = $yz_count;
			$houses[$key]['area_name'] = $MArea->where(array("id"=>$val['area_id']))->getField("area_name");
			$houses[$key]['room_list'] = $MRoom->field("lewo_room.id,lewo_room.account_id,lewo_room.room_code,lewo_room.room_nickname,lewo_room.room_sort,lewo_room.room_type,lewo_room.bed_code,lewo_room.rent,lewo_room.status,lewo_room.is_show,lewo_account.realname,lewo_account.sex")->join("lewo_account ON lewo_room.account_id=lewo_account.id","left")->where($filters)->order("room_sort asc")->select();
		}
		//当所获取的分类下的房源为空时，删除掉此记录
		foreach ($houses as $key => $value) {
			$count_room = count($value['room_list']);
			if ($count_room == 0) {
				unset($houses[$key]);
				continue;
			}
		}
		
		return $houses;
	}
	/**
	* [查该该房屋租了多少人]
	**/
	public function getPersonCountByCode($house_code){
		return M("room")->where(array("status"=>2,"house_code"=>$house_code))->count();
	}

	/**
	* [获取房屋小区名 房屋的楼层号]
	**/
	public function getHouseAndArea($house_code){
		return $this->table
		->field([
			'lewo_area.area_name', 
			'lewo_houses.building', 'lewo_houses.floor',
			'lewo_houses.door_no'
		])
		->join("lewo_area ON lewo_houses.area_id = lewo_area.id")
		->where(array("house_code"=>$house_code))
		->find();
	}
}
