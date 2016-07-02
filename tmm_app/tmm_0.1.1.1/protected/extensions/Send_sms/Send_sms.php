<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/1
 * Time: 13:35
 */	/**
 * 短信验证码
 */
class Send_sms
{
    //秘钥
    const sms_key = 'f1a763ef51b3828d7acadda7d5353a69';
    //地址
    const sms_url = 'http://sms-api.luosimao.com/v1/send.json';

    //参数不全
    const sms_params = false;
    //成功
    const sms_success = 1;
    //失败
    const sms_falst   = -1;
    //欠费
    const sms_fee     = 3;


    /**
     *其他
     * @var integer
     */
    const other=0;
    /**
     * 管理员
     * @var integer
     */
    const admin=1;
    /**
     * 代理商
     * @var integer
     */
    const agent=2;
    /**
     * 商家
     * @var integer
     */
    const store=3;
    /**
     *  用户
     * @var integer
     */
    const user=4;



    /**
     * $mobile string 手机号
     * @param string $mobile
     * @param $content 手机内容
     * @param array $gathers 集合参数 array('sms_id','sms_type','role_id','role_type','sms_use','code','sms_source','login_address','sms_error','end_time')
     * @return bool
     * sms_id  发给谁 角色id
     * sms_type 发给谁 角色类型0其他1=管理员2=商家3代理商4=用户
     * role_id 操作角色id
     * role_type 操作角色类型0其他1=管理员2=商家3代理商4=用户
     * sms_use 短信用途 1注册2登录3银行4手机5通知6密码
     * code 手机验证码
     * sms_source
     * login_address 登录地址
     * sms_error 最大错误次数
     * end_time 失效时间
     */
    public static function send($mobile,$params,$content)
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
        curl_setopt($ch, CURLOPT_URL,Yii::app()->params['sms']['sms_url']);

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'api:' . Yii::app()->params['sms']['sms_key']);

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
                $status=self::sms_falst;
            }else
                $status=self::sms_falst;
        }else
            $status=self::sms_falst;

      //Yii::log('','error',$res);
        //写短信发送日志  数据库
        $model=new SmsLog;
        foreach($data as $key=>$value)
            $model->$key=$value;
         $model->sms_ip=Yii::app()->request->userHostAddress;
         $model->sms_content=$content;
         $model->phone=$mobile;
         $model->status=$status;
         $model->save(false);
         return $status==self::sms_success;
    }

    /**
     * 欠费通知
     */
    public static function notify()
    {
        Yii::log('','error','短信数量没有了！');
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
        $criteria= new CDbCriteria;
        $data['phone']=$phone;
        $data['status']=self::sms_success;
        $criteria->addColumnCondition($data);
        $criteria->addCondition('`end_time` >=:time AND `error_count`<=`sms_error` AND is_code=:is_code');
        $criteria->params[':is_code']=SmsLog::is_code;
      	$criteria->params[':time']=time();
        $criteria->order=' add_time desc ';
        $model=SmsLog::model()->find($criteria);
        if($model)
        {
            if($model->code !== $code)
            {
                SmsLog::model()->updateByPk($model->id,array(
                   'error_count'=>new CDbExpression('`error_count`+1'),
                ));
            }else{
                return SmsLog::model()->updateByPk($model->id,array(
                    'is_code'=>0,
                ));
            }
        }
        return false;
    }


    /**
     * 验证码 是否有权限
     * @param $phone
     * @param $params
     * @param int $number   每天发送限制
     * @param int $interval 间隔时长
     */
    public static function is_send($phone,$params,$number=5,$interval=60)
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
        $criteria_count= new CDbCriteria;
        $criteria_count->addColumnCondition(array('phone'=>$phone));
        $criteria_count->addBetweenCondition('add_time',strtotime(date('Y-m-d')),strtotime(date('Y-m-d'))+3600*24-1);
        //验证限制 条数
        if($number !=0 && SmsLog::model()->count($criteria_count) >= $number)
            return false;
        else{
            // 验证间隔时间    0===不验证
            if($interval != 0 && is_numeric($interval) && $interval > 0){
                    $criteria= new CDbCriteria;
                    $data['phone']=$phone;
                    $data['status']=self::sms_success;
                    $criteria->addColumnCondition($data);
                    $criteria->addCondition('`add_time` >=:time AND `error_count`<=`sms_error` AND is_code=:is_code');
                    $criteria->params[':is_code']=SmsLog::is_code;
                    $criteria->params[':time']=time()-$interval;
                    $criteria->order=' add_time desc ';
                    if(SmsLog::model()->find($criteria))
                        return false;
            }
            //过期时长
            $criteria= new CDbCriteria;
            $data['phone']=$phone;
            $data['status']=self::sms_success;
            $criteria->addColumnCondition($data);
            $criteria->addCondition('`end_time` >=:time AND `error_count`<=`sms_error` AND is_code=:is_code');
            $criteria->params[':is_code']=SmsLog::is_code;
            $criteria->params[':time']=time();
            $criteria->order=' add_time desc ';
            if(SmsLog::model()->find($criteria))
                return false;
        }
        return true;
    }
}