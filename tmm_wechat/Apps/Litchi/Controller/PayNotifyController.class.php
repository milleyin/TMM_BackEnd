<?php
namespace Litchi\Controller;
use Think\Controller;
use Common\Library\Util\WxPayNotifyCallBack;

class PayNotifyController extends Controller {

    /**
     * 微信支付回调（异步）
     */
    public function wxpay()
    {
        $notify = new WxPayNotifyCallBack();
        $notify->Handle(true);
        die;
    }
}