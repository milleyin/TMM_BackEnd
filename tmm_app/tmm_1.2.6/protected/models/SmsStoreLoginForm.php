<?php
/**
 * 商家登录模型
 * @author Moore Mo
 */
class SmsStoreLoginForm extends  CFormModel
{
    public $phone;
    public $old_phone;
    public $verifyCode;

    public function rules() {
        return array(
        	array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'no,yes'),
            array('phone, verifyCode', 'required','on'=>'yes'),
            array('verifyCode','captcha','allowEmpty'=>!CCaptcha::checkRequirements(),'skipOnError'=>true,'on'=>'yes'),
            array('phone', 'authenticate','on'=>'yes'),
            array('phone, verifyCode','safe','on'=>'yes'),
   		
            array('phone','required','on'=>'no'),
            array('phone','safe','on'=>'no'),

            array('phone, old_phone', 'required','on'=>'change_phone'),
        	array('old_phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'change_phone'),
        	array('phone,old_phone','required','on'=>'change_phone'),
        	array('phone,old_phone','safe','on'=>'change_phone'),


            //修改密码
            array('phone','required','on'=>'pwd_phone'),
            array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'pwd_phone'),
            array('phone', 'authenticate_pwd','on'=>'pwd_phone'),
            array('phone','safe','on'=>'pwd_phone'),

            //改变旧手机
            array('phone','required','on'=>'old_phone'),
            array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'old_phone'),
            array('phone','safe','on'=>'old_phone'),
            array('phone', 'authenticate_old','on'=>'old_phone'),

            //改变新手机号
            array('phone','required','on'=>'new_phone'),
            array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'new_phone'),
            array('phone','safe','on'=>'new_phone'),
            array('phone', 'authenticate_new','on'=>'new_phone'),

            // 设置银行
            array('phone','required','on'=>'bank_phone'),
            array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'bank_phone'),
            array('phone', 'authenticate_bank','on'=>'bank_phone'),
            array('phone','safe','on'=>'bank_phone'),

            // 商家提现
            array('phone','required','on'=>'cash_phone'),
            array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'cash_phone'),
            array('phone', 'authenticate_cash','on'=>'cash_phone'),
            array('phone','safe','on'=>'cash_phone'),
        );
    }

    public function attributeLabels() {
        return array(
            'phone' => '手机号',
        	'old_phone' => '以前手机号',
            'verifyCode' => '验证码'
        );
    }

    /**
 * Authenticates the password.
 * This is the 'authenticate' validator as declared in rules().
 */
    public function authenticate($attribute,$params)
    {
        if(!$this->hasErrors())
        {
            if(! preg_match(Yii::app()->params['pattern']['phone'],$this->phone)){
                $this->addError('phone','手机号码 不是有效值');
            }
            else{
                if(! StoreUser::model()->find('`phone`=:phone AND `status`=1',array(':phone'=>$this->phone)))
                    $this->addError('phone','手机号 不是有效值');
            }
        }
    }
    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate_pwd($attribute,$params)
    {
        if(!$this->hasErrors())
        {
            if(! preg_match(Yii::app()->params['pattern']['phone'],$this->phone)){
                $this->addError('phone','手机号码 不是有效值');
            }
            else{
                if(! StoreUser::model()->find('`phone`=:phone AND `status`=1',array(':phone'=>$this->phone)))
                    $this->addError('phone','手机号 不是有效值');
            }
        }
    }

    /**
     * 设置银行-短信验证
     * @param $attribute
     * @param $params
     */
    public function authenticate_bank($attribute,$params)
    {
        if(!$this->hasErrors())
        {
            if(! preg_match(Yii::app()->params['pattern']['phone'],$this->phone)){
                $this->addError('phone','手机号码 不是有效值');
            }
            else{
                if(! StoreUser::model()->find('`phone`=:phone AND `status`=1',array(':phone'=>$this->phone)))
                    $this->addError('phone','手机号 不是有效值');
            }
        }
    }

    /**
     * 商家提现-短信验证
     * @param $attribute
     * @param $params
     */
    public function authenticate_cash($attribute,$params)
    {
        if(!$this->hasErrors())
        {
            if(! preg_match(Yii::app()->params['pattern']['phone'],$this->phone)){
                $this->addError('phone','手机号码 不是有效值');
            }
            else{
                if(! StoreUser::model()->find('`phone`=:phone AND `status`=1',array(':phone'=>$this->phone)))
                    $this->addError('phone','手机号 不是有效值');
            }
        }
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate_old($attribute,$params)
    {
        if(!$this->hasErrors())
        {
            if(! preg_match(Yii::app()->params['pattern']['phone'],$this->phone)){
                $this->addError('phone','手机号码 不是有效值');
            }
            else{
                if(! StoreUser::model()->find('`phone`=:phone AND `status`=1 AND id=:id',array(':phone'=>$this->phone,':id'=>Yii::app()->store->id)))
                    $this->addError('phone','手机号码 不是有效值');
            }
        }
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate_new($attribute,$params)
    {
        if(!$this->hasErrors())
        {
            if(! preg_match(Yii::app()->params['pattern']['phone'],$this->phone)){
                $this->addError('phone','手机号码 不是有效值');
            }
            else{
                if(StoreUser::model()->find('phone=:phone',array(':phone'=>$this->phone)))
                    $this->addError('phone','手机号码 不是有效值');
            }
        }
    }

    /**
     * 获取错误信息
     * @return array
     */
    public function get_error()
    {
        $result=array();
        foreach($this->getErrors() as $attribute=>$errors)
            $result[$attribute]=$errors;
        if(empty($result))
        	$result['phone'][]='操作过于频繁,请1分钟后再试';
        return $result;
    }
    

    public function init()
    {
    	parent::init();
    	Yii::import('ext.Send_sms.Send_sms');
    }

    /**
     * 商家 登录 短信
     * @return bool
     */
    public function store_login_send()
    {
        //当前手机号是否存在
        $model=StoreUser::model()->find('`phone`=:phone',array(':phone'=>$this->phone));
        if($model){
            if($model->status==0 ) {
                $this->addError('phone', '该帐号被禁用');
                return false;
            }
            elseif( $model->status == -1){
                $this->addError('phone', '该手机不能注册');
                return false;
            }
        }
        //存在  发送手机ID为自己　　不存在　　为　０　
        $sms_id = $model?$model->id : 0;

            $params_v=array(
                'sms_id'=>$sms_id,
                'sms_type'=>SmsLog::sms_store,
                'role_id'=>$sms_id,
                'role_type'=>SmsLog::send_store,
                'sms_use'=>$sms_id==0? SmsLog::use_register: SmsLog::use_login,
            );

            if(Send_sms::is_send($this->phone,$params_v,Yii::app()->params['sms']['store_login']['number'],Yii::app()->params['sms']['store_login']['interval']))
            {
                $code=rand(100000,999999);
                $params=array(
                    'sms_id'=>$sms_id,
                    'sms_type'=>SmsLog::sms_store,
                    'role_id'=>$sms_id,
                    'role_type'=>SmsLog::send_store,
                    'sms_use'=>$sms_id==0? SmsLog::use_register: SmsLog::use_login,
                    'code'=>$code,
                    'sms_source'=>SmsLog::source_app,
                    'login_address'=>'',
                    'sms_error'=>Yii::app()->params['sms']['store_login']['error'],
                    'end_time'=>time()+Yii::app()->params['sms']['store_login']['time'],
                );
                return Send_sms::send($this->phone,$params,
                		strtr(Yii::app()->params['sms']['store_login']['content'],array(
                			'{code}'=>$code
               			 ))
                	);
            }
    }

    /**
     * 用户更改自己手机的短信========旧手机
     * @return bool
     */
    public function  store_update_old_phone_sms()
    {
        $model=StoreUser::model()->find('`phone`=:phone AND `status`=1 AND `id`=:id',array(':phone'=>$this->phone,':id'=>Yii::app()->store->id));
        if($model)
        {
            $params_v=array(
                'sms_id'=>Yii::app()->store->id,
                'sms_type'=>SmsLog::sms_store,
                'role_id'=>Yii::app()->store->id,
                'role_type'=>SmsLog::send_store,
                'sms_use'=>SmsLog::use_phone,
            );
            if(Send_sms::is_send($this->phone,$params_v,Yii::app()->params['sms']['store_update_old_phone']['number'],Yii::app()->params['sms']['store_update_old_phone']['interval']))
            {
                $code=rand(100000,999999);
                $params=array(
                    'sms_id'=>Yii::app()->store->id,
                    'sms_type'=>SmsLog::sms_store,
                    'role_id'=>Yii::app()->store->id,
                    'role_type'=>SmsLog::send_store,
                    'sms_use'=>SmsLog::use_phone,
                    'code'=>$code,
                    'sms_source'=>SmsLog::source_app,
                    'login_address'=>'',
                    'sms_error'=>Yii::app()->params['sms']['store_update_old_phone']['error'],
                    'end_time'=>time()+Yii::app()->params['sms']['store_update_old_phone']['time'],
                );
                return Send_sms::send($this->phone,$params,
                    strtr(Yii::app()->params['sms']['store_update_old_phone']['content'],array(
                        '{code}'=>$code
                    ))
                );
            }
        }
        return false;
    }

    /**
     * 用户更改自己手机的短信========新手机
     * @return bool
     */
    public function  store_update_new_phone_sms(){
        $model = StoreUser::model()->find('`phone`=:phone',array(':phone'=>$this->phone));

        if(!$model)
        {
            $params_v=array(
                'sms_id'=>Yii::app()->store->id,
                'sms_type'=>SmsLog::sms_store,
                'role_id'=>Yii::app()->store->id,
                'role_type'=>SmsLog::send_store,
                'sms_use'=>SmsLog::use_phone,
            );
            if(Send_sms::is_send($this->phone,$params_v,Yii::app()->params['sms']['store_update_new_phone']['number'],Yii::app()->params['sms']['store_update_new_phone']['interval']))
            {
                $code=rand(100000,999999);
                $params=array(
                    'sms_id'=>Yii::app()->store->id,
                    'sms_type'=>SmsLog::sms_store,
                    'role_id'=>Yii::app()->store->id,
                    'role_type'=>SmsLog::send_store,
                    'sms_use'=>SmsLog::use_phone,
                    'code'=>$code,
                    'sms_source'=>SmsLog::source_app,
                    'login_address'=>'',
                    'sms_error'=>Yii::app()->params['sms']['user_update_new_phone']['error'],
                    'end_time'=>time()+Yii::app()->params['sms']['user_update_new_phone']['time'],
                );
                return Send_sms::send($this->phone,$params,
                    strtr(Yii::app()->params['sms']['user_update_new_phone']['content'],array(
                        '{code}'=>$code
                    ))
                );
            }
        }
        return false;

    }

    /**
     * 商家更改自己密码的短信========密码
     * @return bool
     */
    public function  store_update_pwd_phone_sms()
    {
        $model = StoreUser::model()->find('`phone`=:phone AND `id`=:id AND `status`=1',array(':phone'=>$this->phone,':id'=>Yii::app()->store->id));

        if($model)
        {
            $params_v=array(
                'sms_id'=>Yii::app()->store->id,
                'sms_type'=>SmsLog::sms_store,
                'role_id'=>Yii::app()->store->id,
                'role_type'=>SmsLog::send_store,
                'sms_use'=>SmsLog::use_password,
            );
            if(Send_sms::is_send($this->phone,$params_v,Yii::app()->params['sms']['store_update_pwd_phone']['number'],Yii::app()->params['sms']['store_update_pwd_phone']['interval']))
            {
                $code=rand(100000,999999);
                $params=array(
                    'sms_id'=>Yii::app()->store->id,
                    'sms_type'=>SmsLog::sms_store,
                    'role_id'=>Yii::app()->store->id,
                    'role_type'=>SmsLog::send_store,
                    'sms_use'=>SmsLog::use_password,
                    'code'=>$code,
                    'sms_source'=>SmsLog::source_app,
                    'login_address'=>'',
                    'sms_error'=>Yii::app()->params['sms']['store_update_pwd_phone']['error'],
                    'end_time'=>time()+Yii::app()->params['sms']['store_update_pwd_phone']['time'],
                );
                return Send_sms::send($this->phone,$params,
                    strtr(Yii::app()->params['sms']['store_update_pwd_phone']['content'],array(
                        '{code}'=>$code
                    ))
                );
            }
        }
        return false;
    }

    /**
     * 商家设置银行的短信
     * @return bool
     */
    public function  store_update_bank_phone_sms()
    {
        $model = StoreUser::model()->find('`phone`=:phone AND `id`=:id AND `status`=1',array(':phone'=>$this->phone,':id'=>Yii::app()->store->id));

        if($model)
        {
            $params_v=array(
                'sms_id'=>Yii::app()->store->id,
                'sms_type'=>SmsLog::sms_store,
                'role_id'=>Yii::app()->store->id,
                'role_type'=>SmsLog::send_store,
                'sms_use'=>SmsLog::use_bank,
            );
            if(Send_sms::is_send($this->phone,$params_v,Yii::app()->params['sms']['store_update_bank_phone']['number'],Yii::app()->params['sms']['store_update_bank_phone']['interval']))
            {
                $code=rand(100000,999999);
                $params=array(
                    'sms_id'=>Yii::app()->store->id,
                    'sms_type'=>SmsLog::sms_store,
                    'role_id'=>Yii::app()->store->id,
                    'role_type'=>SmsLog::send_store,
                    'sms_use'=>SmsLog::use_bank,
                    'code'=>$code,
                    'sms_source'=>SmsLog::source_app,
                    'login_address'=>'',
                    'sms_error'=>Yii::app()->params['sms']['store_update_bank_phone']['error'],
                    'end_time'=>time()+Yii::app()->params['sms']['store_update_bank_phone']['time'],
                );
                return Send_sms::send($this->phone,$params,
                    strtr(Yii::app()->params['sms']['store_update_bank_phone']['content'],array(
                        '{code}'=>$code
                    ))
                );
            }
        }
        return false;
    }

    /**
     * 商家提现的短信
     * @param $id 商家id或父id
     * @return bool
     */
    public function store_cash_phone_sms($id)
    {
        $model = StoreUser::model()->find('`phone`=:phone AND `id`=:id AND `status`=1',array(':phone'=>$this->phone,':id'=>$id));

        if($model)
        {
            $params_v=array(
                'sms_id'=>$id,
                'sms_type'=>SmsLog::sms_store,
                'role_id'=>$id,
                'role_type'=>SmsLog::send_store,
                'sms_use'=>SmsLog::use_cash,
            );
            if(Send_sms::is_send($this->phone,$params_v,Yii::app()->params['sms']['store_cash_phone']['number'],Yii::app()->params['sms']['store_cash_phone']['interval']))
            {
                $code=rand(100000,999999);
                $params=array(
                    'sms_id'=>$id,
                    'sms_type'=>SmsLog::sms_store,
                    'role_id'=>$id,
                    'role_type'=>SmsLog::send_store,
                    'sms_use'=>SmsLog::use_cash,
                    'code'=>$code,
                    'sms_source'=>SmsLog::source_app,
                    'login_address'=>'',
                    'sms_error'=>Yii::app()->params['sms']['store_cash_phone']['error'],
                    'end_time'=>time()+Yii::app()->params['sms']['store_cash_phone']['time'],
                );
                return Send_sms::send($this->phone,$params,
                    strtr(Yii::app()->params['sms']['store_cash_phone']['content'],array(
                        '{code}'=>$code
                    ))
                );
            }
        }
        return false;
    }

}