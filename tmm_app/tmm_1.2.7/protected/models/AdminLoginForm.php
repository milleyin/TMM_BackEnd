<?php
/**
 * 后台登录模型
 * @author Changhai Zhan
 *
 */
class AdminLoginForm extends CFormModel
{
	/**
	 * 用户名
	 * @var unknown
	 */
	public $username;
	/**
	 * 密码
	 * @var unknown
	 */
	public $password;
	/**
	 * 验证码
	 * @var unknown
	 */
	public $verifyCode;
	/**
	 * 验证
	 * @var unknown
	 */
	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('password', 'length', 'max'=>28),
			array('verifyCode', 'length', 'max'=>4, 'min'=>4),
			array('username', 'length', 'max'=>40),
			
			array('username, password, verifyCode', 'required'),
			array('verifyCode','captcha','allowEmpty'=>!CCaptcha::checkRequirements(),'skipOnError'=>true),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'username'=>'用户名',
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
			$this->_identity=new AdminUserIdentity($this->username,$this->password);
			if(!$this->_identity->authenticate())
			{
				$this->addError('password','用户名或密码错误');
				if(isset($this->_identity->admin))
					$this->_identity->admin->login_error();			//记录错误登录次数
			}else
				$this->_identity->admin->login_status($this);		//登录状态
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
			$this->_identity=new AdminUserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===AdminUserIdentity::ERROR_NONE)
		{
			Yii::app()->admin->setFlash(LoginLog::login_type_name,LoginLog::login_type_password);
			return Yii::app()->admin->login($this->_identity);
		}
			
		return false;
	}
}
