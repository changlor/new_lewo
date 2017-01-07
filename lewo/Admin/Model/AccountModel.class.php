<?php
namespace Admin\Model;
use Think\Model;
/**
* [帐号数据层]
*/
class AccountModel extends BaseModel{
	private $table;
	protected $tableName = 'account';

	public function __construct()
    {
        parent::__construct();
    	$this->table = M($this->tableName);
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
	 * [获取该id的帐号信息]
	 **/
	public function getInfoById($id){
		$where['id'] = $id;
		$result = $this->table->field("id,mobile,email,sex,birthday,register_time,avatar,address,zipcode,card_no,enable,wx_no,wx_openid,card_z_pic,card_f_pic,extends,balance,login_time,ip,realname,contact2")->where($where)->find();
		return $result;
	}	
	/**
	 * [获取该id的某个值]
	 **/
	public function getFieldById($id,$field){
		$where['id'] = $id;
		$result = $this->table->where($where)->getField($field);
		return $result;
	}
}

?>