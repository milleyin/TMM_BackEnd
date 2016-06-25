<?php
namespace Sakura\Controller;
use Think\Controller;
use Common\Library\Util\PayNotifyCallBack;

class NotifyController extends Controller {

    /**
     * 微信支付回调（异步）
     */
    public function wxpay () 
    {
        $notify = new PayNotifyCallBack();
        $notify->Handle(true);
        die;
    }
}