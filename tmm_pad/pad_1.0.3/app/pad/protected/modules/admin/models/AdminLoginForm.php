<?php
/**
 * AdminLoginForm class.
 * AdminLoginForm is the data structure for keeping
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
	public $verifycode;
	/**
	 * 验证信息
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
			array('username, password', 'length', 'max'=>30),
			array('verifycode', 'length', 'max'=>4),
			//验证码登录
			array('username, password, verifycode', 'required' ,'on'=>'verifycode'),
			array('verifycode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements(), 'skipOnError'=>true, 'on'=>'verifycode'),
			array('username, password, verifycode', 'safe' ,'on'=>'verifycode'),
			array('password', 'authenticate' ,'on'=>'verifycode'),
			//没有验证登录
			array('username, password', 'required' ,'on'=>'password'),
			array('username, password', 'safe' ,'on'=>'password'),
			array('password', 'authenticate' ,'on'=>'password'),
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
			'verifycode'=>'验证码',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if (!$this->hasErrors())
		{
			$this->_identity = new AdminUserIdentity($this->username, $this->password);
			if ( !$this->_identity->authenticate())
				$this->addError('password', '用户名或密码错误');
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if ($this->_identity === null)
		{
			$this->_identity = new AdminUserIdentity($this->username, $this->password);
			$this->_identity->authenticate();
		}
		if ($this->_identity->errorCode === AdminUserIdentity::ERROR_NONE)
			return Yii::app()->user->login($this->_identity);
		else
			return false;
	}
}
