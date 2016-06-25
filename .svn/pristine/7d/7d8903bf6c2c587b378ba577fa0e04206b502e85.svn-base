<?php
namespace Common\Controller;

use Think\Controller;

/**
 * 公共api控制器
 * Class ApiController
 * @package Common\Controller
 *
 * @author Moore Mo
 */
class ApiController extends Controller {
    const KEY = 'DyNbMXQxc8XGANb5';
    /**
     * 获取地区信息
     * @return mixed
     */
    public function getRegion() {
        $regionModel = M('Region');
        $id = max(intval(I('get.id')), 0);
        return $regionModel->where(array('pid' => $id, 'status' => 1))->order('nid')->select();
    }

    /**
     * 获取微信用户信息
     * @param $openid
     * @return array
     */
    public function getWxUserInfo($openid) {
        // 生成16位随机字符串
        $randStr = createNonceStr();
        // 签名
        $signStr = $this->_sign($openid, $randStr);
        $wxUserInfo = postCurl('http://mpapi.365tmm.com/home/index/index', array('openid' => $openid, 'rand_str' => $randStr, 'sign_str' => $signStr));
        return json_decode($wxUserInfo, true);
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