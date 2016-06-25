<?php
/**
 * 
 * @author Changhai Zhan
 * 微信定位
 */
class WxgpsShopsAction extends CAction
{
	// private $appId = 'wx7276cefc5ef30643';
	// private $appSecret = 'be4c6500837f69c93722f20d1e3ad527';
	private $appId = 'wx5ef6db5eeb32aeb0';
	private $appSecret = '67aba54fbad7b1fc10b49b91f8a3e7f8';
	private $url;

	/**
	 * 执行的方法
	 */
	public function run()
	{
		if (isset($_GET['wxgps'], $_GET['url']) && $_GET['wxgps'] == 'wxgps') {
			$this->url = $_GET['url'];
			$signPackage = $this->GetSignPackage();
			$this->controller->send($signPackage);
		} else {
			$this->controller->send_error(DATA_NULL);
		}
	}


	private function getSignPackage() {
		$jsapiTicket = $this->getJsApiTicket();

		// 注意 URL 一定要动态获取，不能 hardcode.
		//$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		//$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$url = $this->url;

		$timestamp = time();
		$nonceStr = $this->createNonceStr();

		// 这里参数的顺序要按照 key 值 ASCII 码升序排序
		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
		$signature = sha1($string);
		// "rawString" => $string
		// "url"       => $url,
		$signPackage = array(
			"appId"     => $this->appId,
			"nonceStr"  => $nonceStr,
			"timestamp" => $timestamp,
			"signature" => $signature
		);
		return $signPackage;
	}

	private function createNonceStr($length = 16) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}

	private function getJsApiTicket() {
		// jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
		$data = Yii::app()->session['wxgps_jsapi_ticket'];
		if (!isset($data['expire_time']) ||  $data['expire_time'] < time()) {
			$accessToken = $this->getAccessToken();
			// 如果是企业号用以下 URL 获取 ticket
			// $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
			$res = json_decode($this->httpGet($url));
			$ticket = $res->ticket;
			if ($ticket) {
				$data['expire_time'] = time() + 7000;
				$data['jsapi_ticket'] = $ticket;
				Yii::app()->session['wxgps_jsapi_ticket'] = $data;
			}
		} else {
			$ticket = $data['jsapi_ticket'];
		}

		return $ticket;
	}

	private function getAccessToken() {
		// access_token 应该全局存储与更新，以下代码以写入到文件中做示例
		$data = Yii::app()->session['wxgps_access_token'];
		if (!isset($data['expire_time']) ||  $data['expire_time'] < time()) {
			// 如果是企业号用以下URL获取access_token
			// $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
			$res = json_decode($this->httpGet($url));
			$access_token = $res->access_token;
			if ($access_token) {
				$data['expire_time'] = time() + 7000;
				$data['access_token'] = $access_token;
				Yii::app()->session['wxgps_access_token'] = $data;
			}
		} else {
			$access_token = $data['access_token'];
		}
		return $access_token;
	}

	private function httpGet($url) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 500);
		// 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
		// 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, FALSE);

		$res = curl_exec($curl);
		$result = json_decode($res, true);
		curl_close($curl);
		if (! array_key_exists('expires_in', $result)) {
			$this->controller->send_error(DATA_NULL);
		}

		return $res;
	}

}