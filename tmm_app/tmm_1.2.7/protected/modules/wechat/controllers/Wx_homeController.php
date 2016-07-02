<?php
/**
 * Class Wx_homeController
 * @description 微信公众号（入口控制器）
 *
 * @author Moore Mo
 * @datetime 2015-12-11T14:16:20+0800
 */
class Wx_homeController extends AppController
{
    public function init() {
        Yii::import('ext.Wechat.JSSDK');
    }

    public function actionIndex()
    {
        $jssdk = new JSSDK("wx5ef6db5eeb32aeb0", "67aba54fbad7b1fc10b49b91f8a3e7f8");
        $signPackage = $jssdk->GetSignPackage();
        $this->renderPartial('index', array('signPackage'=>$signPackage));
    }
}