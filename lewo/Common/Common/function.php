<?php
/**
*
* 公共函数
*
**/

/**
 * [获取两个时间进行判断，判断年月是否相同]
 **/
function bothTimeIsEqual($time1,$time2){
    $time1_year = date("Y",strtotime($time1));
    $time1_month = date("m",strtotime($time1));
    $time2_year = date("Y",strtotime($time2));
    $time2_month = date("m",strtotime($time2));

    if ( $time1_year == $time2_year && $time1_month == $time2_month ) {
    	return true;
    } else {
    	return false;
    }
}

/**
* [获取人日]
* @param $year 年
* @param $month 月
* @param $ht_start_date 合同开始
* @param $ht_end_date 合同结束
* @param $ht_actual_end_time 实际退房日期
* @param $appoint_time 约定时间 即 约定退房日期
*
* @return 人日
**/
function get_person_day($year,$month,$contract_info,$appoint_time = null){
	$now_month = $year."-".$month;
	$ht_start_date = $contract_info['start_time'];//合同开始日
	$ht_end_date = $contract_info['end_time'];//合同开始日
	$ht_actual_end_time = $contract_info['actual_end_time'];//实际退房日
    $ht_person = $contract_info['person_count'];//合同人数

    //判断租客是否当该月入住
    $is_start_date = bothTimeIsEqual($ht_start_date,$now_month);

    //判断租客是否该月退房
    $is_end_date = bothTimeIsEqual($ht_actual_end_time,$now_month);

    $person_day = 0;
    if ( $is_start_date ) {
        if ( $is_end_date ) {
            //合同开始和实际退房的月份一样，获取两者之间的日数
            $lastday = date('Y-m-d', strtotime($ht_actual_end_time)); //退房时间
            $person_day = round((strtotime($lastday)-strtotime($ht_start_date))/86400)+1;
        } else {
            //是这个月入住则，人日获取的是，租期开始到月末的日数
            if ( !is_null($appoint_time) ) {
            	$lastday = date('Y-m-d', strtotime($appoint_time)); //约定时间
            } else {
            	$lastday = $now_month."-".date("t",strtotime($now_month));//获取该月最后一日
            }
            
            $person_day = round((strtotime($lastday)-strtotime($ht_start_date))/86400)+1;
        }
    } else {
        if ( $is_end_date ) {
        	if ( !is_null($appoint_time) && (strtotime($appoint_time) <= strtotime($ht_actual_end_time)) ) {
        		$person_day = date("d",strtotime($appoint_time));
        	} else {
        		//这个月就是退房的月数 那么这个月的人日就是到这一天
            	$person_day = date("d",strtotime($ht_actual_end_time));
        	}
        } else {
        	if ( !is_null($appoint_time) ) {
        		$person_day = date("d",strtotime($appoint_time));
        	} else {
        		//不是这个月入住也不是这个月退房的，则获取这个月的日数
	            // 因为是退房，所以获取是约定时间的日数
	            $person_day = date("t",strtotime($now_month));
        	} 
        }
    }
    return $person_day*$ht_person;
}

/**
* [矫正日期]
* [判断这个 年月日 的 日 是否大于该年月的日数，如果大于则取该月的日数]
**/
function correct_date($year,$month,$day){
	//获取该月的日数
	$current_date = $year."-".$month;
	$current_day = date("t",strtotime($current_date));
	$exact_day = ($day>=$current_day)?$current_day:$day;
	$format_date = $year."-".$month."-".$exact_day;
	return $format_date;
}

/**
* [获取电费]
* @param add_energy 增加的度数
* @param energy_stair 阶梯算法
* @return 电费
**/
function get_energy_fee($add_energy,$energy_stair){
	$energy_fee = 0;
	foreach ( $energy_stair AS $k=>$v ) {
        if ( $add_energy > $v['0'] ) { //是本阶
            if ( $add_energy >= $v['1'] ) { //超于本阶的度数
                $energy_fee += ( $v['1'] - $v['0'] ) * $v['2']; //本阶的度数 * 本阶单价
            } else {
                $this_add_energy = $add_energy - $v['0']; //本阶增加的度数
                $energy_fee += $this_add_energy * $v['2'];
            }
        }
    }
    return $energy_fee;
}

/**
* 创建唯一的账单号
**/
function getOrderNo(){
	return date('Ymds') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
}

/**
* [判断是否微信浏览器]
**/
function is_weixin(){
    if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
            return true;
    }  
    return false;
}

/**
* [获取某天签约合同的金额]
**/
function getTotalMoney($date){
	$MContract = M("contract");
	$sum_rent = $MContract->where(array("start_time"=>$date))->sum("rent");//租金
	$sum_fee = $MContract->where(array("start_time"=>$date))->sum("fee");//服务费
	$sum_deposit = $MContract->where(array("start_time"=>$date))->sum("deposit");//押金
	return $sum_fee+$sum_deposit+$sum_rent;
}

/**
* [获取某天签约的个数]
**/
function getSignCount($date){
	return M("contract")->where(array("start_time"=>$date))->count();
}

/**
* [获取某天缴定个数]
**/
function getJDCount($date){
	return M("schedule")->where(array("create_date"=>$date,"schedule_type"=>4))->count();
}

/**
* [获取某天总缴定金额]
**/
function getJDMoney($date){
	$sum_money = M("schedule")->where(array("create_date"=>$date,"schedule_type"=>4))->sum("money");
	return empty($sum_money)?0:$sum_money;
}	

/**
* [获取房间总数]
**/
function getRoomCount(){
	return M("room")->where(array("room_type"=>1,"is_show"=>1))->count();
}
/**
* [获取房间空置率和空置数]
**/
function getEmptyRoomCount(){
	return M("room")->where(array("status"=>0,"room_type"=>1,"is_show"=>1))->count();
}
/**
* [获取床位总数]
**/
function getBedCount(){
	return M("room")->where(array("room_type"=>2,"is_show"=>1))->count();
}
/**
* [获取床位空置率和空置数]
**/
function getEmptyBedCount(){
	return M("room")->where(array("status"=>0,"room_type"=>2,"is_show"=>1))->count();
}

/*移动端判断*/
function isMobile()
{ 
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
    {
        return true;
    } 
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA']))
    { 
        // 找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    } 
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT']))
    {
        $clientkeywords = array ('nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile'
            ); 
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
        {
            return true;
        } 
    } 
    // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT']))
    { 
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
        {
            return true;
        } 
    } 
    return false;
} 
/**
* [获取数组中的一栏]
**/
function i_array_column($input, $columnKey, $indexKey=null){
    if(!function_exists('array_column')){ 
        $columnKeyIsNumber  = (is_numeric($columnKey))?true:false; 
        $indexKeyIsNull            = (is_null($indexKey))?true :false; 
        $indexKeyIsNumber     = (is_numeric($indexKey))?true:false; 
        $result                         = array(); 
        foreach((array)$input as $key=>$row){ 
            if($columnKeyIsNumber){ 
                $tmp= array_slice($row, $columnKey, 1); 
                $tmp= (is_array($tmp) && !empty($tmp))?current($tmp):null; 
            }else{ 
                $tmp= isset($row[$columnKey])?$row[$columnKey]:null; 
            } 
            if(!$indexKeyIsNull){ 
                if($indexKeyIsNumber){ 
                  $key = array_slice($row, $indexKey, 1); 
                  $key = (is_array($key) && !empty($key))?current($key):null; 
                  $key = is_null($key)?0:$key; 
                }else{ 
                  $key = isset($row[$indexKey])?$row[$indexKey]:0; 
                } 
            } 
            $result[$key] = $tmp; 
        } 
        return $result; 
    }else{
        return array_column($input, $columnKey, $indexKey);
    }
}
?> 