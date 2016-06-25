<?php
/**
 * 微信公众号SDK扩展类
 * Class Weixin
 *
 * @author Moore Mo
 */
class Weixin {
    private static $appId = 'wx5ef6db5eeb32aeb0';
    private static $appSecret = '67aba54fbad7b1fc10b49b91f8a3e7f8';

    /**
     * 获取openid
     * @return string $wechatid
     */
    public static function getOpenid() {
        $openid = Yii::app()->session['wechatid'];
        if (empty($openid)) {
            if (!isset($_GET['state'])) {
                $redirectUri = self::getCurUrl();
                self::requestCode($redirectUri);
            } elseif ((isset($_GET['state']) && $_GET['state'])) {
                $result = self::getAccessTokenByCode();
                Yii::app()->session['wechatid'] = $result['openid'];
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
    public static function getUserInfo($openid)
    {
        $result = Yii::app()->cache->get('userInfo_' . $openid);
        if (!$result) {
            $accessToken = self::getAccessToken();
            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $accessToken . '&openid=' . $openid . '&lang=zh_CN';
            $result = json_decode(self::getCurl($url), true);
            Yii::app()->cache->set('userInfo_' . $openid, $result, 7200);
        }
        return $result;
    }

    /**
     * 获取用户基本信息（包括UnionID机制）
     * @param string $openid
     * @return array
     */
    public static function getWxUserInfo($openid)
    {
        $accessToken = self::getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $accessToken . '&openid=' . $openid . '&lang=zh_CN';
        $result = json_decode(self::getCurl($url), true);
        return $result;
    }

    /**
     * 获取access_token，自动带缓存功能
     * @return string $accessToken
     */
    public static function getAccessToken()
    {
        $accessToken = Yii::app()->cache->get('access_token');
        if (!$accessToken) {
            if (empty(self::$appId) || empty(self::$appSecret)) {
                return false;
            }
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . self::$appId . '&secret=' . self::$appSecret;
            $result = json_decode(self::getCurl($url), true);
            if (array_key_exists('access_token', $result)) {
                Yii::app()->cache->set('access_token', $result['access_token'], 20);
                $accessToken = $result['access_token'];
            }
        }
        return $accessToken;
    }

    /**
     * 获取jsapi_ticket，自动带缓存功能
     * @return string $jsapiTicket
     */
    public static function getJsapiTicket()
    {
        $accessToken = self::getAccessToken();
        if (!$accessToken) {
            return false;
        }
        $jsapiTicket = Yii::app()->cache->get('jsapi_ticket');
        if (!$jsapiTicket) {
            $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $accessToken . '&type=jsapi';
            $result = json_decode(self::getCurl($url), true);
            if (array_key_exists('ticket', $result)) {
                Yii::app()->cache->set('jsapi_ticket', $result['ticket'], 7200);
                $jsapiTicket = $result['ticket'];
            }
        }
        return $jsapiTicket;
    }

    /**
     * 获取JSSDK配置信息
     * @return string $config
     */
    public static function getJssdkConfig()
    {
        $jsapiTicket = self::getJsapiTicket();
        if (!$jsapiTicket) {
            return false;
        }
        $nonceStr = createNoncestr();
        $time = time();
        $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $config = array();
        $config['appId'] = self::$appId;
        $config['timestamp'] = $time;
        $config['nonceStr'] = $nonceStr;
        $config['signature'] = self::getJssdkSign(array('noncestr' => $nonceStr, 'jsapi_ticket' => $jsapiTicket, 'timestamp' => $time, 'url' => $url));
        return $config;
    }

    /**
     * 请求CODE
     * @param string $redirectUri 重定向的回调链接地址
     */
    public static function requestCode($redirectUri)
    {
        $url = 'https://m.365tmm.com/wx.html?appid=' . self::$appId . '&scope=snsapi_base&state=1&redirect_uri=' . urlencode($redirectUri);
        //$url = 'http://test2.365tmm.net/wx.html?appid=' . self::$appId . '&scope=snsapi_base&state=1&redirect_uri=' . urlencode($redirectUri);
        //$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . self::$appId . '&redirect_uri=' . urlencode($redirectUri) . '&response_type=code&scope=snsapi_base&state=1#wechat_redirect';
        header("location: " . $url);
        exit;
    }

    /**
     * 通过code换取access_token
     * @return array
     */
    public static function getAccessTokenByCode()
    {
        $code = isset($_GET['code']) ? $_GET['code'] : '';
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . self::$appId . '&secret=' . self::$appSecret . '&code=' . $code . '&grant_type=authorization_code';
        $result = json_decode(self::getCurl($url), true);
        return $result;
    }

    /**
     * 获取JSSDK签名
     * @param $Obj
     * @return string
     */
    private static function getJssdkSign($Obj)
    {
        foreach ($Obj as $k => $v) {
            $Parameters[$k] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String = self::formatBizQueryParaMap($Parameters, false);
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
    private static function formatBizQueryParaMap($paraMap, $urlencode)
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
        $reqPar = '';
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }

    /**
     * php获取当前访问的完整url地址
     * @return string
     */
    private static function getCurUrl() {
        $url = 'http://';
        if (isset ( $_SERVER ['HTTPS'] ) && $_SERVER ['HTTPS'] == 'on') {
            $url = 'https://';
        }
        if ($_SERVER ['SERVER_PORT'] != '80') {
            $url .= $_SERVER ['HTTP_HOST'] . ':' . $_SERVER ['SERVER_PORT'] . $_SERVER ['REQUEST_URI'];
        } else {
            $url .= $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'];
        }
        // 兼容后面的参数组装
        if (stripos ( $url, '?' ) === false) {
            $url .= '?t=' . time ();
        }
        return $url;
    }

    /**
     * curl请求数据
     * @param string $url url地址
     * @return string $result 返回的数据
     */
    private static function getCurl($url){ //get https的内容
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //不输出内容
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * curl提交数据
     * @param string $url url地址
     * @param array $data post数据
     * @return string $result 返回的数据
     */
    private static function postCurl($url, $data) {
        $curl = curl_init(); //启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); //要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); //对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); //从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); //模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); //使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); //自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); //发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); //Post提交的数据包
        curl_setopt($curl, CURLOPT_COOKIEFILE, $GLOBALS['cookie_file']); //读取上面所储存的Cookie信息
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); //设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); //显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl); //执行操作
        if (curl_errno($curl)) {
            return 'Errno'.curl_error($curl);
        }
        curl_close($curl); //关闭CURL会话
        return $tmpInfo; //返回数据
    }
}