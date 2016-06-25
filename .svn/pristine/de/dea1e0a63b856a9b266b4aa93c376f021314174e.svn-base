<?php
/**
 * 用户短信登录模型
 * @author Changhai Zhan
 *
 */
class UserSmsLoginForm extends CFormModel
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
			array('verifyCode', 'length', 'max'=>4, 'min'=>4, 'on'=>'yes'),
			array('phone', 'length', 'max'=>11, 'min'=>11, 'on'=>'yes, no'),
			array('sms', 'length', 'max'=>6, 'min'=>6, 'on'=>'yes, no'),
			//手机登录
			array('phone, sms, verifyCode', 'required', 'on'=>'yes'),
			array('phone', 'match', 'pattern'=>Yii::app()->params['pattern']['phone'], 'on'=>'yes'),
			array('sms', 'authenticate', 'on'=>'yes'),

			array('phone, sms', 'required', 'on'=>'no'),
			array('phone', 'match', 'pattern'=>Yii::app()->params['pattern']['phone'], 'on'=>'no'),
			array('sms', 'authenticate', 'on'=>'no'),
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
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			if(preg_match(Yii::app()->params['pattern']['phone'],$this->phone)) 
			{
				//查当前用户
				$model = User::model()->find('phone=:phone', array(':phone' => $this->phone));
				//存在  发送手机ID为自己　　不存在　　为　０　
				$sms_id = isset($model->id) ? $model->id:0;
				//导入短信类
				Yii::import('ext.Send_sms.Send_sms');
				$params = array(
					'sms_id' => $sms_id,
					'sms_type' => SmsLog::sms_user,
					'role_id' => $sms_id,
					'role_type' => SmsLog::send_user,
					'sms_use' => $sms_id==0? SmsLog::use_register: SmsLog::use_login,
				);
				if (Send_sms::verifycode($this->phone, $params, $this->sms))
				{
					//注册
					if($sms_id==0)
					{
						if(! User::register($this->phone))
						{
							$this->addError('phone', '手机号注册错误');
							return false;
						}
					}
					//验证
					$this->_identity = new UserSmsUserIdentity($this->phone, $this->sms);
					if ($this->_identity->authenticate()) 											//验证手机 密码
					{																									
							$this->_identity->user->login_status($this,true);				//验证 状态
					} 
					else 
					{
						if (isset($this->_identity->user))											//用户存在 错误++
							$this->_identity->user->login_error();								//记录错误登录次数
						$this->addError('phone', '手机号或验证码错误');
					}
				}else
					$this->addError('phone', '手机号或验证码错误');

			}else
				$this->addError('phone', '手机号不是有效的');
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
			$this->_identity=new UserSmsUserIdentity($this->phone,$this->sms);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserSmsUserIdentity::ERROR_NONE)
		{
			Yii::app()->api->setFlash(LoginLog::login_type_name,LoginLog::login_type_sms);
			return Yii::app()->api->login($this->_identity,Yii::app()->api->allowAutoLogin ? 3600*24*30:0);
		}
		
		return false;
	}

	/**
	 * 返回用户数据
	 * @return mixed
	 */
	public function getUser()
	{
		if(isset($this->_identity->user))
			return $this->_identity->user;
		
		return NULL;
	}

}
