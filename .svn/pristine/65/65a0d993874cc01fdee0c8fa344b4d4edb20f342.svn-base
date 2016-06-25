<?php
namespace Common\Library\Util;
use Think\Model;
use Common\Model\SmsModel;

class SMS {
    // 秘钥
    const sms_key = 'f1a763ef51b3828d7acadda7d5353a69';
    // 地址
    const sms_url = 'http://sms-api.luosimao.com/v1/send.json';

    // 参数不全
    const sms_params = false;
    // 成功
    const sms_success = 1;
    // 失败
    const sms_fail   = -1;
    // 欠费
    const sms_fee     = 3;

    /**
     *其他
     * @var integer
     */
    const other = 0;
    /**
     *  用户
     * @var integer
     */
    const user = 1;

    /**
     * $mobile string 手机号
     * @param string $mobile
     * @param $content 手机内容
     * @param array $gathers 集合参数 array('sms_id','sms_type','role_id','role_type','sms_use','code','sms_source','login_address','sms_error','end_time')
     * @return bool
     * sms_id  发给谁 角色id
     * sms_type 发给谁 角色类型0其他1=用户
     * role_id 操作角色id
     * role_type 操作角色类型0其他1=用户
     * sms_use 短信用途 1购票
     * code 手机验证码
     * sms_source
     * login_address 登录地址
     * sms_error 最大错误次数
     * end_time 失效时间
     */
    public static function send($mobile, $params, $content)
    {
        $gathers=array('sms_id','sms_type','role_id','role_type','sms_use','code','sms_source','login_address','sms_error','end_time');
        $data= array();
        foreach($gathers as $value)
        {
            if(isset($params[$value]))
                $data[$value] = $params[$value];
            else
                return self::sms_params;
        }
        $text= array('mobile' => $mobile,'message'=>$content);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, C('SMS')['sms_url']);

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'api:' . C('SMS')['sms_key']);

        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$text);

        $res = curl_exec($ch);
        curl_close($ch);
        $val = json_decode($res);
        if(isset($val->error))
        {
            if ($val->error == 0)
                $status = self::sms_success; // 成功
            elseif ($val->error == -20) {
                self::notify();
                $status=self::sms_fail;
            }else
                $status=self::sms_fail;
        }else
            $status=self::sms_fail;

        // 写短信发送日志  数据库
        $data['sms_ip'] = get_client_ip();
        $data['sms_content'] =$content;
        $data['phone'] =$mobile;
        $data['status'] = 1;
		$data['add_time'] = time();
        if (! D('Sms')->add($data)) {
            return false;
        }
        return $status == self::sms_success;
    }

    /**
     * 欠费通知
     */
    public static function notify()
    {
        \Think\Log::write('短信数量没有了');
    }

    /**
     * 对比验证码 是否对
     * @param $phone
     * @param $params
     * @param $code
     * @return bool|int
     */
    public static function verifycode($phone,$params,$code)
    {
        $gathers=array('sms_id','sms_type','role_id','role_type','sms_use');
        $data=array();
        foreach($gathers as $value)
        {
            if(isset($params[$value]))
                $data[$value]=$params[$value];
            else
                return false;
        }
        $data['phone'] = $phone;
        $data['status'] = self::sms_success;
        $data['end_time'] = array('egt', time());
        $data['error_count'] = array('elt', 5);
        $data['is_code'] = SmsModel::is_code;
        $model = M('Sms')->where($data)->order('add_time desc')->find();
        if($model)
        {
            if($model['code'] !== $code)
            {
                M('Sms')->where(array('id'=>$model['id']))->setInc('error_count');
            }else{
                return M('Sms')->where(array('id'=>$model['id']))->setField(array('is_code'=>0));
            }
        }
        return false;
    }

    /**
     * 验证码 是否有权限
     * @param $phone
     * @param $params
     * @param int $number 每天发送限制
     * @param int $interval 间隔时长
     * @return bool
     */
    public static function is_send($phone,$params,$number=5,$interval=60)
    {

        $gathers = array('sms_id','sms_type','role_id','role_type','sms_use');
        $data = array();
        foreach($gathers as $value)
        {
            if(isset($params[$value]))
                $data[$value]=$params[$value];
            else
                return false;
        }
        $condition['phone'] = $phone;
        $condition['add_time'] = array('between', array(strtotime(date('Y-m-d')), strtotime(date('Y-m-d'))+3600*24-1));
        //验证限制 条数
        if($number !=0 && M('Sms')->where($condition)->count() >= $number) {
            return false;
        } else{
            // 验证间隔时间    0===不验证
            if($interval != 0 && is_numeric($interval) && $interval > 0){
                $data['phone'] = $phone;
                $data['status'] = self::sms_success;
                $data['add_time'] = array('egt', time() - $interval);
                $data['error_count'] = array('elt', 5);
                $data['is_code'] = SmsModel::is_code;
                if(M('Sms')->where($data)->order('add_time desc')->find())
                    return false;
            }
            // 过期时长
            $data['phone'] = $phone;
            $data['status'] = self::sms_success;
            $data['end_time'] = array('egt', time());
            $data['error_count'] = array('elt', 5);
            $data['is_code'] = SmsModel::is_code;
            if(M('Sms')->where($data)->order('add_time desc')->find())
                return false;
        }
        return true;
    }

    /**
     * 直接发送短信通知，不入库操作
     * @param $mobile 通知的手机
     * @param $content 通知的内容
     * @return bool
     */
    public static function notice($mobile, $content) {
        $text= array('mobile' => $mobile,'message'=>$content);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, C('SMS')['sms_url']);

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'api:' . C('SMS')['sms_key']);

        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$text);

        $res = curl_exec($ch);
        curl_close($ch);
        $val = json_decode($res);
        $return = false;
        if(isset($val->error))
        {
            if ($val->error == 0)
                //\Think\Log::write('给'. $mobile . '发送通知成功' . $res , 'INFO');
                $return = true;
            elseif ($val->error == -20) {
                \Think\Log::write('给'. $mobile . '发送通知失败，短信数量没有了', 'WARN');
            }else
                \Think\Log::write('给'. $mobile . '发送通知失败' . $res , 'WARN');
        }

        return $return;
    }
}