<?php
/**
 * 商家登录模型
 * @author Moore Mo
 *
 */
class StoreLoginForm extends CFormModel
{
	public $phone;
	public $password;
	public $verifyCode;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			//密码登录
			array('phone, password, verifyCode', 'required'),
			array('phone','match','pattern'=>Yii::app()->params['pattern']['phone']),
			array('verifyCode','captcha','allowEmpty'=>!CCaptcha::checkRequirements(),'skipOnError'=>true),
			array('password', 'authenticate')

		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'phone'=>'手机号',
			'password'=>'密码',
			'verifyCode'=>'验证码',
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
			if(preg_match(Yii::app()->params['pattern']['phone'],$this->phone))
			{
				if(empty($this->password))
				{
					$this->addError('password', '手机号或密码错误');
					return false;
				}
				$this->_identity = new StoreUserIdentity($this->phone, $this->password);
				if ($this->_identity->authenticate()) //验证手机 密码
				{
					if ($this->_identity->store->login_error_confine($this))		//验证 错误次数
						$this->_identity->store->login_status($this);				//验证 状态
				} else {
					if (isset($this->_identity->store))							//用户存在 错误++
						$this->_identity->store->login_error();//记录错误登录次数
					$this->addError('password', '手机号或密码错误');
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
			$this->_identity=new StoreUserIdentity($this->phone,$this->password);
                        
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===StoreUserIdentity::ERROR_NONE)
		{
			Yii::app()->store->setFlash(LoginLog::login_type_name,LoginLog::login_type_password);		 
			return Yii::app()->store->login($this->_identity,Yii::app()->store->allowAutoLogin?3600*24*30:0);
		}
		
		return false;            
	}

	/**
	 * 返回商家数据
	 * @return mixed
	 */
	public function getStore(){
		if(isset($this->_identity->store))
			return $this->_identity->store;

		return NULL;
	}
	

}
