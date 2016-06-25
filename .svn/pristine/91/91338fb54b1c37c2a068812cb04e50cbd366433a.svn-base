<?php

class LoginController extends ApiController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		//{actions}
		return array(
			//验证码
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor' =>mt_rand(100,255).mt_rand(100,255).mt_rand(100,255),
				'foreColor'=>mt_rand(100,100).mt_rand(0,100).mt_rand(0,100),
				'maxLength'=>4,
				'minLength'=>4,
				'offset'=>mt_rand(10,13),
				'padding'=>mt_rand(1,2),
				'width'=>107,
				'height'=>30,
				'testLimit'=>3,//使用3次
			),
		);
		//{actions}
	}

	/**
	 * 初始化
	 * @see MainController::init()
	 */
	public function init(){
		parent::init();
	}

	/**
	 * 登录=====用户名+密码
	 */
	public function actionIndex()
	{	
		if(isset($_POST['UserLoginForm']))
		{
			$model=new UserLoginForm;
			$model->scenario = 'no';
			$model->attributes = $_POST['UserLoginForm'];
			if ($model->validate() && $model->login()) 
			{
				//获得用户信息
				$return = $this->user_info($model);
				//输出
				$this->send($return, array('home'=>Yii::app()->homeUrl));
			}else
				$this->send_error_form($this->form_error($model));
		}else
			$this->send_csrf(array(
					//'verifyCode'=>Yii::app()->createUrl('/api/login/captcha',array('refresh'=>1)),
			));
	}

	/**
	 * 短信登录====验证短信及登录
	 */
	public function actionLogin_sms()
	{
		if(isset($_POST['UserSmsLoginForm'])) 
		{
			$model = new UserSmsLoginForm;		
			$model->scenario = 'no';
			//获得所有post 值
			$model->attributes = $_POST['UserSmsLoginForm'];
			//输入了验证码
			if ($model->validate() && $model->login()) 
			{
				//获得用户信息
				$return = $this->user_info($model);
				//输出
				$this->send($return, array('home' => Yii::app()->homeUrl));
			} else
				$this->send_error_form($this->form_error($model));
		}else			
			$this->send_csrf(array(
					//'verifyCode'=>Yii::app()->createUrl('/api/login/captcha',array('refresh'=>1)),
			));
	}
	
	/**
	 * 短信登录====发送短信
	 */
	public function actionCaptcha_sms()
	{
		if(isset($_POST['phone']))
		{
			$model = new SmsApiLoginForm;
			$model->scenario = 'no';
			
			$model->attributes = $_POST;		
			if($model->validate())
			{
				//验证
				if($model->api_login_send())
				{
					//成功
					$return=array(
						'status'=>STATUS_SUCCESS,
					);
					$this->send($return);
				}else
					$this->send_error_form($model->get_error());
			}else
				$this->send_error_form($model->get_error());
		}else
			$this->send_csrf();
	}

	/**
	 * 用户信息
	 */
	public function user_info($model)
	{
		if($model && isset($model->user->id)){
			$return = array();
			$datas=array(
				'phone',
				'nickname',
				'is_organizer',
				'audit',
				'gender',
				'count',
				'last_ip',
			);
			foreach($datas as $data)
				$return[$data]=$model->user->$data;
			$return['add_time'] = Yii::app()->format->datetime($model->user->add_time);
			$return['last_time'] = Yii::app()->format->datetime($model->user->last_time);
			return $return;
		}
		return array();
	}


	/**
	 * 退出当前登录模块
	 */
	public function actionOut(){
		Yii::app()->api->logout(false);
		$this->send(array('login'=>0),Yii::app()->params['API_LOGIN_REQUIRED']);
	}
}