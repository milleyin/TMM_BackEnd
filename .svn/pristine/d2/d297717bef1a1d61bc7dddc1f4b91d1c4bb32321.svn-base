<?php
require_once __DIR__ . "/../lib/WxPay.Api.php";
/**
 * app 支付签名
 * @author Changhai Zhan
 *
 */
class AppApiPay
{
	/**
	 * 获取app接口数据
	 * @param unknown $UnifiedOrderResult
	 * @throws WxPayException
	 * @return array
	 */
	public function GetAppApiParameters($UnifiedOrderResult)
	{
		if (!array_key_exists("appid", $UnifiedOrderResult)
		|| !array_key_exists("prepay_id", $UnifiedOrderResult)
		|| $UnifiedOrderResult['prepay_id'] == "")
		{
			throw new WxPayException("参数错误" .json_encode($UnifiedOrderResult));
		}
		$appapi = new WxPayAppApiPay();
		//应用ID
		$appapi->SetAppid(WxPayConfig::APP_APPID);
		//商户号
		$appapi->SetPartnerid(WxPayConfig::APP_MCHID);
		//预支付交易会话ID
		$appapi->SetPrepayid($UnifiedOrderResult['prepay_id']);
		//扩展字段
		$appapi->SetPackage('Sign=WXPay');
		//随机字符串
		//$appapi->SetNoncestr($UnifiedOrderResult['nonce_str']');
		$appapi->SetNoncestr(WxPayApi::getNonceStr());
		//时间戳
		$appapi->SetTimestamp(time());
		//签名
		//Yii::log(json_encode($appapi->GetValues()), 'info');
		$appapi->SetSign();
		//所有的值
		$parameters = $appapi->GetValues();
		return $parameters;
	}
}