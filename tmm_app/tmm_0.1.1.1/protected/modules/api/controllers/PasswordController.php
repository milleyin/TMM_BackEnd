<?php
/**
 * 密码的情况
 * @author Changhai Zhan
 *	创建时间：2015-12-28 10:32:30 */
class PasswordController extends ApiController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Password';
	/**
	 * 密码短信的key
	 */
	public $_password_sms_key = '__password_sms_key_';
	/**
	 * 验证有效时间
	 */
	public $_password_sms_time=150;
	/**
	 * 归属角色的密码
	 * @var unknown
	 */
	public $_role_type=Password::role_type_user;
	/**
	 * 操作密码的角色
	 * @var unknown
	 */
	public $_manage_type=Password::manage_type_user;
		
	/**
	 * 查看密码详情 
	 * @param integer $type 密码类型 默认 支付密码
	 * -3 已禁用
	 * -2 已无效
	 * -1 未设置
	 * 0 已锁定
	 * 1 已设置
	 */
	public function actionView($type=Password::password_type_pay)
	{
		if(isset(Password::$_password_type[$type]))
		{
			$name=Password::$_password_type[$type];
			$criteria = new CDbCriteria;
			$criteria->with = array(
				'Password_User'=>array('select'=>'phone'),
			);
			$criteria->select = '`use_error`,`centre_status`,`status`';
			$criteria->addColumnCondition(array(
				'`t`.`password_type`' => $type,									//密码的类型
				'`t`.`role_type`' => $this->_role_type,							//用户角色
				'`t`.`role_id`' => Yii::app()->api->id,							//归属用户
				'`Password_User`.`status`'=>User::status_suc,			//用户正常
			));
			$model = Password::model()->find($criteria);
			$return = array();
			$return['type']=array(
				'name'=>$name,
				'value'=>$type,
			);
			if($model)
			{
				if($model->status != Password::status_normal)		//记录状态
				{				
					$return['name']='已禁用';
					$return['value']=-3;
				}
				elseif($model->centre_status != Password::centre_status_valid) //核心状态
				{
					$return['name']='已无效';
					$return['value']=-2;
				}
				else
				{
					if(! isset(Password::$_use_error_limit[$type]))
						$use_error_limit = 0;
					else
						$use_error_limit = Password::$_use_error_limit[$type];
					
					$return['sms_link'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/password/captcha_sms',array('type'=>$type));
					$return['validate'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/password/validate',array('type'=>$type));
					$return['link'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/password/update',array('type'=>$type));
						
					if($use_error_limit <= 0 || ($use_error_limit > 0 && $model->use_error < $use_error_limit))
					{
						$return['name'] = '已设置';
						$return['value'] = 1;
					}
					elseif($use_error_limit > 0 && $model->use_error >= $use_error_limit)
					{
						$return['name']='已锁定';
						$return['value']=0;
					}
					else
					{
						$return['name']='已设置';
						$return['value']=1;
					}
				}
			}
			else
			{
				$return['name']='未设置';
				$return['value']=-1;
				$return['sms_link'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/password/captcha_sms',array('type'=>$type));
				$return['link'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/password/create',array('type'=>$type));
				$return['validate'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/api/password/validate',array('type'=>$type));
			}
			$this->send($return);
		}else
			$this->send_error(DATA_NULL);
	}

	/**
	 * 创建 密码
	 */
	public function actionCreate($type=Password::password_type_pay)
	{
		if($this->isValidate() && isset($_POST['Password']))
		{
			$role_who = array(
					'role_id'=>Yii::app()->api->id,
					'role_type'=>Password::role_type_user,
			);
			$manage_role = array(
					'manage_id'=>Yii::app()->api->id,
					'manage_type'=>Password::manage_type_user
			);
			$model=Password::createPwd($type,$role_who,$_POST['Password'],$manage_role);
			if($model && $model->validate())
			{
				$transaction = Yii::app()->db->beginTransaction();
				try
				{
					$return = Password::executeCreateUpdatePwd($model);
					if(! $return)
						throw new Exception("密码加密错误");
					$this->setValidatePassword($type,null);
					$this->log('设置用户角色'.$model::$_password_type[$model->password_type],ManageLog::user,ManageLog::create);
					$transaction->commit();
				}
				catch(Exception $e)
				{
					$transaction->rollBack();
					$this->error_log($e->getMessage(),ErrorLog::user,ErrorLog::create,ErrorLog::rollback,__METHOD__);
				}
				if(isset($return) && $return)
				{
					$return=array(
							'type'=>array(
									'name'=>Password::$_password_type[$type],
									'value'=>$type,
							),
							'status'=>STATUS_SUCCESS,
					);
					$this->send($return);
				}
				else
				{
					$return=array(
							'type'=>array(
									'name'=>Password::$_password_type[$type],
									'value'=>$type,
							),
							'status'=>STATUS_FAIL,
					);
					$this->send($return);
				}
			}elseif($model)
				$this->send_error_form($this->form_error($model));
			else 
				$this->send_error_form(array(
							'Password__pwd'=>array(
								(isset(Password::$_password_type[$type])?Password::$_password_type[$type]:'').'已设置'
							)
						)
				);
		}elseif(!$this->isValidate($type) && isset($_POST['Password']))
			$this->send_error_form(array(
				'Password__pwd'=>array('短信验证 未通过'),
			));
		
		$this->send_csrf();
	}

	/**
	 * 更新
	 * @param integer $id
	 */
	public function actionUpdate($type=Password::password_type_pay)
	{
		if($this->isValidate() && isset($_POST['Password']))
		{
			$role_who = array(
					'role_id'=>Yii::app()->api->id,
					'role_type'=>Password::role_type_user
			);
			$manage_role = array(
					'manage_id'=>Yii::app()->api->id,
					'manage_type'=>Password::manage_type_user
			);
			$model=Password::updatePwd($type,$role_who,$_POST['Password'],$manage_role);
			if($model && $model->validate())
			{
				$transaction = Yii::app()->db->beginTransaction();
				try
				{
					$return = Password::executeCreateUpdatePwd($model);
					if(! $return)
						throw new Exception("密码加密错误");
					$this->setValidatePassword($type,null);
					$this->log('更新用户角色'.$model::$_password_type[$model->password_type],ManageLog::user,ManageLog::update);
					$transaction->commit();
				}
				catch(Exception $e)
				{
					$transaction->rollBack();
					$this->error_log($e->getMessage(),ErrorLog::user,ErrorLog::create,ErrorLog::rollback,__METHOD__);
				}				
				if(isset($return) && $return)
				{
					$return=array(
							'type'=>array(
									'name'=>Password::$_password_type[$type],
									'value'=>$type,
							),
							'status'=>STATUS_SUCCESS,
					);	
					$this->send($return);
				}
				else
				{
					$return=array(
							'type'=>array(
									'name'=>Password::$_password_type[$type],
									'value'=>$type,
							),
							'status'=>STATUS_FAIL,
					);
					$this->send($return);
				}
			}elseif($model)
				$this->send_error_form($this->form_error($model));
			else 
				$this->send_error_form(array(
							'Password__pwd'=>array(
								(isset(Password::$_password_type[$type])?Password::$_password_type[$type]:'').'未设置'
							)
						)
				);
		}elseif(!$this->isValidate($type) && isset($_POST['Password']))
			$this->send_error_form(array(
				'Password__pwd'=>array('短信验证 未通过'),
			));
		
		$this->send_csrf();
	}
	
	/**
	 * 是否通过验证
	 * @param unknown $type
	 */
	public function actionIsvalidate($type=Password::password_type_pay)
	{
		$ruturn=array(
				'name'=>'验证未通过',
				'value'=>-1,
		);
		if($this->isValidate($type))
			$ruturn=array(
				'name'=>'验证通过',
				'value'=>1,
			);
			
		$this->send($ruturn);
	}
	
	/**
	 * 是否验证通过
	 * @param unknown $type
	 */
	public function isValidate($type=Password::password_type_pay)
	{
		$value = Yii::app()->api->getState($this->_password_sms_key.$type);
		if(isset($value['time'],$value['value']) && (time()-$value['time']) < $this->_password_sms_time)		
			return true;
		$this->setValidatePassword($type,null);
		return false;
	}
	
	/**
	 * 设置 验证信息 值
	 * @param unknown $type
	 * @param string $value
	 */
	public function setValidatePassword($type,$value='')
	{
		if($value !== null)
			$value = array('value'=>$type,'time'=>time());
		Yii::app()->api->setState($this->_password_sms_key.$type,$value);
	}

	/**
	 * 验证短信是否正确
	 * @param unknown $type
	 */
	public function actionValidate($type=Password::password_type_pay)
	{
		if(isset(Password::$_password_type[$type],$_POST['Password']))
		{
			$model = new Password;
			$model->scenario = 'validate_sms';
			$model->attributes = $_POST['Password'];
			if($model->validate() && $model->verifycode($type))
			{
				$this->setValidatePassword($type);
				//成功
				$return=array(
						'type'=>array(
							'name'=>Password::$_password_type[$type],
							'value'=>$type,
						),
						'status'=>STATUS_SUCCESS,
				);
				$this->send($return);
			}else
				$this->send_error_form($this->form_error($model));
		}
		$this->send_csrf();
	}

	/**
	 * 获取短信
	 */
	public function actionCaptcha_sms($type=Password::password_type_pay)
	{	
		if(isset(Password::$_password_type[$type]))
		{
			$user = User::model()->findByPk(Yii::app()->api->id,'status=:status',array(':status'=>User::status_suc));
			if ($user)
			{
				$model = new SmsApiLoginForm;
				
				$model->scenario = 'password';
				$model->attributes=array('phone'=>$user->phone);
				
				if($model->validate())
				{
					if($model->password_sms($type))
					{
						//成功
						$return=array(
							'type'=>array(
								'name'=>Password::$_password_type[$type],
								'value'=>$type,
							),
							'status'=>STATUS_SUCCESS,
						);
						$this->send($return);
					}else 
						$this->send_error_form($model->get_error());
				}else 
					$this->send_error_form($model->get_error());
			}else 
				$this->send_error_form(array('phone'=>array('用户账号 已禁用')));
		}
		$this->send_error(DATA_NOT_SCUSSECS);
	}
}
