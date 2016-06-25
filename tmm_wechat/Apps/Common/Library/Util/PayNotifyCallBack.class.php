<?php
namespace Common\Library\Util;
use Common\Model\SmsModel;
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);

require_once __DIR__ . "/../../Wxpay/lib/WxPay.Api.php";
require_once __DIR__. '/../../Wxpay/lib/WxPay.Notify.php';

class PayNotifyCallBack extends \WxPayNotify
{
    // 查询订单
    public function Queryorder($transaction_id)
    {
        $input = new \WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = \WxPayApi::orderQuery($input);
        if(array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS")
        {
            
            // 如果查询成功，证明微信支付已成功，更新订单状态为已支付
            $orderModel = D('Order');
            $condition = array('order_no' => $result['out_trade_no'], 'order_status' => $orderModel::order_status_pay_not);
            $orderData = $orderModel->where($condition)->find();
            if (! empty($orderData)) {
                // 更新订单状态为已支付
                if ($orderModel->where($condition)->field('order_status')->save(array('order_status' => $orderModel::order_status_pay_yes))) {
                    // 短信通知，用户购买票成功的信息
                    $this->sendSmsUser($orderData);
                    return 'SUCCESS';
                }
            }
            return true;
        }
        return false;
    }

    // 重写回调处理函数
    public function NotifyProcess($data, &$msg)
    {
        $notfiyOutput = array();

        if(!array_key_exists("transaction_id", $data)){
            $msg = "输入参数不正确";
            return false;
        }
        //查询订单，判断订单真实性
        if(!$this->Queryorder($data["transaction_id"])){
            $msg = "订单查询失败";
            return false;
        }
        return true;
    }

    /**
     * 通知用户购买成功，并显示信息
     * @param $orderInfo 订单信息
     */
    private function sendSmsUser($orderInfo)
    {
        $userInfo = M('User')->where(array('id' => $orderInfo['user_id']))->find();
        if ($orderInfo['type'] == 1) {
            $info = "普通票{$orderInfo['number']}张";
        }

        if ($orderInfo['type'] == 2) {
            $info = "夜樱票{$orderInfo['number']}张";
        }
        $userNoticeOrderConf = C('USER_NOTICE_ORDER');
        $params = array(
            'sms_id' => $userInfo['id'],
            'sms_type' =>  SmsModel::send_user,
            'role_id' => $userInfo['id'],
            'role_type' =>  SmsModel::send_user,
            'sms_use' => SmsModel::use_notice_order,
            'code'=> '',
            'sms_source'=> SmsModel::source_weixin,
            'login_address'=>'',
            'sms_error'=>  0,
            'end_time'=> time()
        );
        // 发送短信，通知用户购买成功
        SMS::send($userInfo['mobile'], $params, strtr($userNoticeOrderConf['content'], array('{info}'=>$info)));

    }
}
