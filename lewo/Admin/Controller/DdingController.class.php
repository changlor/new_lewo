<?php
namespace Admin\Controller;
use Think\Controller;
class DdingController extends Controller {
    public function __construct(){
        parent::__construct();
        if ( empty($_SESSION['username']) && ACTION_NAME != 'register') {
            header("Location:".U("Admin/Index/login"));
            die();
        }
    }
    
    public function room_manage(){
    	$Mdding_room = M("dding_room");
    	$room_list = $Mdding_room->select();
    	$this->assign("room_list",$room_list);
    	$this->display("Common/header");
    	$this->display("Common/nav");
        $this->display("room_manage");
        $this->display("Common/footer");
    }

    public function add_room(){
    	if ( !empty($_POST) ) {
    		//判断房间是否重复
    		$Mdding_room = M("dding_room");
    		$result = $Mdding_room->where(array("room_code"=>$room_code))->find();
    		$uuid_count = strlen(I("uuid"));
    		if ( count($result) > 0 ) {
    			$this->error(I("room_code")."已录入信息",U("Admin/Dding/room_manage"));
    		}
    		if ( $uuid_count != 32) {
    			$this->error("请录入正确的uuid");
    		}
    		$data['room_code'] = I("room_code");
    		$data['uuid'] = I("uuid");
    		$add_result = $Mdding_room->add($data);
    		if ( $add_result ) {
    			$this->success("录入成功",U("Admin/Dding/room_manage"));
    		} else {
    			$this->error("录入失败",U("Admin/Dding/room_manage"));
    		}
    	} else {
    		$this->display("Common/header");
	    	$this->display("Common/nav");
	        $this->display("add_room");
	        $this->display("Common/footer");
    	}
    }

    public function update_room(){
    	$id = I("id");
	    $Mdding_room = M("dding_room");	
    	if ( !empty($_POST) ) {
    		$save['room_code'] = I("room_code");
    		$save['uuid'] = I("uuid");
    		$result = $Mdding_room->where(array("id"=>$id))->save($save);
    		if ( $result ) {
    			$this->success("修改成功",U('Admin/Dding/room_manage'));
    		} else {
    			$this->error("修改失败",U("Admin/Dding/room_manage"));
    		}
    	} else {    	
	    	$room_info = $Mdding_room->where(array("id"=>$id))->find();
	    	$this->assign("room_info",$room_info);
	    	$this->display("Common/header");
	    	$this->display("Common/nav");
	        $this->display("update_room");
	        $this->display("Common/footer");
	    }
    }

    public function delete_room(){
    	$id = I("id");
    	$Mdding_room = M("dding_room");	
    	$result = $Mdding_room->where(array("id"=>$id))->delete();
    	if ( $result ) {
			$this->success("删除成功",U('Admin/Dding/room_manage'));
		} else {
			$this->error("删除失败",U("Admin/Dding/room_manage"));
		}
    }

    public function log(){
        
    }
}