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
}