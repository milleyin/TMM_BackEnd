<?php
/**
 * 代理商登录模型
 * @author moore
 */
class AgentLoginForm extends  CFormModel
{
	const phone = 'phone';
    public $phone;
    public $password;
    public $verifyCode;

    private $_identity;

	public function rules() 
	{
		return array(
        	array('password', 'length', 'max'=>28),
        	array('verifyCode', 'length', 'max'=>4, 'min'=>4),
        	array('phone', 'length', 'max'=>11, 'min'=>11),
        	
			array('phone, password, verifyCode', 'required', 'on'=>'login'),
			array('phone', 'match', 'pattern'=>Yii::app()->params['pattern']['phone'], 'on'=>'login'),
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements(), 'skipOnError'=>true, 'on'=>'login'),
			array('password', 'authenticate', 'on'=>'login'),
					
			array('phone, verifyCode', 'required', 'on'=>'apply'),
			array('phone', 'match', 'pattern'=>Yii::app()->params['pattern']['phone'], 'on'=>'apply'),
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements(), 'skipOnError'=>true, 'on'=>'apply'),
			array('phone', 'isAgent', 'on'=>'apply'),
		);
	}

    public function attributeLabels() 
    {
        return array(
            'phone' => '手机号',
            'password' => '密码',
            'verifyCode' => '验证码',
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute)
    {
        if (!$this->hasErrors())
        {    	
        	if (preg_match(Yii::app()->params['pattern']['phone'], $this->phone)) 
        	{
				$this->_identity = new AgentUserIdentity($this->phone, $this->password);
				if ($this->_identity->authenticate()) 											//验证手机 密码
				{							
					if ($this->_identity->agent->login_error_confine($this))		//验证 错误次数
						$this->_identity->agent->login_status($this);					//验证 状态
				}
				else
				{
					if (isset($this->_identity->agent))											//代理商存在 错误++
						$this->_identity->agent->login_error();							//记录错误登录次数
					$this->addError('password', '用户名或密码错误');
				}
			}else
				$this->addError('phone', '手机号 不是有效的');
        }
    }

    /**
     * 登录
     * @return boolean
     */
    public function login() 
    {
        if($this->_identity===null)
        {
            $this->_identity = new AgentUserIdentity($this->phone, $this->password);
            $this->_identity->authenticate();
        }
        if($this->_identity->errorCode === AgentUserIdentity::ERROR_NONE)
        {
    		Yii::app()->operator->setFlash(LoginLog::login_type_name,LoginLog::login_type_password);
			return Yii::app()->operator->login($this->_identity,Yii::app()->operator->allowAutoLogin ? 3600*24*30 : 0);
        }
        return false;
    }
    
    /**
     * 验证是否
     */
    public function isAgent()
    {
    	if (!$this->hasErrors())
    	{
    		 if ( !Agent::model()->find('phone=:phone AND status=:status', array(':phone'=>$this->phone, ':status'=>Agent::status_suc)))
    		 	$this->addError('phone', '手机号 不是有效的');
    	}
    }
    
    /**
     * 验证成功发送验证码
     */
    public function sendSms()
    {
    	Yii::import('ext.Send_sms.Send_sms');
    	$model = Agent::model()->find('`phone`=:phone',array(':phone'=>$this->phone));
    	if ($model)
    	{
    		$params_v = array(
    				'sms_id'=>$model->id,
    				'sms_type'=>SmsLog::sms_agent,
    				'role_id'=>$model->id,
    				'role_type'=>SmsLog::send_agent,
    				'sms_use'=>SmsLog::use_password,
    		);
    		if (Send_sms::is_send($this->phone,$params_v, Yii::app()->params['sms']['agent_update_pwd_phone']['number'], Yii::app()->params['sms']['agent_update_pwd_phone']['interval']))
    		{
    			$code = rand(100000,999999);
    			$params = array(
    					'sms_id'=>$model->id,
    					'sms_type'=>SmsLog::sms_agent,
    					'role_id'=>$model->id,
    					'role_type'=>SmsLog::send_agent,
    					'sms_use'=>SmsLog::use_password,
    					'code'=>$code,
    					'sms_source'=>SmsLog::source_pc,
    					'login_address'=>'',
    					'sms_error'=>Yii::app()->params['sms']['agent_update_pwd_phone']['error'],
    					'end_time'=>time()+Yii::app()->params['sms']['agent_update_pwd_phone']['time'],
    			);
    			if (Send_sms::send($this->phone, $params, strtr(Yii::app()->params['sms']['agent_update_pwd_phone']['content'], array('{code}'=>$code))))
    			{	
    				Yii::app()->session[self::phone] = $this->phone;
    				return true;
    			}
    			else 
    				$this->addError('phone', '操作过于频繁，请稍后操作！');
    		}
    		else 
    			$this->addError('phone', '操作过于频繁，请稍后操作！');
    	}
    	return false;
    }
    
    /**
     * 是否通过验证
     */
    public static function isVerify()
    {
    	return Yii::app()->session[self::phone];
    }
    
    /**
     * 删除验证
     * @return boolean
     */
    public static function unsetVerify()
    {
    	unset(Yii::app()->session[self::phone]);
    	return true;
    }

}