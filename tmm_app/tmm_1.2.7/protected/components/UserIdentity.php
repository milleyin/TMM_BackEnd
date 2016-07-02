<?php
/**
 * 用户登录身份验证
 * @author Changhai Zhan
 *
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * 用户唯一id
	 * @var unknown
	 */
	private $_id;
	/**
	 * 登录用户数据模型
	 * @var unknown
	 */
	private $_user;
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$this->_user=User::model()->find('phone=:phone',array(':phone'=>$this->username));
		if(!isset($this->_user->phone) || $this->_user->phone != $this->username)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif(!$this->_user->validatePassword($this->password,$this->_user->password))
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else{
			$this->_id=$this->_user->id;
			$this->errorCode=self::ERROR_NONE;
		}
		return $this->errorCode==self::ERROR_NONE;
	}

	/**
	 * 返回登录唯一标示id
	 * @see CUserIdentity::getId()
	 */
	public function getId()
	{
		return $this->_id;
	}
	
	/**
	 *持久保存数据
	 * @see CBaseUserIdentity::getPersistentStates()
	 */
	public function getPersistentStates()
	{
		return array();
	}

	/**
	 * 返回登录人的数据
	 * @return Ambigous <static, mixed, NULL, multitype:static , multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
	 */
	public function getUser()
	{
		return $this->_user;
	}
}