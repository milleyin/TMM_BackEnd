<?php
/**
 * 后台登录
 * @author Changhai Zhan
 */
class AdminWebUser extends CWebUser
{
	/**
	 * 加载用户的信息
	 * @var unknown
	 */
	protected  $_model;
	/**
	 * 当前登录凭证密码
	 * @var unknown
	 */
	protected $_login_unique;
	
	/**
	 * 初始化
	 * @see CWebUser::init()
	 */
	public function init()
	{
		parent::init();
	}
	
	/**
	 * 获取用户信息
	 * @param unknown $id
	 * @return unknown|NULL
	 */
	protected function getUser($id)
	{
		if(! $this->_model)
			$this->_model = Admin::model()->findByPk($id,'`status`=:status',array(':status'=>Admin::status_suc));

		return isset($this->_model->id) && $this->_model->id == $id ? $this->_model : null;
	}
	
	/**
	 * 获取唯一登录的秘钥
	 * @param unknown $id
	 * @return string
	 */
	protected function getLoginUnique($id)
	{
		if(! $this->_login_unique)
			$this->_login_unique = md5($this->stateKeyPrefix.time().$id.mt_rand(10000000, 99999999));
			
		return $this->_login_unique;
	}
	
	/**
	 * 登录之前的操作
	 * @see CWebUser::beforeLogin()
	 */
	protected function beforeLogin($id,$states,$fromCookie)
	{ 
		if(!! $model = $this->getUser($id))
		{		
			$login_unique = $this->getLoginUnique($model->id);
			//缓存错误次数
			$this->setFlash(LoginLog::login_error_name, $model->login_error);
			if($model->set_login(time(),$login_unique))
			{			
				$this->setState(Admin::login_unique, $login_unique);
				Yii::app()->admin->setState('username', $model->username);
				Yii::app()->admin->setState('name',$model->name);
				Yii::app()->admin->setState('ip',Yii::app()->request->userHostAddress);
				
				$this->setFlash(LoginLog::login_address_name,'');
				return true;
			}
		}
		return false;
	}
	
	/**
	 * 登录之后的操作
	 * @see CWebUser::afterLogin()
	 */
	protected function afterLogin($fromCookie)
	{
		LoginLog::createLog(LoginLog::admin,$this);
	}
}

