<?php
namespace Common\Library\Util;
use Common\Model\SmsModel;
use Think\Log;

ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);

require_once __DIR__ . "/../../Wxpay/lib/WxPay.Api.php";
require_once __DIR__. '/../../Wxpay/lib/WxPay.Notify.php';

class WxPayNotifyCallBack extends \WxPayNotify
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
            $model = M();
            $lzOrderModel = D('LzOrder');
            $lzCardModel = M('LzCard');
            $lzTicketOrderModel = M('LzTicketOrder');
            $lzLogisticsModel = M('LzLogistics');

            $model->startTrans();

            try {
                $condition = array('order_no' => $result['out_trade_no'], 'order_status' => $lzOrderModel::order_status_pay_not);
                $orderInfo = $lzOrderModel->where($condition)->find();
                if (! empty($orderInfo)) {
                    // 更新订单状态为已支付
                    $updateOrderResult = $lzOrderModel->where($condition)->save(array('transaction_id'=> $result['transaction_id'], 'order_status' => $lzOrderModel::order_status_pay_yes, 'up_time' => time()));
                    if ($updateOrderResult === false) {
                        throw new \Exception('更新订单失败');
                    }

                    // 电子门票订单支付成功后，更新电子门票的状态为有效
                    if ($orderInfo['type'] == 1) {
                        $updateTicketStatusResult = $lzTicketOrderModel->where(array('order_id' => $orderInfo['id'], 'type' => 1))->save(array('is_valid' => 1));
                        if ($updateTicketStatusResult === false) {
                            throw new \Exception('更新订单失败');
                        }
                    }

                    // 卡券订单支付成功后，更新电子卡的状态为有效
                    if ($orderInfo['type'] == 2) {
                        $updateCardStatusResult = $lzCardModel->where(array('order_id' => $orderInfo['id'], 'type' => 1))->save(array('is_valid' => 1));
                        if ($updateCardStatusResult === false) {
                            throw new \Exception('更新订单失败');
                        }
                        // 更新赠送票有效
                        $updateTicketOrderStatusResult = $lzTicketOrderModel->where(array('order_id' => $orderInfo['id'], 'type' => 2))->save(array('is_valid' => 1));
                        if ($updateTicketOrderStatusResult === false) {
                            throw new \Exception('更新订单失败');
                        }
                    }

                    // 物流订单支付成功后，更新物流信息的状态为有效
                    if ($orderInfo['type'] == 3) {
                        // 提果方式为邮寄，运费支付成功后，更新卡券提果的状态
                        $updateCardResult = $lzCardModel->where(array('id' => $orderInfo['ticket_id']))->save(array('carry_type' => 1, 'is_carry' => 1, 'carry_time' => time()));
                        if ($updateCardResult === false) {
                            throw new \Exception('更新订单失败');
                        }

                        $updateLogisticsStatusResult = $lzLogisticsModel->where(array('order_id' => $orderInfo['id']))->save(array('is_valid' => 1));
                        if ($updateLogisticsStatusResult === false) {
                            throw new \Exception('更新订单失败');
                        }
                    }

                    $model->commit();

                    // 短信通知，用户购买票成功的信息
                    $this->sendSmsUser($orderInfo);
                    return 'SUCCESS';
                }
            } catch(\Exception $e) {
                $model->rollback();
                return false;
            }
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
        $userInfo = M('LzUser')->where(array('mobile' => $orderInfo['mobile'], 'wechatid' => $orderInfo['wechatid']))->find();

        $userNoticeOrderConf = C('USER_NOTICE_ORDER');

        if ($orderInfo['type'] == 1) {
            $info = "门票{$orderInfo['number']}张";
        }

        if ($orderInfo['type'] == 2) {
            $info = "桂味·大宗提货券{$orderInfo['number']}张";
        }

        if ($orderInfo['type'] == 3) {
            // 邮寄物流的卡券的信息
            $logisticsInfo = M('LzLogistics')->where(array('order_id' => $orderInfo['id'], 'is_valid' => 1))->find();
            $userNoticeOrderConf = C('USER_NOTICE_LOGISTICS');
            $info = "卡券号：{$logisticsInfo['card_no']}";
        }




        $params = array(
            'sms_id' => $userInfo['id'],
            'sms_type' =>  SmsModel::send_user,
            'role_id' => $userInfo['id'],
            'role_type' =>  SmsModel::send_user,
            'sms_use' => SmsModel::litchi_use_notice_order,
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
