<?php
/**
 * 代理商登录模型
 * @author moore
 */
class AgentSmsLoginForm extends  CFormModel
{
    public $phone;
    public $sms;
    public $verifyCode;

    private $_identity;
        
    public function rules() 
    {
        return array(
        	array('verifyCode', 'length', 'max'=>4, 'min'=>4),
        	array('phone', 'length', 'max'=>11, 'min'=>11),
        	array('sms', 'length', 'max'=>6, 'min'=>6),
            array('phone,sms, verifyCode', 'required'),
        	array('phone', 'match', 'pattern'=>Yii::app()->params['pattern']['phone']),
            array('sms', 'authenticate')
        );
    }

    public function attributeLabels() {
        return array(
            'phone' => '手机号',
            'sms' => '短信验证码',
            'verifyCode' => '验证码'
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
        	if (preg_match(Yii::app()->params['pattern']['phone'],$this->phone))
            {
            	//导入短信类
            	Yii::import('ext.Send_sms.Send_sms');
            	$params = array(
	                'sms_id' => $this->_identity->agent->id,
	                'sms_type' => SmsLog::sms_agent,
	                'role_id' => $this->_identity->agent->id,
	                'role_type' => SmsLog::send_agent,
	                'sms_use' => SmsLog::use_login,
            	);
            	if (Send_sms::verifycode($this->phone, $params, $this->sms))
            	{
            		//验证
            		$this->_identity = new AgentSmsUserIdentity($this->phone, $this->sms); 		
            		if ($this->_identity->authenticate()) 											//验证手机 密码
            			$this->_identity->agent->login_status($this, true);				//验证 状态
            		else
            		{
            			if (isset($this->_identity->agent))											//用户存在 错误++
            				$this->_identity->agent->login_error();							//记录错误登录次数
            			$this->addError('phone', '手机号或验证码错误');
            		}
            	}else
            		$this->addError('phone', '手机号或验证码错误');
            
            }else
            	$this->addError('phone', '手机号不是有效的');
        }
    }

    /**
     * 登录
     * @return boolean
     */
    public function login()
    {
        if($this->_identity === null)
        {
            $this->_identity=new AgentSmsUserIdentity($this->phone,$this->sms);
            $this->_identity->authenticate();
        }
        if($this->_identity->errorCode === AgentSmsUserIdentity::ERROR_NONE)
        {      	
        	Yii::app()->operator->setFlash(LoginLog::login_type_name, LoginLog::login_type_sms);
			return Yii::app()->operator->login($this->_identity,Yii::app()->operator->allowAutoLogin ? 3600*24*30 : 0);
        }        
        return false;
    }

}