<?php
class PayNotifyCallBackApp extends WxPayNotifyApp
{
    // 查询订单
    public function Queryorder($transaction_id)
    {
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = WxPayApiApp::orderQuery($input);
        if(array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS")
        {
        	//订单号
        	$data['out_trade_no'] = $result['out_trade_no'];
        	//微信订单号
        	$data['trade_no'] = $result['transaction_id'];
        	//支付价格
        	$data['total_fee'] = $result['cash_fee'] / 100;
        	//支付账户
        	$data['buyer_email'] = $result['openid'];
        	// 支付成功
         	if (Yii::app()->controller->notify_data($data, Order::pay_type_wxpay))
        	{
        		return 'SUCCESS';
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
}