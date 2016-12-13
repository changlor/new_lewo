<?php
namespace Home\Controller;
use Think\Controller;
/**
 * [找房]
 **/
class MapsearchController extends Controller {
    public function landing_page(){
        $this->display("landing-page");
    }
    /**
     * [找房]
     **/
    public function index(){
        if ( !empty(I("get.region_id")) ) {
            $condition['region_id'] = I("get.region_id");
        }
        if ( !empty(I("get.type")) ) {
            $condition['type'] = I("get.type");
        }
        if ( !empty(I("get.sort")) ) {
            $condition['sort'] = I("get.sort");
        }
        if ( !empty(I("get.money")) ) {
            $condition['money'] = I("get.money");
        }

        $DRegion = D('region');
        //生成二维数组
        $region = $DRegion->getRegion();
        $region_arr = array();
        foreach( $region AS $key=>$val ){
            if ( $val['father_id'] == 0 ) {
                $region_arr[$key] = $val;
                foreach($region AS $k=>$v){
                    if ( $val['id'] == $v['father_id'] ) {
                        $region_arr[$key]['c'][] = $v;
                    }
                }
            }
        }

    	$DHouses = D('Houses');
        $this->assign('region_arr',$region_arr);
    	$this->assign('room_arr',$DHouses->getAllRoom($condition));
    	$this->display('map-search');
    }

    /**
     * [房间/床位详细信息]
     **/
    public function detail_room($id){
    	$DHouses = D('Houses');
    	$info = $DHouses->getRoom($id);
    	$otherRoom = $DHouses->getOtherRoom($info['house_code'],$id);
        $this->assign('otherRoom',$otherRoom);
    	$this->assign('info',$info);
    	$this->display('room-info');
    }
}