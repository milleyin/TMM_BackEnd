<?php
/**
 * 管理员登录身份验证
 * @author Moore Mo
 *
 */
class StoreUserIdentity extends CUserIdentity
{
	private $_id;
	private $_store;
	
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
		$this->_store=StoreUser::model()->find('phone=:phone',array(':phone'=>$this->username));
		if(!isset($this->_store->phone) || $this->_store->phone != $this->username)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif(!$this->_store->validatePassword($this->password,$this->_store->password))
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else{
			$this->_id=$this->_store->id;
			$this->errorCode=self::ERROR_NONE;
		}
		return $this->errorCode==self::ERROR_NONE;
	}
	
	/**
	 * 返回登录唯一标示id
	 * @see CUserIdentity::getId()
	 */
	public function getId(){
		return $this->_id;
	}
	
	/**
	 * 返回登录人的数据
	 * @return Ambigous <static, mixed, NULL, multitype:static , multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
	 */
	public function getStore(){
		return $this->_store;
	}
	
}