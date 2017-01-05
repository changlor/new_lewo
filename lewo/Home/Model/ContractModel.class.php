<?php
namespace Home\Model;
use Think\Model;
/**
* [合同数据层]
*/
class ContractModel extends BaseModel {
	protected $table;
	protected $tableName = 'contract';

	public function __construct()
    {
        parent::__construct();
    	$this->table = M($this->tableName);
    }

    public function insert($contract)
    {
    	return $this->table->add($contract);
    }

    public function select($where, $field)
    {
        $field = empty($field) ? '' : $field;
        $where = empty($where) ? '' : $where;
        $field = is_array($field) ? implode(',', $field) : $field;
        return $this->table->field($field)->where($where);
    }

    public function selectField($where, $field)
    {
        return $this->select($where)->getField($field);
    }

    public function update($where)
    {
        $field = empty($field) ? '' : $field;
        $where = empty($where) ? '' : $where;
        $field = is_array($field) ? implode(',', $field) : $field;
        return $this->table->where($where);
    }

    public function join($joinTable, $where, $field)
    {
        return parent::join($this->table, $joinTable)->field($field)->where($where);
    }

    public function insertContract($contract)
    {
        return $this->insert($contract);
    }

    public function updateContract($where, $updateInfo)
    {
        return $this->update($where)->save($updateInfo);
    }

    public function getContractBill($input)
    {
        // 获取模型实例
        $DRoom = D('room'); $DSchedule = D('schedule'); $DAccount = D('account'); $DPay = D('pay');
        // 获取accountId
        $accountId = $input['accountId'];
        // 获取roomId
        $roomId = $input['roomId'];
        // 获取proId
        $proId = $input['proId'];
        $filters = [];
        if (is_numeric($proId)) {
            $filters = ['lewo_pay.pro_id' => $proId];
            $payInfo = $DPay->selectPay(['pro_id' => $proId], ['account_id', 'room_id']);
            $accountId = $payInfo['account_id'];
            $roomId = $payInfo['room_id'];
        }
        // 获取scheduleId
        $scheduleId = $input['scheduleId'];
        if (is_numeric($scheduleId)) {
            $scheduleInfo = $DSchedule->selectSchedule(['id' => $scheduleId], ['room_id', 'account_id']);
            $roomId = $scheduleInfo['room_id'];
            $accountId = $scheduleInfo['account_id'];
        }
        if (is_numeric($roomId) && is_numeric($accountId)) {
            $filters = ['lewo_contract.room_id' => $roomId, 'lewo_contract.account_id' => $accountId];
        }
        $joinTable = [
            'contract(pay)' => 'pro_id(pro_id)',
        ];
        $field = [
            // contract
            'lewo_contract.pro_id',
            'lewo_contract.deposit',
            'lewo_contract.rent',
            'lewo_contract.fee',
            'lewo_contract.wg_fee',
            'lewo_contract.book_deposit',
            'lewo_contract.balance',
            'lewo_contract.period',
            'lewo_contract.rent_type',
            'lewo_contract.start_time',
            'lewo_contract.end_time',
            'lewo_contract.rent_date',
            'lewo_contract.total_fee',
            'lewo_contract.room_id',
            'lewo_contract.cotenant',
            'lewo_contract.roomD',
            // pay
            'lewo_pay.favorable',
            'lewo_pay.favorable_des',
            'lewo_pay.price',
        ];
        $contractBill = $this->join($joinTable, $filters, $field)->order('lewo_contract.create_time desc')->find();
        $contractBill['cotenant'] = unserialize($contractBill['cotenant']);
        $accountInfo = $DAccount->selectAccount(['id' => $accountId], ['realname', 'mobile', 'card_no', 'contact2', 'email', 'id' => 'account_id']);
        $roomInfo = $DRoom->selectRoom(['id' => $roomId], ['room_code', 'id' => 'room_id', 'rent', 'room_fee']);
        return parent::response([true, '', ['contractInfo' => $contractBill, 'roomInfo' => $roomInfo, 'accountInfo' => $accountInfo]]);
    }

	/**
	* [根据用户id获取最新合同]
	**/
	public function getNewContractById($account_id){
		$where['account_id'] = $account_id;
		$where['contract_status'] = 1;//正常签约
		$order = "id desc";
		$result = $this->table->where($where)->order($order)->find();
		return $result;
	}

	/**
	* [获取未支付合同]
	**/
	public function getNotPayContract($account_id){
		$pay_info = M("pay")
				->alias('p')
				->field('c.*,p.*')
				->join("lewo_contract c ON p.pro_id=c.pro_id")
				->where(array("p.account_id"=>$account_id,"p.bill_type"=>C("bill_type_ht"),"p.pay_status"=>0,"is_show"=>1))
				->order("p.id desc")
				->find();

        $MHouses = M("houses");
        $MArea = M("area");
        $MRoom = M("room");
        $MAccount = M("account");
        if ( !is_null($pay_info) ) {
        	$house_code = $MRoom->where(array("id"=>$pay_info['room_id']))->getField("house_code");
        	$realname = $MAccount->where(array('id'=>$account_id))->getField("realname");
	        //根据house_code 判断广州或重庆
	        $area_id = $MHouses->where(array("house_code"=>$house_code))->getField("area_id");
	        $city_id = $MArea->where(array("id"=>$area_id))->getField("city_id");
	        $city_name = C("city_id")[$city_id];
	        $pay_info['bill_info'] = $realname."合同".$city_name."账单";
        } else {
        	return false;
        }

        return $pay_info;
	}

    public function putContract($input)
    {
        //获取模型实例
        $DRoom = D('room'); $DHouses = D('houses'); $DAccount = D('account'); $DPay = D('pay');
        // 获取管家id
        $stewardId = $_SESSION['steward_id'];
        if (!is_numeric($stewardId)) {
            return parent::response([false, '非法操作！']);
        }
        /*// 获取is_show，控制合同是否显示
        $isShow = is_null($input['isShow'])? 1: $input['isShow'];*/
        // 获取proId
        $proId = $input['proId'];
        if (is_null($proId)) {
            return parent::response([false, '账单号不存在！']);
        }
        // 获取房间roomId
        // --如果proId存在，查找roomId
        $roomId = empty($proId)
        ? $input['roomId']
        : $DPay->selectField(['pro_id' => $proId], 'room_id');
        if (!is_numeric($roomId)) {
            return parent::response([false, '房间不存在！']);
        }
        // 判断房间是否已签约或者已入住
        $roomStatus = $DRoom->selectField(['id' => $roomId], 'status');
        // 只有当房屋状态等于3的时候，可以被修改
        if ($roomStatus != 3) {
            return parent::response([false, '房屋合同已经无法修改！']);
        }
        // 获取真实姓名realName
        $realName = $input['realName'];
        // 获取手机mobile
        $mobile = $input['mobile'];
        $pattern = '/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/i';
        if (!empty($mobile) && !preg_match($pattern, $mobile)) {
            return parent::response([false, '手机号格式不对！']);   
        }
        // 获取邮箱email
        $email = $input['email'];
        $pattern = '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/i';
        if (!empty($email) && !preg_match($pattern, $email)) {
            return parent::response([false, '邮箱格式不对！']);
        }
        // 获取紧急联系人电话contact2
        $contact2 = empty($input['contact2']) ? 0 : $input['contact2'];
        $pattern = '/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/i';
        if (!empty($contact2) && !preg_match($pattern, $contact2)) {
            return parent::response([false, '联系人电话格式不对！']);
        }
        // 获取身份证idNo
        $idNo = $input['idNo'];
        $pattern = '/^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/i';
        if (!empty($idNo) && !preg_match($pattern, $idNo)) {
            return parent::response([false, '身份证格式不对！']);
        }
        // 获取租金rent
        $rent = $input['rent'];
        if (!empty($rent) && !is_numeric($rent)) {
            return parent::response([false, '租金出错！']);
        }
        // 获取personCount
        $personCount = $input['personCount'];
        if (!empty($personCount) && (!is_numeric($personCount) || $personCount < 1)) {
            return parent::response([false, '居住人数必须为数字且必须大于等于1']);
        }
        // 获取合租人姓名hzRealName
        $hzRealname = $input['hzRealname'];
        // 获取合租人手机hzMobile
        $hzMobile = $input['hzMobile'];
        // 获取合租人身份证$hzCardNo
        $hzCardNo = $input['hzCardNo'];
        // 组装合租信息
        $hzInfo = [];
        $hzInfo['hzRealname'] = $hzRealname;
        $hzInfo['hzMobile'] = $hzMobile;
        $hzInfo['hzCardNo'] = $hzCardNo;
        if (!((bool)trim($hzRealname) == (bool)trim($hzMobile) && (bool)trim($hzRealname) == (bool)trim($hzCardNo))) {
            return parent::response([false, '合租人信息有误！']);
        }
        // 验证合租人电话
        $pattern = '/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/i';
        if (!empty($hzMobile) && !preg_match($pattern, $hzMobile)) {
            return parent::response([false, '合租人电话格式有误！']);   
        }
        // 验证合租人身份证
        $pattern = '/^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1|[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/i';
        if (!empty($hzCardNo) && !preg_match($pattern, $hzCardNo)) {
            return parent::response([false, '合租人身份证格式不对！']);
        }
        // 获取物管费wgFee
        $wgFee = $input['wgFee'];
        if (!empty($wgFee) && !is_numeric($wgFee)) {
            return parent::response([false, '物管费类型出错！']);
        }
        // 获取服务费fee
        $fee = $input['fee'];
        if (!empty($fee) && !is_numeric($fee)) {
            return parent::response([false, '服务费类型出错！']);
        }
        // 获取付款方式，压几付几，rentType
        $rentType = $input['rentType'];
        $pattern = '/^(\d)[_](\d)$/i';
        if (!empty($rentType) && !preg_match($pattern, $rentType, $match)) {
            return parent::response([false, '付款方式不对！']);
        }
        // 押几
        $depositCount = isset($match[1]) ? $match[1] : '';
        // 付几
        $payCount = isset($match[2]) ? $match[2] : '';
        // 获取租期开始日startDate
        $startDate = $input['startDate'];
        $pattern = '/^\d{4}[-]\d{2}[-]\d{2}$/i';
        if (!empty($startDate) && !preg_match($pattern, $startDate)) {
            return parent::response([false, '起始日期格式不对！']);
        }
        // 获取租期截止日endDate
        $endDate = $input['endDate'];
        $pattern = '/^\d{4}[-]\d{2}[-]\d{2}$/i';
        if (!empty($endDate) && !preg_match($pattern, $startDate)) {
            return parent::response([false, '截止日期格式不对！']);
        }
        // 获取房间电表roomD
        $roomD = $input['roomD'];
        if (!empty($roomD) && !is_numeric($roomD)) {
            return parent::response([false, '房间电表类型不对！']);
        }
        // 获取押金depost
        $deposit = $input['deposit'];
        if (!empty($deposit) && !is_numeric($deposit)) {
            return parent::response([false, '押金类型不对！']);
        }
        // 获取优惠favorable
        $favorable = $input['favorable'];
        if (!empty($favorable) && !is_numeric($favorable)) {
            return parent::response([false, '优惠类型不对！']);
        }
        // 获取优惠描述
        $favorableDes = $input['favorableDes'];
        // 获取是否换房余额抵扣isDeduct
        $isDeduct = $input['isDeduct'];
        if (!empty($isDeduct) && $isDeduct != 1 && $isDeduct != 0) {
            return parent::response([false, '余额抵扣出错！']);
        }
        // 获取缴定抵扣bookDeposit
        $bookDeposit = $input['bookDeposit'];
        if (!empty($bookDeposit) && !is_numeric($bookDeposit)) {
            return parent::response([false, '缴定抵扣出错！']);
        }
        // 获取合同金额total
        $total = $input['total'];
        if (!empty($total) && $total != round($wgFee + $rent + $fee + $deposit - $bookDeposit - $favorableDes, 2)) {
            return parent::response([false, '合同金额出错！']);
        }
        
        // 获取租客管家合影
        $photo = $input['photo'];
        // 获取房屋状态houseCode
        $houseCode = D('room')->selectField(['id' => $roomId], 'house_code');
        // 获取房屋的托管结束日
        $houseEndDate = D('houses')->selectField(['room_id' => $roomId], 'end_date');
        // 将租期起始日和租期结束日，以及房屋托管结束日转换成时间戳，以方便比较
        $startDateTimestamp = strtotime($startDate);
        $endDateTimestamp = strtotime($endDate);
        $houseEndDateTimestamp = strtotime($houseEndDate);
        // 获取租期起始年份
        $startYear = date('Y', $startDateTimestamp);
        // 获取租期起始月份
        $startMonth = date('m', $startDateTimestamp);
        // 获取当前日期
        // $createTime = date('Y-m-d H:i:s', time());
        // 判断租期开始日是否大于租期结束日
        if (!empty($startDate) && !empty($endDate) && $startDateTimestamp > $endDateTimestamp) {
            return parent::response([false, '签约失败，租期开始日:' . $startDate . ' 大于 租期结束日:' . $endDate]);
        }
        // 判断租期结束日是否大于房间托管结束日
        if (!empty($endDate) && $endDateTimestamp > $houseEndDateTimestamp) {
            return parent::response([false, '签约失败，租期结束日:' . $endDate . ' 大于 房间托管结束日:' . $houseEndDate]);
        }
        // 设置并获取photo（如果存在）
        $photoDir = ' ';
        if (!empty($photo['name']['0'])) {
            // 实例化上传类
            $VUpload = new \Think\Upload();
            // 设置附件上传大小
            $VUpload->maxSize = 1024 * 1024 * 1;
            // 设置附件上传类型
            $VUpload->exts = ['jpg', 'gif', 'png', 'jpeg'];
            // 设置附件上传根目录
            $VUpload->rootPath = './Uploads/';
            // 设置附件上传（子）目录
            $VUpload->savePath = '';
            // 上传之后回调结果
            $res = $VUpload->upload();
            // 如果上传成功保存上传信息，并保存照片信息
            if ($res) {
                foreach ($res as $file) {
                    // 保存photo路径
                    if ($file['key'] == 'photo') {
                        $photoDir = $file['savepath'] . $file['savename'];
                    }
                }
            }
        }
        // 判断房屋状态
        $res = $DRoom->selectField(['id' => $roomId], 'status');
        if (!($res == 3)) {
            return parent::response([false, '房屋未处于签约状态！']);
        }
        // 获取房屋状态
        $roomStatus = $input['roomStatus'];
        if (!empty($roomStatus) && !is_numeric($roomStatus)) {
            return parent::response([false, '不存在该房屋状态']);
        }
        // 根据mobile获取account列表
        $accountId = $DAccount->selectField(['mobile' => $mobile], 'id');
        // 如果获取不到
        if (!empty($mobile) && !is_numeric($accountId)) {
            // 插入帐号
            $account = [
                'realname' => $realName,
                // 默认密码
                'password' => md5('123456'),
                'mobile' => $mobile,
                // 紧急联系人
                'contact2' => $contact2,
                // 身份证
                'card_no' => $card_no,
                'email' => $email,
                'register_time' => date('Y-m-d H:i:s', time())
            ];
            // 插入account并返回account_id
            $accountId = $DAccount->insertAccount($account);
        } elseif (!empty($mobile)) {
            // 更新租客信息
            $account = [
                'realname' => $realName,
                // 紧急联系人
                'contact2' => $contact2,
                // 身份证
                'card_no' => $idNo,
                'email' => $email
            ];
            // 更新account数据
            $affectedRows0 = $DAccount->updateAccount(['id' => $accountId], $account);
        }
        // 插入合同数据
        $contract = [
            'start_time' => $startDate,
            'end_time' => $endDate,
            'pro_id' => $proId,
            'account_id' => $accountId,
            'room_id' => $roomId,
            'deposit' => $deposit,
            'wg_fee' => $wgFee,
            'book_deposit' => $bookDeposit,
            'period' => $payCount,
            'steward_id' => $stewardId,
            'rent_type' => $rentType,
            'rent_date' => date('Y-m-d', strtotime($startDate . ' + ' . $payCount . ' month -1 day')),
            'rent' => $rent,
            'fee' => $fee,
            'contact2' => $contact2,
            'person_count' => $personCount,
            'cotenant' => serialize($hzInfo),
            'roomD' => $roomD,
            // 总金额
            'total_fee' => $total,
            // 合影
            'photo' => $photoDir
        ];
        // 去除空的数据并插入contract表
        $contract = array_filter($contract, function ($value) {
            return $value === 0 || !!$value;
        });
        $affectedRows1 = $this->updateContract(['pro_id' => $proId], $contract);
        // pay表数据
        $pay = [
            'pro_id' => $proId,
            'account_id' => $accountId,
            'room_id' => $roomId,
            'input_year' => $startYear,
            'input_month' => $startMonth,
            'favorable' => $favorable,
            'favorable_des' => $favorableDes,
            'price' => $total,
            'modify_log' => ' '
        ];
        // 插入pay表数据
        $pay = array_filter($pay, function ($value) {
            return $value === 0 || !!$value;
        });
        $pay['is_show'] = $isShow;
        if (!is_numeric($isShow)) {
            unset($pay['is_show']);
        }
        $affectedRows2 = $DPay->updatePay(['pro_id' => $proId], $pay);
        // 修改房屋状态
        $room = [];
        $room['status'] = $roomStatus;
        $room['account_id'] = $input['roomAccountId'];
        $room = array_filter($room, function ($value) {
            return $value === 0 || !!$value;
        });
        $affectedRows3 = $DRoom->updateRoom(['id' => $roomId], $room);
        if (
            (isset($affectedRows0) && !($affectedRows0 > 0)) &&
            !($affectedRows1 > 0) && !($affectedRows2 > 0) && !($affectedRows3 > 0)
            ) {
            return parent::response([false, '未做任何修改！']);
        }
        return parent::response([true, '', ['proId' => $proId]]);  
    }

    public function postContract($input)
    {
        //获取模型实例
        $DRoom = D('room'); $DHouses = D('houses'); $DAccount = D('account'); $DPay = D('pay');
        // 获取管家id
        $stewardId = $_SESSION['steward_id'];
        if (!is_numeric($stewardId)) {
            return parent::response([false, '非法操作！']);
        }
        // 获取房间roomId
        $roomId = $input['roomId'];
        if (!is_numeric($roomId)) {
            return parent::response([false, '房间不存在！']);
        }
        // 判断房间是否已签约或者已入住
        $roomStatus = $DRoom->selectField(['id' => $roomId], 'status');
        // 只有当房屋状态为0且1时可以签约
        if ($roomStatus != 0 && $roomStatus != 1) {
            return parent::response([false, '房屋已被出租！']);
        }
        // 获取真实姓名realName
        $realName = $input['realName'];
        if (empty($realName)) {
            return parent::response([false, '用户名不能为空！']);
        }
        // 获取手机mobile
        $mobile = $input['mobile'];
        $pattern = '/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/i';
        if (!preg_match($pattern, $mobile)) {
            return parent::response([false, '手机号格式不对！']);   
        }
        // 获取邮箱email
        $email = $input['email'];
        $pattern = '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/i';
        if (!empty($email) && !preg_match($pattern, $email)) {
            return parent::response([false, '邮箱格式不对！']);
        }
        // 获取紧急联系人电话contact2
        $contact2 = $input['contact2'];
        $pattern = '/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/i';
        if (!preg_match($pattern, $contact2)) {
            return parent::response([false, '紧急联系人电话不能为空或格式不对！']);
        }
        // 获取身份证idNo
        $idNo = $input['idNo'];
        $pattern = '/^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/i';
        if (!preg_match($pattern, $idNo)) {
            return parent::response([false, '身份证格式不对！']);
        }
        // 获取租金rent
        $rent = $input['rent'];
        if (!is_numeric($rent)) {
            return parent::response([false, '租金出错！']);
        }
        // 获取personCount
        $personCount = $input['personCount'];
        if (!is_numeric($personCount) || $personCount < 1) {
            return parent::response([false, '居住人数必须为数字且必须大于等于1']);
        }
        // 获取合租人姓名hzRealName
        $hzRealname = $input['hzRealname'];
        // 获取合租人手机hzMobile
        $hzMobile = $input['hzMobile'];
        // 获取合租人身份证$hzCardNo
        $hzCardNo = $input['hzCardNo'];
        // 组装合租信息
        $hzInfo = [];
        $hzInfo['hzRealname'] = $hzRealname;
        $hzInfo['hzMobile'] = $hzMobile;
        $hzInfo['hzCardNo'] = $hzCardNo;
        if (!((bool)trim($hzRealname) == (bool)trim($hzMobile) && (bool)trim($hzRealname) == (bool)trim($hzCardNo))) {
            return parent::response([false, '合租人信息有误！']);
        }
        // 验证合租人电话
        $pattern = '/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/i';
        if (!empty($hzMobile) && !preg_match($pattern, $hzMobile)) {
            return parent::response([false, '合租人电话格式有误！']);   
        }
        // 验证合租人身份证
        $pattern = '/^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1|[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/i';
        if (!empty($hzCardNo) && !preg_match($pattern, $hzCardNo)) {
            return parent::response([false, '合租人身份证格式不对！']);
        }
        // 获取物管费wgFee
        $wgFee = $input['wgFee'];
        if (!is_numeric($wgFee)) {
            return parent::response([false, '物管费类型出错！']);
        }
        // 获取服务费fee
        $fee = $input['fee'];
        if (!is_numeric($fee)) {
            return parent::response([false, '服务费类型出错！']);
        }
        // 获取付款方式，压几付几，rentType
        $rentType = $input['rentType'];
        $pattern = '/^(\d)[_](\d)$/i';
        if (!preg_match($pattern, $rentType, $match)) {
            return parent::response([false, '付款方式不对！']);
        }
        // 押几
        $depositCount = $match['1'];
        // 付几
        $payCount = $match['2'];
        // 获取租期开始日startDate
        $startDate = $input['startDate'];
        $pattern = '/^\d{4}[-]\d{2}[-]\d{2}$/i';
        if (!preg_match($pattern, $startDate)) {
            return parent::response([false, '起始日期格式不对！']);
        }
        // 获取租期截止日endDate
        $endDate = $input['endDate'];
        $pattern = '/^\d{4}[-]\d{2}[-]\d{2}$/i';
        if (!preg_match($pattern, $startDate)) {
            return parent::response([false, '截止日期格式不对！']);
        }
        // 获取房间电表roomD
        $roomD = $input['roomD'];
        if (!is_numeric($roomD)) {
            return parent::response([false, '房间电表类型不对！']);
        }
        // 获取押金depost
        $deposit = $input['deposit'];
        if (!is_numeric($deposit)) {
            return parent::response([false, '押金类型不对！']);
        }
        // 获取优惠favorable
        $favorable = $input['favorable'];
        if (!is_numeric($favorable)) {
            return parent::response([false, '优惠类型不对！']);
        }
        // 获取优惠描述
        $favorableDes = $input['favorableDes'];
        // 获取是否换房余额抵扣isDeduct
        $isDeduct = $input['isDeduct'];
        if ($isDeduct != 1 && $isDeduct != 0) {
            return parent::response([false, '余额抵扣出错！']);
        }
        // 获取合同金额total
        $total = $input['total'];
        if (!empty($total) && $total != round($wgFee + $rent + $fee + $deposit - $bookDeposit - $favorableDes, 2)) {
            return parent::response([false, '合同金额出错！']);
        }
        // 获取缴定抵扣bookDeposit
        $bookDeposit = $input['bookDeposit'];
        if (!is_numeric($bookDeposit)) {
            return parent::response([false, '缴定抵扣出错！']);
        }
        // 获取生成pro_id
        $proId = getOrderNo();
        // 获取租客管家合影
        $photo = $input['photo'];
        // 获取房屋状态houseCode
        $houseCode = D('room')->selectField(['id' => $roomId], 'house_code');
        // 获取房屋的托管结束日
        $houseEndDate = D('houses')->selectField(['room_id' => $roomId], 'end_date');
        // 将租期起始日和租期结束日，以及房屋托管结束日转换成时间戳，以方便比较
        $startDateTimestamp = strtotime($startDate);
        $endDateTimestamp = strtotime($endDate);
        $houseEndDateTimestamp = strtotime($houseEndDate);
        // 获取租期起始年份
        $startYear = date('Y', $startDateTimestamp);
        // 获取租期起始月份
        $startMonth = date('m', $startDateTimestamp);
        // 获取当前日期
        $createTime = date('Y-m-d H:i:s', time());
        if ($startDateTimestamp > $endDateTimestamp) {
            // 判断租期开始日是否大于房间托管结束日
            return parent::response([false, '签约失败，租期开始日:' . $startDate . ' 大于 租期结束日:' . $endDate]);
        }
        if ($endDateTimestamp > $houseEndDateTimestamp) {
            // 判断租期结束日是否大于房间托管结束日
            return parent::response([false, '签约失败，租期结束日:' . $endDate . ' 大于 房间托管结束日:' . $houseEndDate]);
        }
        // 设置并获取photo（如果存在）
        $photoDir = ' ';
        if (!empty($photo['name']['0'])) {
            // 实例化上传类
            $VUpload = new \Think\Upload();
            // 设置附件上传大小
            $VUpload->maxSize = 1024 * 1024 * 1;
            // 设置附件上传类型
            $VUpload->exts = ['jpg', 'gif', 'png', 'jpeg'];
            // 设置附件上传根目录
            $VUpload->rootPath = './Uploads/';
            // 设置附件上传（子）目录
            $VUpload->savePath = '';
            // 上传之后回调结果
            $res = $VUpload->upload();
            // 如果上传成功保存上传信息，并保存照片信息
            if ($res) {
                foreach ($res as $file) {
                    // 保存photo路径
                    if ($file['key'] == 'photo') {
                        $photoDir = $file['savepath'] . $file['savename'];
                    }
                }
            }
        }
        // 根据mobile获取account列表
        $accountId = $DAccount->selectField(['mobile' => $mobile], 'id');
        // 如果获取不到
        if (!is_numeric($accountId)) {
            // 插入帐号
            $account = [];
            $account['realname'] = $realName;
            // 默认密码
            $account['password'] = md5("123456");
            $account['mobile'] = $mobile;
            // 紧急联系人
            $account['contact2'] = $contact2;
            // 身份证
            $account['card_no'] = $idNo;
            $account['email'] = $email;
            $account['register_time'] = date("Y-m-d H:i:s",time());
            // 插入account并返回account_id
            $accountId = $DAccount->insertAccount($account);
        } else {
            // 更新租客信息
            $account = [];
            $account['realname'] = $realName;
            // 紧急联系人
            $account['contact2'] = $contact2;
            // 身份证
            $account['card_no'] = $idNo;
            $account['email'] = $email;
            // 更新account数据
            $DAccount->updateAccount(['id' => $accountId], $account);
        }
        // 插入合同数据
        $contract = [];
        $contract['start_time'] = $startDate;
        $contract['end_time'] = $endDate;
        $contract['pro_id'] = $proId;
        $contract['account_id'] = $accountId;
        $contract['room_id'] = $roomId;
        $contract['create_time'] = $createTime;
        $contract['deposit'] = $deposit;
        $contract['wg_fee'] = $wgFee;
        $contract['book_deposit'] = $bookDeposit;
        $contract['period'] = $payCount;
        $contract['steward_id'] = $stewardId;
        $contract['rent_type']     = $rentType;
        $contract['rent_date'] = date('Y-m-d', strtotime($startDate . ' + ' . $payCount . ' month -1 day'));
        $contract['rent'] = $rent;
        $contract['fee'] = $fee;
        $contract['contact2'] = $contact2;
        $contract['person_count'] = $personCount;
        $contract['cotenant'] = serialize($hzInfo);
        $contract['roomD'] = $roomD;
        // 总金额
        $contract['total_fee'] = $total;
        // 合影
        $contract['photo'] = $photoDir;
        // 插入contract表
        $contractId = $this->insertContract($contract);
        if (!is_numeric($contractId)) {
            return parent::response([false, '合同表生成失败！']);
        }
        // pay表数据
        $pay = [];
        $pay['pay_status'] = 0;
        $pay['bill_type'] = 2;
        $pay['is_send'] = 0;
        $pay['pro_id'] = $proId;
        $pay['account_id'] = $accountId;
        $pay['room_id'] = $roomId;
        $pay['create_time'] = $createTime;
        $pay['input_year'] = $startYear;
        $pay['input_month'] = $startMonth;
        $pay['should_date'] = $createTime;
        $pay['last_date'] = $createTime;
        $pay['favorable'] = $favorable;
        $pay['favorable_des'] = $favorableDes;
        $pay['price'] = $total;
        $pay['modify_log'] = ' ';
        $pay['is_show'] = 1;
        // 插入pay表数据
        $payId = $DPay->insertPay($pay);
        if (!is_numeric($payId)) {
            return parent::response([false, '支付表生成失败！']);
        }
        // 修改待办已完成
        // --如果存在sceduleId的话，说明该合同是在管家待办里被创建的
        if (is_numeric($scheduleId)) {
            $DSchedule->finishedSchedule(['id' => $scheduleId], ['is_finish' => 1]);
        }
        // 修改房屋状态
        $res = $DRoom->updateRoom(['id' => $roomId], ['status' => 3, 'account_id' => $accountId]);
        if (!($res > 0)) {
            return parent::response([false, '房屋状态修改失败！']);
        }
        return parent::response([true, '合同生成成功！', ['proId' => $proId]]);  
    }
}
