<?php
namespace Home\Model;
use Think\Model;
/**
* [账单数据层]
*/
class ChargeBillModel extends Model{
	/**
    * [获取该用户id的账单列表]
    **/
    /*public function getBillByAcId($account_id){
        $list = M('pay')
                ->alias('p')
                ->field('cb.*,p.*,a.realname')
                ->join('lewo_charge_bill cb ON p.pro_id=cb.pro_id','left')
                ->join('lewo_account a ON p.account_id=a.id','left')
                ->where(array("p.account_id"=>$account_id,"p.is_show"=>1,"cb.is_send"=>1))
                ->order('p.input_year desc,p.input_month desc')
                ->select();

        $MHouses = M("houses");
        $MArea = M("area");
        foreach ($list AS $key=>$val) {
            //根据house_code 判断广州或重庆
            $area_id = $MHouses->where(array("house_code"=>$val['house_code']))->getField("area_id");
            $city_id = $MArea->where(array("id"=>$area_id))->getField("city_id");
            $city_name = C("city_id")[$city_id];

            $realname = preg_replace('/(\/|\.|\%)/','', $val['realname']);

            $list[$key]['bill_info'] = $realname.$val['input_year']."年".$val['input_month']."月".C('bill_type')[$val['bill_type']];

	        switch ($val['type']) {
                case 2:
                    $list[$key]['type_name'] = "退租";
                    $list[$key]['bill_info'] .= "退租";
                    break;
                case 3:
                    $list[$key]['type_name'] = "转租";
                    $list[$key]['bill_info'] .= "转租";
                    break;
                case 4:
                    $list[$key]['type_name'] = "换租";
                    $list[$key]['bill_info'] .= "换租";
                    break;
			}
            $list[$key]['bill_info'] .= $city_name."账单";
		}

		return $list;
    }*/
    /**
    * [获取该账单信息]用户账单页面
    **/ 
    public function getDetailBillById($pro_id){
    	$bill_info = M('pay')
                    ->alias('p')
                    ->field('cb.*,p.*,a.realname,r.house_code,h.building,h.floor,h.door_no')
                    ->join('lewo_charge_bill cb ON p.pro_id=cb.pro_id','left')
                    ->join('lewo_account a ON p.account_id=a.id','left')
                    ->join('lewo_room r ON r.id=p.room_id','left')
                    ->join('lewo_houses h ON h.house_code=r.house_code','left')
                    ->where(array("p.pro_id"=>$pro_id))
                    ->find();

        //根据house_code 判断广州或重庆
        $MHouses = M('houses');
        $MArea = M('area');

        $realname = preg_replace('/(\/|\.|\%)/','', $bill_info['realname']);
        $area_id = $MHouses->where(array("house_code"=>$bill_info['house_code']))->getField("area_id");
        $bill_info['area_name'] = $MHouses->where(array("house_code"=>$bill_info['house_code']))->getField("area_name");
        $city_id = $MArea->where(array("id"=>$area_id))->getField("city_id");
        $city_name = C("city_id")[$city_id];
        $bill_info['bill_info'] = $realname.$bill_info['input_year']."年".$bill_info['input_month']."月".C('bill_type')[$bill_info['bill_type']];

        switch ($bill_info['type']) {
            case 2:
                $bill_info['bill_info'] .= "退租";
                break;
            case 3:
                $bill_info['bill_info'] .= "转租";
                break;
            case 4:
                $bill_info['bill_info'] .= "换租";
                break;
        }
        $bill_info['bill_info'] .= $city_name."账单";

    	return $bill_info;
    }

    /**
    * [获取该租客账单]
    **/
    public function getBillByAcId($account_id,$pay_status = null,$bill_type){
        if ( !is_null($pay_status) ) $where['p.pay_status'] = $pay_status;
        if ( !is_null($bill_type) ) $where['p.bill_type'] = $bill_type;

        $where['p.account_id'] = $account_id;
        $where['p.is_show'] = 1;
        $where['cb.is_send'] = 1;
    	$list =  M('pay')
                ->alias('p')
                ->field('cb.*,p.*,a.realname')
                ->join('lewo_charge_bill cb ON p.pro_id=cb.pro_id','left')
                ->join('lewo_account a ON p.account_id=a.id','left')
                ->where($where)
                ->order('p.input_year desc,p.input_month desc')
                ->select();

        $MHouses = M("houses");
        $MArea = M("area");
    	foreach ($list AS $key=>$val) {
    		$startdate=time();
			$enddate=strtotime($val['should_date']);
			$days=round(($enddate-$startdate)/86400)+1;
    		$list[$key]['days'] = $days;
            
            $realname = preg_replace('/(\/|\.|\%)/','', $val['realname']);

            //根据house_code 判断广州或重庆
            $area_id = $MHouses->where(array("house_code"=>$val['house_code']))->getField("area_id");
            $city_id = $MArea->where(array("id"=>$area_id))->getField("city_id");
            $city_name = C("city_id")[$city_id];

            $list[$key]['bill_info'] = $realname.$val['input_year']."年".$val['input_month']."月";
	        switch ($val['type']) {
				case 1:
					$list[$key]['type_name'] = "日常";
                    $list[$key]['bill_info'] .= "日常";
					break;
				case 2:
                    $list[$key]['type_name'] = "退租";
                    $list[$key]['bill_info'] .= "退租";
                    break;
                case 3:
                    $list[$key]['type_name'] = "转租";
                    $list[$key]['bill_info'] .= "转租";
                    break;
                case 4:
                    $list[$key]['type_name'] = "换租";
                    $list[$key]['bill_info'] .= "换租";
                    break;
			}
            $list[$key]['bill_info'] .= $city_name."账单";
            
		}
		return $list;
    }

    /**
    * [修改支付][1支付宝][2微信][3余额抵押]
    **/
    public function setPayStatus($charge_id,$pay_type){
    	return $this->where(array("id"=>$charge_id))->save(array("pay_type"=>$pay_type));
    }
}

?>