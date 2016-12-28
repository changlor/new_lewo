<?php
namespace Home\Model;
use Think\Model;
/**
* [帐号数据层]
*/
class AccountModel extends BaseModel {

	protected $table;
	protected $tableName = 'account';

	public function __construct()
	{
		parent::__construct();
		$this->table = M($this->tableName);
	}

	public function insert($account)
	{
		return $this->table->add($account);
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

	public function update($account, $where)
	{
		return $this->table->where($where)->save($account);
	}

	/**
	 * [获取帐号id]
	 * @param $key 键值
	 * @param $val 值
	 * @return $id
	 **/
	public function getAccountId($key,$val){
		$where[$key] = $val;
		$id = $this->table->where($where)->getField("id");
		return $id;
	}	
	/**
	* [根据id获取帐号信息]
	**/
	public function getAccountInfoById($id){
		return $this->table->field("id,mobile,email,sex,tag,birthday,register_time,avatar,address,zipcode,card_no,balance,realname,contact2")->where(array("id"=>$id))->find();
	}
}

?>