<?php
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller {
    public function __construct()
    {
        parent::__construct();
    }

    public function sms($id, $content)
    {
        //获取订单信息，只针对租客
        $pay_info = M('pay')->where(['pro_id' => $id])->find();
        $mobile = M('account')->where(['id' => $pay_info['account_id']])->getField('mobile');
        //引用短信库
        Vendor('ChuanglanSms.chuanglanSmsApi');
        //新建实例
        $sms  = new \ChuanglanSmsApi();
        //发送短信并回调
        $callback = $sms->sendSMS($mobile, $content, true);
        $sms_callback = $sms->execResult($callback);
        $send_time = date('Y-m-d H:i:s', time());
        //记录发送日志
        M('sms_log')->add([
            'mobile' => $mobile,
            'message' => $content,
            'send_time' => $send_time,
            'account_id' => $pay_info['account_id'],
            'room_id' => $pay_info['room_id'],
            'result' => serialize($sms_callback)
        ]);
        //返回发送信息
        $res['sms_callback'] = $sms->execResult($callback);
        $res['send_time'] = date('Y-m-d H:i:s', time());
        $res['content'] = $content;
        $res['id'] = $id;
        return $res;
    }
}