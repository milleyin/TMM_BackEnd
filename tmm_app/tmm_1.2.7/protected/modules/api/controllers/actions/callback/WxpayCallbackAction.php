<?php 
/**
 * 微信回调支付 方法
 * @author Changhai Zhan
 *
 */
class WxpayCallbackAction extends CAction
{
	public function run()
	{
		error_reporting(E_ERROR);
		require_once (Yii::app()->basePath . '/extensions/Wxpay/lib/WxPay.Api.php');
		require_once (Yii::app()->basePath . '/extensions/Wxpay/lib/WxPay.Notify.php');
		require_once (Yii::app()->basePath . '/extensions/Wxpay/PayNotifyCallBack.php');
		//微信支付回调
		$notify = new PayNotifyCallBack();
		$notify->Handle(true);
	}
}