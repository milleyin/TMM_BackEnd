<?php
namespace Common\Controller;

use Think\Controller;

/**
 * Class WeixinController
 * @package Common\Controller
 *
 * @author Moore Mo
 */
class WeixinController extends Controller
{

    private $appId = 'wx5ef6db5eeb32aeb0';
    private $appSecret = '67aba54fbad7b1fc10b49b91f8a3e7f8';

    /**
     * 获取openid
     * @return string $wechatid
     */
    public function getOpenid()
    {
        $openid = I('session.wechatid');
        if (empty($openid)) {
            if (!isset($_GET['state'])) {
                $redirectUri = getCurUrl();
                $this->requestCode($redirectUri);
            } elseif (I('get.state')) {
                $result = $this->getAccessTokenByCode();
                session('wechatid', $result['openid']);
                $openid = $result['openid'];
            }
        }
        return $openid;
    }

    /**
     * 获取用户基本信息（包括UnionID机制）
     * @param string $openid
     * @return array
     */
    public function getUserInfo($openid)
    {
        $result = S('userInfo_' . $openid);
        if (!$result) {
            $accessToken = $this->getAccessToken();
            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $accessToken . '&openid=' . $openid . '&lang=zh_CN';
            $result = json_decode(getCurl($url), true);
            if (isset($result['openid'])) {
               S('userInfo_' . $openid, $result, $result['expires_in'] - 100);
            }
        }
        return $result;
    }

    /**
     * 获取用户基本信息（包括UnionID机制）
     * @param string $openid
     * @return array
     */
    public function getWxUserInfo($openid)
    {
        $accessToken = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $accessToken . '&openid=' . $openid . '&lang=zh_CN';
        $result = json_decode(getCurl($url), true);
        return $result;
    }

    /**
     * 获取access_token，自动带缓存功能
     * @return string $accessToken
     */
    public function getAccessToken()
    {
        $accessToken = S('access_token');
        if (!$accessToken) {
            if (empty($this->appId) || empty($this->appSecret)) {
                return false;
            }
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $this->appId . '&secret=' . $this->appSecret;
            $result = json_decode(getCurl($url), true);
            if (array_key_exists('access_token', $result)) {
                S('access_token', $result['access_token'], $result['expires_in'] - 100);
                $accessToken = $result['access_token'];
            }
        }
        return $accessToken;
    }

    /**
     * 获取jsapi_ticket，自动带缓存功能
     * @return string $jsapiTicket
     */
    public function getJsapiTicket()
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return false;
        }
        $jsapiTicket = S('jsapi_ticket');
        if (!$jsapiTicket) {
            $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $accessToken . '&type=jsapi';
            $result = json_decode(getCurl($url), true);
            if (array_key_exists('ticket', $result)) {
                S('jsapi_ticket', $result['ticket'], $result['expires_in'] - 100);
                $jsapiTicket = $result['ticket'];
            }
        }
        return $jsapiTicket;
    }

    /**
     * 获取JSSDK配置信息
     * @return string $config
     */
    public function getJssdkConfig()
    {
        $jsapiTicket = $this->getJsapiTicket();
        if (!$jsapiTicket) {
            return false;
        }
        $nonceStr = createNoncestr();
        $time = time();
        $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $config = array();
        $config['appId'] = $this->appId;
        $config['timestamp'] = $time;
        $config['nonceStr'] = $nonceStr;
        $config['signature'] = $this->getJssdkSign(array('noncestr' => $nonceStr, 'jsapi_ticket' => $jsapiTicket, 'timestamp' => $time, 'url' => $url));
        return $config;
    }

    /**
     * 请求CODE
     * @param string $redirectUri 重定向的回调链接地址
     */
    public function requestCode($redirectUri)
    {
        $url = 'https://m.365tmm.com/wx.html?appid=' . $this->appId . '&scope=snsapi_base&state=1&redirect_uri=' . urlencode($redirectUri);
        //$url = 'http://test2.365tmm.net/wx.html?appid=' . $this->appId . '&scope=snsapi_base&state=1&redirect_uri=' . urlencode($redirectUri);
        //$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->appId . '&redirect_uri=' . urlencode($redirectUri) . '&response_type=code&scope=snsapi_base&state=1#wechat_redirect';
        redirect($url);
    }

    /**
     * 通过code换取access_token
     * @return array
     */
    public function getAccessTokenByCode()
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->appId . '&secret=' . $this->appSecret . '&code=' . I('get.code') . '&grant_type=authorization_code';
        $result = json_decode(getCurl($url), true);
        return $result;
    }

    /**
     * 获取JSSDK签名
     * @param $Obj
     * @return string
     */
    private function getJssdkSign($Obj)
    {
        foreach ($Obj as $k => $v) {
            $Parameters[$k] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);
        //echo '【string1】'.$String.'</br>';
        //签名步骤二：对string1进行sha1签名
        $result_ = sha1($String);
        //echo "【result】 ".$result_."</br>";
        return $result_;
    }

    /**
     * 作用：格式化参数，签名过程需要使用
     * @param $paraMap
     * @param $urlencode
     * @return string
     */
    private function formatBizQueryParaMap($paraMap, $urlencode)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if ($urlencode) {
                $v = urlencode($v);
            }
            //$buff .= strtolower($k) . "=" . $v . "&";
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar;
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }
}