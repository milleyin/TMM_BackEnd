<?php 
/**
 * 微信回调支付 方法
 * @author Changhai Zhan
 *
 */
class WxpayappCallbackAction extends CAction
{
	public function run()
	{
		error_reporting(E_ERROR);
		require_once (Yii::app()->basePath . '/extensions/Wxpay/lib/WxPay.Api.php');
		require_once (Yii::app()->basePath . '/extensions/Wxpay/lib/WxPay.Notify.php');
		require_once (Yii::app()->basePath . '/extensions/Wxpay/PayNotifyCallBackApp.php');
		//微信支付回调
		WxPayDataBase::$key = WxPayConfig::APP_KEY;
		
		$notify = new PayNotifyCallBackApp();
		$notify->Handle(true);
	}
}