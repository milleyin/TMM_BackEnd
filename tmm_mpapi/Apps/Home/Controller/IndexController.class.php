<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {

    const KEY = 'DyNbMXQxc8XGANb5';

    /**
     * 根据微信id获取微信用户信息
     */
    public function index() {
        ! IS_POST && $this->error('非法访问');

        $wechatid = I('post.openid');
        $randStr = I('post.rand_str');
        $signStr = I('post.sign_str');

        $wxUserInfo = array();
        if (! empty($wechatid) && ! empty($randStr) && ! empty($signStr) && $signStr == $this->_sign($wechatid, $randStr)) {
            $wxUserInfo = A('Common/Weixin')->getWxUserInfo($wechatid);
        }

        $this->ajaxReturn($wxUserInfo);
    }

    /**
     * 签名方法
     * @param $wechatid
     * @param $randStr
     * @return string
     */
    private function _sign($wechatid, $randStr) {
        return md5($wechatid . KEY .$randStr);
    }

}