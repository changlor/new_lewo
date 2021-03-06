<?php
namespace Admin\Controller;
use Think\Controller;
class StewardController extends Controller {
    public function __construct(){
        parent::__construct();
        if ( empty($_SESSION['username']) && ACTION_NAME != 'register') {
            header("Location:".U("Admin/Index/login"));
            die();
        }
    }
    /**
     * [帐号列表]
     **/
    public function account(){
        $Madmin_user = D('admin_user');
        $account_arr = $Madmin_user->getAdmin();
        $this->assign('account',$account_arr);
    	$this->display("Common/header");
    	$this->display("Common/nav");
    	$this->display("steward-account");
    	$this->display("Common/footer");
    }
    /**
     * [添加管家帐号]
     **/
    public function add_account(){
        if ( !empty($_POST) ) {
            if ( $_POST['password'] !== $_POST['repassword'] ){
                die("两次密码不相同");
            }
            $Msteward = M('admin_user');
            $data['username'] = I('post.username');
            $data['password'] = md5(I('post.password'));
            $data['mobile'] = I('post.mobile');
            $data['admin_type'] = I('post.admin_type');
            $data['nickname'] = I('post.nickname');
            $data['create_time'] = date('Y-m-d H:i:s');
            $data['ip'] = get_client_ip();
            $result = $Msteward->where(array('username'=>$data['username']))->find();
            if ( empty($result) ) {
                $Msteward->add($data);
                $this->success("注册成功",U('Admin/Steward/account'));
            } else {
                $this->error("注册失败，帐号已存在");
            }
        } else {
            $this->assign('admin_type_arr',C('admin_type_arr'));
            $this->display("Common/header");
            $this->display("Common/nav");
            $this->display('add-account');
            $this->display("Common/footer");
        }
    }

    public function update_account(){
        if ( !empty($_POST) ) {
            $id = I("id");
            $password = I("password");
            $repassword = I("repassword");
            if ( empty($password) ) {
                $this->error("密码不能为空!");
            }
            if ( $password != $repassword ) {
                $this->error("两次密码不相同");
            }
            $result = M("admin_user")->where(array("id"=>$id))->save(array("password"=>md5(I("password"))));
            if ( $result ) {
                $this->success("修改成功",U('Admin/Steward/account'));
            } else {
                $this->error("修改失败");
            }
        } else {
            $id = I("id");
            $this->assign("id",$id);
            $this->display("Common/header");
            $this->display("Common/nav");
            $this->display('update-account');
            $this->display("Common/footer");
        }
    }

    public function delete_account(){
        $id = I('id');
        $Madmin_user = M('admin_user');
        $result = $Madmin_user->where(array('id'=>$id))->delete();
        if ( $result ) {
            $this->success('删除成功!');
        } else {
            $this->error('删除失败!');
        }
    }
}