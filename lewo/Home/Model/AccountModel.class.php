<?php
namespace Home\Model;
use Think\Model;
/**
* [帐号数据层]
*/
class AccountModel extends Base {

	protected $table;
	protected $tableName = 'account';

	public function __construct()
	{
		$this->table = M($this->tableName);
	}

	public function insert($account)
	{
		return $this->table->add($account);
	}
	/**
	 * [获取帐号id]
	 * @param $key 键值
	 * @param $val 值
	 * @return $id
	 **/
	public function getAccountId($key,$val){
		$where[$key] = $val;
		$id = $this->where($where)->getField("id");
		return $id;
	}	
	/**
	* [根据id获取帐号信息]
	**/
	public function getAccountInfoById($id){
		return $this->field("id,mobile,email,sex,tag,birthday,register_time,avatar,address,zipcode,card_no,balance,realname,contact2")->where(array("id"=>$id))->find();
	}
}

?>