<?php
/**
 * 保险
 * @author Moore Mo
 */
class Insurance
{
    // 联盟 ID
    const UID = '1111085';
    // 识别 ID
    const SID = 'qljltest';
    // 密钥
    const APP_SECRET = 'abcd1111085';
    // API协议的版本
    const VERSION = '1.0.1';
    // API基础链接
    const API_URL = 'http://58.240.26.203:8090/api';
    // 保险计划ID（华泰境内旅行险标准款），目前选定为这一款保险套餐
    const PLAN_ID = 175;
    // 登录用户ID
    const LOGIN_ID = '1111086';
    // 登录用户名
    const LOGIN_NAME = 'qljltest';
    // 时间戳， 格式为 Y-m-d H:m:s， 例如： 2015-08-08 08:08:08
    private $ts;

//    public function test()
//    {
////        array(
////            'loginId' => 0, // 可选
////            'orderId' => 0, // 可选
////            'policyId' => 0, // 可选
////            'applicationId' => 0
////        );
//        //return $this->sign();
////         $this->redirect(array('create_4','id'=>$model->id));
//        //$this->sign();
////        $result = $this->plan_detail();
////        $result = $this->order_add();
////        $result = $this->order_detail('sdf');
////          $result = $this->order_confirm(151013280634);
////        $result = $this->order_cancel(151013280632);
////        $result = $this->order_pay(null);
////        $result = $this->underwrite(151015281096);
//        $result = $this->policy_detail(151013280634);
//
//        echo '<pre>';
//        print_r($result);
////        var_dump($result);
//    }

    /**
     * 保单详情
     * @param int $applicationId 订单中的applicationId
     * @return bool|mixed
     */
    public function policy_detail($applicationId)
    {
        $url = $this->getUrl(self::API_URL.'/policy/get', array('applicationId'=>$applicationId));
        $return = json_decode($this->send($url));
        if (!empty($return) && !empty($return->policy)) {
            return $return;
        }
        return false;
    }

    /**
     * 获取保险承保情况
     * @param int $orderId 订单中的id
     * @return bool|mixed
     */
    public function underwrite($orderId)
    {
        $url = $this->getUrl(self::API_URL.'/order/undWrResult', array('orderId'=>$orderId));
        $return = json_decode($this->send($url));
        if (!empty($return) && !empty($return->underwriteInfo)) {
            return $return;
        }
        return false;
    }

    /**
     * 订单支付
     * @param int $orderId 订单id
     * @return bool|mixed
     *
     * 备注：支付接口改为/accpay/singlePay，带的参数为orderId,loginId,loginName
     */
    public function order_pay($orderId)
    {
        $params = array(
            'orderId' => $orderId,
            'loginId' => self::LOGIN_ID,
            'loginName' => self::LOGIN_NAME,
        );

        $url = $this->getUrl(self::API_URL.'/accpay/singlePay', $params);
        $return = json_decode($this->send($url));
        if ($return->payStatus == 1) {
            return $this->pay_callback($orderId);
        }
        return false;
    }

    /**
     * 确认订单
     * @param int $orderId 订单id
     * @return bool
     */
    public function order_confirm($orderId)
    {
        $url = $this->getUrl(self::API_URL.'/order/confirm', array('orderId'=>$orderId));
        $return = json_decode($this->send($url));
        if (!empty($return) && !empty($return->order)) {
            return true;
        }
        return false;
    }

    /**
     * 取消订单
     * @param int $orderId 订单id
     * @return bool
     */
    public function order_cancel($orderId)
    {
        $url = $this->getUrl(self::API_URL.'/order/cancel', array('orderId'=>$orderId));
        $return = json_decode($this->send($url));
        if (!empty($return) && !empty($return->order)) {
            return true;
        }
        return false;
    }

    /**
     * 订单详情
     * @param int $orderId 订单id
     * @return bool|mixed
     */
    public function order_detail($orderId)
    {
        $url = $this->getUrl(self::API_URL.'/order/get', array('orderId'=>$orderId));
        $return = $this->send($url);
        if (empty($return)) {
            return false;
        }
        return json_decode($return);
    }

    /**
     * 保险下单
     * @param array $params 订单参数信息
     * array(
     *   "period_start" =>"2015-10-16 00", // 保障期间开始日期
     *   "period_end" =>"2015-10-16 24", // 保障期间结束日期
     *   'applicant' => array(   // 投保人信息
     *       'name'=> '小沫', // 姓名
     *       'cardNo'=> '150602199010181079', // 身份证号码
     *       'province'=> 'Anhui', // 省代号
     *       'city'=> 'Anhui_Hefei', // 市代号
     *       'mobile'=> '13578941738', // 手机
     *       'email'=> '11@163.com', // 邮箱
     *   ),
     *   'insureds' => array( // 被投保人信息
     *       array(
     *           'name'=> '小沫', // 姓名
     *           'cardNo'=> '150602199010181079', // 身份证号码
     *           'mobile'=> '13578941738', // 手机
     *       ),
     *   )
     * );
     * @return bool|mixed
     */
    public function order_add($params=array())
    {
        if (empty($params)) {
            return false;
        }
//        $params = array(
////            'planId' => 175,
//            "period_start" =>"2015-10-16 00",
//            "period_end" =>"2015-10-16 24",
//            'applicant' => array(
//                'name'=> '小沫',
////                'cardType'=> 1,
//                'cardNo'=> '150602199010181079',
////                'gender'=> 1,
////                'birthday'=> '1986-11-26',
//                'province'=> 'Anhui',
//                'city'=> 'Anhui_Hefei',
//                'mobile'=> '13578941738',
//                'email'=> '11@163.com',
//            ),
//            'insureds' => array(
//                array(
//                    'name'=> '小沫',
////                    'cardType'=> 1,
//                    'cardNo'=> '150602199010181079',
////                    'birthday'=> '1986-11-26',
////                    'gender'=> 1,
//                    'mobile'=> '13578941738',
////                    'buyCopies'=> 1,
////                    'relationshipToPolicyholder'=> '1',
//                ),
//                array(
//                    'name'=> '小张',
////                    'cardType'=> 1,
//                    'cardNo'=> '350526198002193246',
////                    'birthday'=> '1987-11-26',
////                    'gender'=> 2,
//                    'mobile'=> '18111111111',
////                    'buyCopies'=> 1,
////                    'relationshipToPolicyholder'=> '14',
//                ),
//                array(
//                    'name'=> '阿拉斯加',
////                    'cardType'=> 1,
//                    'cardNo'=> '440510198812150038',
////                    'birthday'=> '1990-01-01',
////                    'gender'=> 1,
//                    'mobile'=> '18122222222',
////                    'buyCopies'=> 1,
////                    'relationshipToPolicyholder'=> '14',
//                ),
//            )
//        );

        $params_temp = array();
        // 订单的操作方式，必须为 2
        $params_temp['operateType'] = 2;
        // 保险计划id
        $params_temp['planId'] = self::PLAN_ID;
        $period_str = '';
        $applicant_cardNo = '';
        foreach($params as $key=>$val)
        {
            // 保险计划id
            if ($key == 'planId') {
                $params_temp['planId'] = $val;
            }
            // 保险期限开始时间
            if ($key == 'period_start') {
                $period_str .= $val;
            }
            // 保险期限结束时间
            if ($key == 'period_end') {
                $period_str .= '&period='.$val;
                $params_temp['period'] = $period_str;
            }
            // 投保人信息
            if ($key == 'applicant') {
                if (is_array($val)) {
                    // 'cardType'=> ,
                    $params_temp['applicant.cardType'] = 1;
                    foreach ($val as $applicant_key=>$applicant_val) {
                        if ($applicant_key == 'cardNo') {
                            $applicant_cardNo = $applicant_val;
                            // 44 05 10 19 88 12 15 00 38
                            $params_temp['applicant.birthday'] =  $this->get_birthday($applicant_val);
                            $params_temp['applicant.gender'] = substr($applicant_val, 16,1)%2==0?2:1;
                        }
                        $params_temp['applicant.'.$applicant_key] = $applicant_val;
                    }
                }
            }
            // 被投保人信息
            if ($key == 'insureds') {
                if (is_array($val)) {
                    foreach ($val as $insureds_key=>$insureds_val) {
                        $params_temp['insureds['.$insureds_key.'].cardType'] = 1;
                        $params_temp['insureds['.$insureds_key.'].buyCopies'] = 1;
                        foreach($insureds_val as $insured_key=>$insured_val) {
                            if ($insured_key == 'cardNo') {
                                $params_temp['insureds['.$insureds_key.'].birthday'] = $this->get_birthday($insured_val);;
                                $params_temp['insureds['.$insureds_key.'].gender'] = substr($insured_val, 16,1)%2==0?2:1;
                                $params_temp['insureds['.$insureds_key.'].relationshipToPolicyholder'] = $applicant_cardNo == $insured_val ? '1' : '14';
                            }
                            $params_temp['insureds['.$insureds_key.'].'.$insured_key] = $insured_val;
                        }
                    }
                }
            }
        }

//        echo '<pre>';
//        print_r($params_temp);die;

        $url = $this->getUrl(self::API_URL.'/order/add', $params_temp);
        $return = $this->send($url, TRUE);
        if (empty($return)) {
            return false;
        }
        return json_decode($return);
    }

    /**
     * 获取价格
     * @param array $params 二维数组
     *  array(
     *            array(
     *                'gender' => 1, // 性别 1男 2女
     *                'period_start' => '2015-10-15 00', 保险开始时间
     *                'period_end' => '2015-10-15 24', 保险结束时间
     *                'birthday' => '1988-08-08 ' // 生日
     *            ),
     *            array(
     *                'gender' => 2,
     *                'period_start' => '2015-11-15 00',
     *                'period_end' => '2015-11-15 24',
     *                'birthday' => '1987-08-08 '
     *            )
     *        );
     * @return array
     */
    public function plan_trial($params=array())
    {
        if (empty($params)) {
            return false;
        }

        $total_marketPrice = 0;
        $total_discountPrice = 0;

        foreach($params as $key=>$arr) {
            $params_temp = array(
                // 保险计划id
                'planId' => self::PLAN_ID,
                'gender' => $arr['gender'],
                // period 注意此拼接方式
                'period' => $arr['period_start'].'&=period'.$arr['period_end'],
                'birthday' => $arr['birthday']
            );
            $url = $this->getUrl(self::API_URL.'/product/plan/trial', $params_temp);
            $price_obj = json_decode($this->send($url));

            // 保险单价价格信息
            $params[$key]['prices'] = array(
                // 保险计划 ID
                'planId' => $price_obj->planId,
                // 市场价
                'marketPrice' => $price_obj->marketPrice,
                // 优惠价
                'discountPrice' => $price_obj->discountPrice
            );
            // 统计市场价的总价
            $total_marketPrice += $price_obj->marketPrice;
            // 统计优惠价的总价
            $total_discountPrice += $price_obj->discountPrice;
        }

        return array(
            'insureds' => $params,
            'total_marketPrice' => $total_marketPrice,
            'total_discountPrice' => $total_discountPrice
        );
    }

    /**
     * 获取保险计划详情（华泰境内旅行险标准款）
     * @return bool|mixed
     */
    public function plan_detail()
    {
        $url = $this->getUrl(self::API_URL.'/product/plan/get', array('planId'=>self::PLAN_ID));
        $return = $this->send($url);
        if (empty($return)) {
            return false;
        }
        return json_decode($return);
    }

    /**
     * 获取省市
     * @return array|mixed
     */
    public function area()
    {
        $url = $this->getUrl(self::API_URL.'/util/areas/get', array('type'=>'PROVINCE_LIST'));
        $result = json_decode($this->send($url));
        if (empty($result)) {
            return array();
        }
        return $result;
    }

    /**
     * 获取完整的url
     * @param string $url 链接
     * @param array $params 链接参数
     * @return string
     *
     * @example ('http://58.240.26.203:8090/api/product/plan/get', array('planId'=>1))
     */
    public function getUrl($url, $params=array())
    {
        if (empty($url)) {
            return '';
        }
        // 签名 （这一步签名必须进行）
        $sig = $this->sign($params);
        // 以下为url必带的固定参数
        $must_params = array(
            'uid' => self::UID,
            'sid' => self::SID,
            'ts'  => $this->ts,
            'sig' => $sig,
            'v'   => self::VERSION
        );
        $query_arr = array_merge($params, $must_params);

        $url = trim($url, '?').'?';
        foreach ($query_arr as $key=>$val) {
            if ($key == 'period') {
                // 2015-10-16 00&period=2015-10-16 24
                $arr_one = explode('&', $val);
                $arr_two = explode('=', $arr_one[1]);
                $url .= $key.'='.urlencode($arr_one[0]).'&period='.urlencode($arr_two[1]).'&';
            } else {
                $url .= $key.'='.urlencode($val).'&';
            }
        }
        return trim($url, '&');
    }

    /**
     * 支付成功后，获取订单相关信息
     * @param int $orderId 订单id
     * @return array
     */
    private function pay_callback($orderId)
    {
        $orderInfo = $this->order_detail($orderId);
        if (! $orderInfo) {
            return array();
        }
        return array(
            // 订单id
            'orderId'=> $orderInfo->order->orderId,
            // 实际支付总价格
            'payRealAmt' => $orderInfo->order->payRealAmt,
        );
    }

    /**
     *
     * 获取签名信息
     * @param array $params 签名所需的参数
     * array(
     *   'loginId' => 0, // 可选
     *   'orderId' => 0, // 可选
     *   'policyId'  => 0, // 可选
     *   'applicationId' => 0, // 可选
     *   );
     *
     * 签名参数 key与value的具体值参见对应api的文档
     * 注：以下key的名不能随意改动，详细参见文档
     * array(
     *  'loginId' => 0, // 可选
     *  'orderId' => 0, // 可选
     *  'policyId' => 0, // 可选
     *  'applicationId' => 0, // 可选
     *  'uid' => '', // 必填
     *  'sid' => '', // 必填
     *  'ts' => '', // 必填
     *  'appsecret' => strtoupper(md5('')) // 必填
     * );
     *
     * @return string
     */
    private function sign($params=array())
    {
        // 注意：签名串的顺序必须为
        // uid,sid,ts,loginId,orderId,policyId,applicationId,strtoupper(md5(appsecret))
        // 以下为签名所需的固定参数
        $this->ts = date('Y-m-d H:m:s', time());
        $must_params = array(
            'uid' => self::UID,
            'sid' => self::SID,
            'ts'  => $this->ts,
        );
        // 以下为签名所需的可选参数
        $optional_params = array(
            'loginId' => 0, // 可选
            'orderId' => 0, // 可选
            'policyId' => 0, // 可选
            'applicationId' => 0, // 可选
        );
        $appsecret = array(
            'appsecret' => strtoupper(md5(self::APP_SECRET)),
        );
        // 给可选参数赋值
        foreach ($params as $key=>$val) {
            if (array_key_exists($key, $optional_params)) {
                $optional_params[$key] = $val;
            }
        }

        // 合并参数
        $sign_arr = array_merge($must_params, $optional_params, $appsecret);
        foreach ($sign_arr as $key=>$val) {
            if (empty($val)) {
                unset($sign_arr[$key]);
            }
        }

        // 进行MD5签名
        return strtoupper(md5(implode(',', $sign_arr)));
    }

    /**
     * curl 请求
     * @param $url
     * @param bool|FALSE $is_post
     * @return bool|mixed
     */
    private function send($url, $is_post=FALSE)
    {
        if (empty($url)) {
            return false;
        }
        $data = explode('?', $url);
        if (!isset($data[0]) && empty($data[0])) {
           return false;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $data[0]);

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2000);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);


        curl_setopt($ch, CURLOPT_POST, $is_post);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data[1]);

        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    /**
     * 获取生日 格式为 1990-01-01
     * @param string $cardNo 身份证号码
     * @return string
     */
    private function get_birthday($cardNo)
    {
        // 获取到生日,如: 19900101
        $bir_str = substr($cardNo, 6, 8);
        // 转换成 1990-01-01
        $year  = substr($bir_str, 0, 4);
        $month = substr($bir_str, 4, 2);
        $day   = substr($bir_str, -1, 2);
        return $year . '-' . $month . '-' . $day;
    }
}