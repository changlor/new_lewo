<?php 
/**
*  丁盯门锁接口
*  Author:feng
*  time : 2016/09/06
*/
class DDing
{	
	const ID = "6a4120106589a08e558ac4e7";
	const SECRET = "142646d34a29a0038fd81dfe3458e963";
	const APIPATH = "lockapi.dding.net/openapi/v1/";

	public function msg($key){
		$arr = array(
			1000 => "电话缺失!",
			1001 => "密码缺失!",
			1002 => "房间编码不存在!",
		);
		return $arr[$key];
	}

	protected function get_password(){
		return str_pad( mt_rand(0,999999), 6, 0,STR_PAD_LEFT );
	}

	/**
	* 获取access_token
	**/
	protected function access_token(){
		$access_token = self::findAccessToken();
		if( !$access_token ){
			self::getAccessTokenFromDDing();//post access_token接口更新access_token
			$access_token = self::findAccessToken(); //再获取access_token
		}
		return $access_token;
	}

	/**
	* [获取门锁密码列表，支持GET]
	**/
	public function fetch_passwords($room_code){
		$url = self::APIPATH."fetch_passwords";
		
		$uuid = self::get_uuid($room_code);
		if ( !$uuid ) {
			return self::msg(1002);
		} else {
			$param['access_token'] = self::access_token();
			$param['uuid'] = $uuid;
			$strparam = self::createLinkstringUrlencode($param);
			$result = self::getHttpResponseGET($url."?".$strparam);
			$result = json_decode($result,true);
			dump($result);
		}
	}

	/**
	* [添加密码，支持POST][如果没有管理员密码，必须先增加一个管理员该接口设置的管理员密码is_default为1]
	* @param $opt
	**/
	public function add_password($opt){
		if ( empty($opt['mobile']) ) {
			return self::msg(1000);
		}
		$uuid = self::get_uuid($opt['room_code']);
		if ( !$uuid ) {
			return self::msg(1002);
		}
		$url = self::APIPATH."add_password";
		$param['access_token'] = self::access_token();
		$param['home_id'] = $opt['home_id'];//公寓id，如果第三方的room_id是全局唯一，那可以不传home_id,只传home_id会增加外门锁的密码
		$param['room_id'] = $opt['room_id'];//需要添加门锁的房间room_id
		$param['uuid'] = $uuid;//优先uuid
		$param['phonenumber'] = $opt['mobile'];//密码生成后发送的目标电话号码
		$param['is_default'] = 0;//是否是管理员密码，0:非管理员 1:管理员
		$param['is_send_location'] = false;//短信发送即是否且带公寓信息，默认不携带 true时生效
		$param['password'] = self::get_password();//开锁密码6位
		$param['name'] = $opt['name'];//密码的名称 姓名
		$param['permission_begin'] = $opt['permission_begin'];//开始时间戳 为空的话则永久权限
		$param['permission_end'] = $opt['permission_end'];//结束时间戳 为空的话则永久权限
		$strparam = self::createLinkstring($param);
		$result = self::getHttpResponsePOST($url,$strparam);
		return $reuslt;
	}

	/**
	* [修改密码：有效期、名称或描述]
	**/
	public function update_password(){
		$uuid = self::get_uuid($opt['room_code']);
		if ( !$uuid ) {
			return self::msg(1002);
		}
		$url = self::APIPATH."update_password";
		$param['access_token'] = self::access_token();
		$param['uuid'] = $uuid;//优先uuid
		$param['password_id'] = "";//密码id在此设备中唯一
		$param['password'] = self::get_password();
		$param['is_send_location'] = false;
		$param['phonenumber'] = $opt['mobile'];
		$param['name'] = $opt['name'];
		$param['permission_begin'] = $opt['permission_begin'];//开始时间戳 为空的话则永久权限
		$param['permission_end'] = $opt['permission_end'];//结束时间戳 为空的话则永久权限
		$strparam = self::createLinkstring($param);
		$result = self::getHttpResponsePOST($url,$strparam);
		return $result;
	}

	/**
	* [修改密码：有效期、名称或描述]
	**/
	public function delete_password(){
		$uuid = self::get_uuid($opt['room_code']);
		if ( !$uuid ) {
			return self::msg(1002);
		}
		$url = self::APIPATH."update_password";
		$param['access_token'] = self::access_token();
		$param['uuid'] = $uuid;//优先uuid
		$param['password_id'] = "";//密码id在此设备中唯一
		$strparam = self::createLinkstring($param);
		$result = self::getHttpResponsePOST($url,$strparam);
		return $result;
	}

	/**
	* [查询单个房屋的设备列表，支持GET与POST]
	**/
	public function find_home_device(){
		$url = self::APIPATH."find_home_device";
		$param['access_token'] = self::access_token();
		$param['uuid'] = "23e13fc61fa5351c54f97c1c6c1576a6";
		$strparam = self::createLinkstring($param);
		$result = self::getHttpResponsePOST($url,$strparam);
		dump($result);
	}

	/**
	* [查询单个房屋的设备列表，支持GET与POST]
	**/
	public function find_home_devices(){
		$url = self::APIPATH."find_home_device";
		$param['access_token'] = self::access_token();
		$param['uuid'] = "23e13fc61fa5351c54f97c1c6c1576a6";
		$strparam = self::createLinkstring($param);
		$result = self::getHttpResponsePOST($url,$strparam);
		dump($result);
	}

	/**
	* [获取uuid]
	**/
	protected function get_uuid($room_code){
		$resutl = self::is_has_code($room_code);
		if ( $resutl ) {
			$Mdding_room = M("dding_room");
			return $Mdding_room->where(array("room_code"=>$room_code))->getField("uuid");
		} else {
			return false;
		}
	}

	/**
	* [判断房间编号是否存在]
	**/
	protected function is_has_code($room_code){
		$Mdding_room = M("dding_room");
		$result = $Mdding_room->where(array("room_code"=>$room_code))->find();
		if ( count($result) > 0 ) {
			if ( empty($result['uuid']) ) {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}

	/**
	* [找access_token]
	**/
	protected function findAccessToken(){
		$Mdding = M("dding_config");
		$access_token = $Mdding->getField("access_token");
		$expires_time = $Mdding->getField("expires_time");
		if ( empty($access_token) || time() >= $expires_time ) {
			return false;//需重新获取access_token
		} else {
			return $access_token;
		}
	}

	/**
	* [从接口中获取access_token]
	**/
	protected function getAccessTokenFromDDing(){
		$url = self::APIPATH."access_token";
		$param['client_id'] = self::ID;
		$param['client_secret'] = self::SECRET;
		$strparam = self::createLinkstring($param);
		$result = self::getHttpResponsePOST($url,$strparam);
		self::logResult($result);
		$result = json_decode($result,true);
		if ( !empty($result['access_token']) ) {
			$save['id'] = 1;
			$save['access_token'] = $result['access_token'];
			$save['expires_time'] = $result['expires_time'];
			$result = M("dding_config")->save($save);
			if ( $result !== false ) {
				return true;
			} else {
				return false;
			}
		}
	}	

	/**
	 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
	 * @param $para 需要拼接的数组
	 * return 拼接完成以后的字符串
	 */
	protected function createLinkstring($para) {
		$arg  = "";
		while (list ($key, $val) = each ($para)) {
			$arg.=$key."=".$val."&";
		}
		//去掉最后一个&字符
		$arg = substr($arg,0,count($arg)-2);
		
		//如果存在转义字符，那么去掉转义
		if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
		
		return $arg;
	}

	/**
	 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串，并对字符串做urlencode编码
	 * @param $para 需要拼接的数组
	 * return 拼接完成以后的字符串
	 */
	protected function createLinkstringUrlencode($para) {
		$arg  = "";
		while (list ($key, $val) = each ($para)) {
			$arg.=$key."=".urlencode($val)."&";
		}
		//去掉最后一个&字符
		$arg = substr($arg,0,count($arg)-2);
		
		//如果存在转义字符，那么去掉转义
		if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
		
		return $arg;
	}
	/**
	 * 远程获取数据，POST模式
	**/
	protected function getHttpResponsePOST($url, $para) {
		$curl = curl_init($url);
/*		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
		curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址*/
		curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
		curl_setopt($curl,CURLOPT_POST,true); // post传输数据
		curl_setopt($curl,CURLOPT_POSTFIELDS,$para);// post传输数据
		$responseText = curl_exec($curl);
		//var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
		curl_close($curl);
		
		return $responseText;
	}

	/**
	 * 远程获取数据，GET模式
	 */
	protected function getHttpResponseGET($url) {
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
/*		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
		curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址*/
		$responseText = curl_exec($curl);
		//var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
		curl_close($curl);
		
		return $responseText;
	}

	/**
	 * 写日志，方便测试（看网站需求，也可以改成把记录存入数据库）
	 * 注意：服务器需要开通fopen配置
	 * @param $word 要写入日志里的文本内容 默认值：空值
	 */
	public function logResult($word='') {
		$fp = fopen("ddinglog.txt","a");
		flock($fp, LOCK_EX) ;
		fwrite($fp,"\n"."执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
		flock($fp, LOCK_UN);
		fclose($fp);
	}
}
?>