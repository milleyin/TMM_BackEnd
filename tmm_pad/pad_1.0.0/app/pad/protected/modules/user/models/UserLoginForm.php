<?php
/**
 * 用户登录表单模型
 * UserLoginForm class.
 * UserLoginForm is the data structure for keeping
 *
 * @author Moore Mo
 */
class UserLoginForm extends CFormModel
{
	/**
	 * 微信id
	 * @var String
	 */
	public $openid;
	/**
	 * 微信id
	 * @var String
	 */
	public $name;
	/**
	 * 验证信息
	 * @var
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
			array('openid', 'length', 'max'=>128),
			array('name', 'length', 'max'=>32),
			// 验证登录
			array('openid, name', 'required' ,'on'=>'login'),
			array('openid, name', 'safe' ,'on'=>'login'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'openid'=>'微信ID',
			'name'=>'昵称',
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
			$this->_identity = new UserUserIdentity($this->openid, '');
			if ( !$this->_identity->authenticate())
				$this->addError('openid', '微信授权失败');
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
			$this->_identity = new UserUserIdentity($this->openid, '');
			$this->_identity->authenticate();
		}
		if ($this->_identity->errorCode === UserUserIdentity::ERROR_NONE)
			return Yii::app()->user->login($this->_identity);
		else
			return false;
	}
}
