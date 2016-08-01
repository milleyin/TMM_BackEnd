<?php
/**
 * 帮助文件
 * @author Changhai Zhan
 *
 */
class Helper
{
    /**
     * 返回公司名
     * @return string
     */
    public static function getPowered()
    {
        return 'Shenzhen Tian Mimi Mdt InfoTech Ltd.';
    }
    
    /**
     * ipad 版本号
     * @return string
     */
    public static function getIpadVersion()
    {
        return '1.0.2';
    }
    
    /**
     * 过滤ip地址
     * @param unknown $ip
     * @return boolean
     */
    public static function allowIp($ip, $ipFilters)
    {
        if(empty($ipFilters)) {
            return true;
        }
        foreach($ipFilters as $filter) {
            if($filter==='*' || $filter===$ip || (($pos=strpos($filter,'*'))!==false && !strncmp($ip,$filter,$pos))) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * 跳转页面
     * @param unknown $url
     * @param unknown $content
     * @param string $page
     * @param string $json
     * @param string $source
     * @return Ambigous <string, multitype:string unknown >
     */
    public static function location($url='', $message ='')
    {
        header("Content-type:application/json;");
        $app = Yii::app();
        $request = $app->getRequest();
        if ($url != '') {
            if (is_array($url)) {
                $route = isset($url[0]) ? $url[0] : $app->defaultController;
                $url = $app->createUrl($route, array_splice($url, 1));
            }
        } else {
            $url = $app->controller->getLastUrl();
        }
        $return = array(
            'code'=>200,
            'errorCode' => 302,
            'source'=>$request->getUrl(),
            'location'=>$url,
            'result'=>1,
            'message'=>'success',
            'time' =>8
        );
        if ($message != '' && is_array($message)) {
            $return = array_merge($return, $message);
        } elseif ($message != '' && !is_array($message)) {
            $return['message'] = $message;
        }
        return json_encode($return);
    }
    
    /**
     * 获取数据类型
     * @param string $json
     * @param string $default
     * @return string
     */
    public static function getDataType($json = 'json', $default = 'html')
    {
        if (isset(Yii::app()->theme, Yii::app()->theme->name) && strpos(Yii::app()->theme->name, $json) !== false) {
            return $json;
        }
        return $default;
    }
    
    /**
     * 掷骰子概率计算
     * @param array $arr array('a'=>20,'b'=>30,'c'=>50)
     * @return bool/string 返回数组的KEY
     */
    public static function dice($rateArr)
    {
        $ret = false;
        $arr = array();
        $tmpMaxVal = 0;
        $tmpMaxKey = '';
        if ( !empty($rateArr)) {
            $max = array_sum($rateArr);
            $input = range(1, $max);            
            shuffle($input);        
            foreach ($rateArr as $key => $val) {
                $val = !empty($val) ? intval($val) : 0;
                if ($val > $tmpMaxVal) {
                    $tmpMaxVal = $val;
                    $tmpMaxKey = $key;
                }
                $tmpInput = array_splice($input, $val);
                shuffle($tmpInput);
                $spArr = $input;
                $input = $tmpInput;
                foreach ($spArr as $n) {
                    $arr[$n] = $key;
                }
            }
            $one = mt_rand(1, $max);
            $ret = isset($arr[$one]) ? $arr[$one] : $tmpMaxKey;
        }
        return $ret;
    }
    
    /**
     * 随机码
     * @param number $length
     * @param string $chars
     * @return string
     */
    public static function getRandCode($length = 8, $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789')
    {
        $charsLength = strlen($chars);
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars{mt_rand(0, $charsLength-1)};
        }
        return $str;
    }
    
    /**
     * 获取 访问的类型
     * @return string (weixin, ios, android, pc)|boolean
     */
    public static function getSource($default = false)
    {
        if (isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT']) {
            $server = strtolower($_SERVER['HTTP_USER_AGENT']);
            if (strrpos($server, 'micromessenger') !== false) {
                return 'weixin';
            } elseif (strrpos($server, 'iphone') !== false || strrpos($server, 'ipad') !== false || strrpos($server, 'ipod') !== false) {
                return 'ios';
            } elseif (strrpos($server, 'android') !== false) {
                return 'android';
            } elseif (strrpos($server,'windows nt') !== false) {
                return 'pc';
            }
        }
        return $default;
    }
    
    /**
     * 发送短信
     * @param unknown $phone
     * @param unknown $content
     * @return boolean|number
     */
    public static function sendSms($phone, $content)
    {
        //秘钥
        $sms_key = 'f1a763ef51b3828d7acadda7d5353a69';
        //地址
        $sms_url = 'http://sms-api.luosimao.com/v1/send.json';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $sms_url);  
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'api:' . $sms_key);  
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('mobile' =>$phone, 'message' => $content)); 
        $res = curl_exec($ch);
        curl_close($ch);
        $val = json_decode($res);
        if (YII_DEBUG) {
            Yii::log(json_encode($val), 'info', 'sendSms');
        }
        if (isset($val->error)) {
            if ($val->error == 0) {
                return true;
            } elseif ($val->error == -20) {
                return -1;
            }
        }
        return false;
    }
}