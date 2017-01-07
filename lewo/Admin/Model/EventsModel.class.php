<?php
namespace Admin\Model;
use Think\Model;
/**
 * 事件表
 */
class EventsModel extends BaseModel {
    private $table;
    protected $tableName = 'events';
    private static $eventDes = [
        '1_2' => '管家已验房。',
        '1_3' => '租客退租账单已生成。',
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

    public function selectEvents($where, $field){
        return $this->select($where, $field)->order(['create_time desc'])->select();
    }

    public function insert($data)
    {
        return $this->table->add($data);
    }

    public function insertEvent($data){
        return $this->insert($data);
    }

    /**
    * [插入一条事件]
    **/
    public function postEvent($data){
        $eventId = $data['event_id'];
        if (!is_numeric($eventId) || empty($eventId)) {
            return parent::response([false, '事件ID不存在']);
        }
        $accountId = $data['account_id'];
        if (!is_numeric($eventId) || empty($eventId)) {
            return parent::response([false, '租客ID不存在']);
        }
        $roomId = $data['room_id'];
        if (!is_numeric($roomId) || empty($roomId)) {
            return parent::response([false, '房屋ID不存在']);
        }
        $eventType = $data['event_type'];
        if (!is_numeric($eventType) || empty($eventType)) {
            return parent::response([false, '事件类型不存在']);
        }
        $eventStatus = $data['event_status'];
        if (!is_numeric($eventStatus) || empty($eventStatus)) {
            return parent::response([false, '事件状态不存在']);
        }
        $eventsDesKey = $eventType . '_' . $eventStatus;
        // 获取事件描述
        $data['event_des'] = self::$eventDes[$eventsDesKey];
        $data['create_time'] = time();
        if (!is_null($data['event_des']) && $data['event_des'] != '') {
            $this->insertEvent($data);
            return parent::response([true, '生成事件成功']);
        } else {
            return parent::response([false, '事件不存在']);
        }
    }

    /**
    * [获取事件列表]
    **/
    public function getEventList($eventId){
        return $this->selectEvents([
            'event_id'=>$eventId
        ], [
            'account_id', 'room_id', 'event_id',
            'event_des', 'from_unixtime(create_time,\'%Y-%m-%d %H:%i:%s\') AS create_time', 
            'event_type', 'event_status'
        ]);
    }
}