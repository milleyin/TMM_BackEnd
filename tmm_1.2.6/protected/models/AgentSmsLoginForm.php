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

    public static $num=0;
    public function rules() {
        return array(
            array('phone,sms, verifyCode', 'required'),
        	array('phone','match','pattern'=>Yii::app()->params['pattern']['phone']),
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
        if(!$this->hasErrors())
        {
            if(preg_match(Yii::app()->params['pattern']['phone'],$this->phone))
            {
                $this->_identity = new AgentSmsUserIdentity($this->phone, $this->sms);
                if ($this->_identity->authenticate()) //验证手机 密码
                {
                    //if ($this->_identity->agent->login_error_confine($this))		//验证 错误次数
                        $this->_identity->agent->login_status($this);						//验证 状态
                }else{
                    if (isset($this->_identity->agent))										//代理商存在 错误++
                        $this->_identity->agent->login_error();						//记录错误登录次数
                    $this->addError('sms', '用户名或短信验证码错误');
                }
            }else
                $this->addError('phone', '手机号 不是有效的');

        }
    }

    public function login()
    {
        if($this->_identity===null)
        {
            $this->_identity=new AgentSmsUserIdentity($this->phone,$this->sms);
            $this->_identity->authenticate();
        }
        if($this->_identity->errorCode===AgentSmsUserIdentity::ERROR_NONE)
        {      	
        	Yii::app()->agent->setFlash(LoginLog::login_type_name,LoginLog::login_type_sms);
        	 
        	Yii::app()->agent->setFlash(LoginLog::login_error_name, $this->_identity->agent->login_error);
        	Yii::app()->agent->setFlash(LoginLog::login_address_name,'');
        	
        	$this->_identity->agent->set_login(time());
        	
        	Yii::app()->agent->setState('phone', $this->_identity->agent->phone);
        	Yii::app()->agent->setState('name',$this->_identity->agent->firm_name);
        	Yii::app()->agent->setState('ip',Yii::app()->request->userHostAddress);
        	if(Yii::app()->agent->login($this->_identity))
        	{
        		//代理商登录日志记录
        		LoginLog::createLog(LoginLog::agent,Yii::app()->agent);
        		return true;
        	}
        }
        
        return false;
    }

}