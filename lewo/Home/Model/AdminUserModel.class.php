<?php
namespace Home\Model;
use Think\Model;
/**
* [管家数据层]
*/
class AdminUserModel extends Model{
    /**
    *[获取该管家信息]
    **/ 
    public function getStewardInfo($id){
        return $this->where(array("id"=>$id))->find();
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