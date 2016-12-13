<?php
namespace Admin\Model;
use Think\Model;
/**
* [管家数据层]
*/
class AdminUserModel extends Model{
	/**
	 * [获取帐号列表]
	 **/
	public function getAdmin(){
        $admin = $this->field("id,username,admin_type,nickname,mobile,create_time")->select();
        foreach($admin AS $key=>$val){
            $admin[$key]['admin_type_name'] = C('admin_type_arr')[$val['admin_type']];
        }
        return $admin;
	}
        /**
         * [获取管家列表]
         **/
        public function getStewardAccount(){
        $admin = $this->field("id,username,admin_type,nickname,mobile,create_time")->where(array("admin_type"=>1))->select();
        return $admin;
        }
}

?>