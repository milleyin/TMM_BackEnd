<?php
/**
 * 后台登录模型
 * @author Moore Mo
 *
 */
class StoreSmsLoginForm extends CFormModel
{
	public $phone;
	public $verifyCode;
	public $sms;
	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			//手机登录
			array('phone,sms, verifyCode', 'required'),
			array('phone','match','pattern'=>Yii::app()->params['pattern']['phone']),
			array('sms', 'authenticate')

		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
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
				$this->_identity = new StoreSmsUserIdentity($this->phone, $this->sms);
				if ($this->_identity->authenticate()) //验证手机 密码
				{
					//if ($this->_identity->store->login_error_confine($this))		//验证 错误次数
					$this->_identity->store->login_status($this);						//验证 状态
				}else{
					if (isset($this->_identity->store))										//代理商存在 错误++
						$this->_identity->store->login_error();						//记录错误登录次数
					$this->addError('sms', '手机号或短信验证码错误');
				}
			}else
				$this->addError('phone', '手机号 不是有效的');

		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new StoreSmsUserIdentity($this->phone,$this->sms);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===StoreSmsUserIdentity::ERROR_NONE)
		{			
			Yii::app()->store->setFlash(LoginLog::login_type_name,LoginLog::login_type_sms);
			return Yii::app()->store->login($this->_identity,Yii::app()->store->allowAutoLogin?3600*24*30:0);
		}
		else
			return false;
	}

	/**
	 * 返回商家数据
	 * @return mixed
	 */
	public function getStore()
	{
		if(isset($this->_identity->store))
			return $this->_identity->store;

		return NULL;
	}

}
