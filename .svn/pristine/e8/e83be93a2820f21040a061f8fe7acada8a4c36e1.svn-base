<?php
namespace Home\Controller;
use Think\Controller;
/**
 * [乐窝]
 **/
class LewoController extends Controller {
    public function __construct(){
        parent::__construct();
    }

    public function index(){
        $this->about_us();
    }

    public function about_us(){
        //访问+1pv
        $Mconfig = M("config");
        $pv = $Mconfig->where(array("id"=>1))->getField("58pv");
        $this->assign("pv",$pv);
        $Mconfig->where(array("id"=>1))->setInc("58pv",1);
        if ( isMobile() ) {
            $Mconfig->where(array("id"=>1))->setInc("m58pv",1);
            header("location:http://u3144490.viewer.maka.im/k/PU016TGH");
        } else {
            $this->display("58page");
        }
        
    }

}