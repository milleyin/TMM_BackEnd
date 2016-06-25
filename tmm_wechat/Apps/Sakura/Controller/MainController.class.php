<?php
namespace Sakura\Controller;
use Think\Controller;
use Common\Library\Util\SMS;
use Common\Model\SmsModel;

/**
 * 公共控制器
 * Class MainController
 * @package Sakura\Controller
 *
 * @author Moore Mo
 */
class MainController extends Controller {
    /**
     * 验证手机号
     * @param $mobile 手机号
     * @return bool
     */
    protected function _isMobile($mobile)
    {
        if (!is_numeric($mobile))
            return false;
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
    }

    /**
     * 验证短信验证码
     * @param $mobile 手机号
     * @param $code 短信验证码
     * @param $use 短信用途
     * @return bool
     */
    protected function _checkCode($mobile, $code, $use)
    {
        // 取出openid
        $openid = session('openid');
        // 查询用户信息
        $model = M('User')->where(array('mobile' => $mobile, 'openid' => $openid))->find();
        if(!$model)
            return false;
        $params=array(
            'sms_id' => $model['id'],
            'sms_type' =>  SmsModel::send_user,
            'role_id' => $model['id'],
            'role_type' =>  SmsModel::send_user,
            'sms_use' => $use,
        );
        return SMS::verifycode($mobile, $params, $code);
    }

    /**
     * 获取公众号中的用户openid
     * @param $url 要跳转回来的url（一般是发起请求本身的link）
     * @return \用户的openid
     */
    protected function _getUserOpenId($url) {
        import('Common.Wxpay.lib.WxPay#Api', APP_PATH, '.php');
        import('Common.Wxpay.unit.WxPay#JsApiPay', APP_PATH, '.php');
        $tools = new \JsApiPay();
        return $tools->getUserOpenid($url);
    }


    /**
     * 格式化 钱 显示
     * @param $str
     * @param int $num
     * @return string
     */
    protected function number_format($str, $num=2){
        if(!$str)
            return '0.00';

        return number_format($str, $num);
    }
}