<?php

class Agent_loginController extends AgentController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
				//验证码
				'captcha'=>array(
						'class'=>'CCaptchaAction',
						'backColor' =>mt_rand(100,255).mt_rand(100,255).mt_rand(100,255),
						'foreColor'=>mt_rand(100,100).mt_rand(0,100).mt_rand(0,100),
						'maxLength'=>4,
						'minLength'=>4,
						'offset'=>mt_rand(5,20),
						'padding'=>mt_rand(0,5),
						'width'=>140,
						'height'=>50,
						'testLimit'=>3,//使用3次
				),
		);
	}

	/**
	 * 初始化
	 * @author moore
	 */
	public function init() 
	{
		parent::init();
		$this->layout = '/layouts/column';
	}

	/**
	 * 系统登录
	 */
	public function actionIndex()
	{
		if(! Yii::app()->agent->isGuest)
			$this->redirect(Yii::app()->homeUrl);
		$model_pwd = new AgentLoginForm;
		$model_sms = new AgentSmsLoginForm;

		if(isset($_POST['AgentLoginForm']))
		{
			$model_pwd->attributes = $_POST['AgentLoginForm'];
			if ($model_pwd->validate() && $model_pwd->login())
				$this->redirect(Yii::app()->homeUrl);
		}
		elseif(isset($_POST['AgentSmsLoginForm']))
		{
			//获得所有post 值
			$model_sms->attributes = $_POST['AgentSmsLoginForm'];
			//输入了验证码
			if ($model_sms->validate() && $model_sms->login())
				$this->redirect(Yii::app()->homeUrl);
		}
		$this->render('index', array('model_pwd' =>$model_pwd,'model_sms'=>$model_sms));
	}

	/**
	 *短信发送 
	 */
	public function actionCaptcha_sms(){
		if(isset($_POST['verifyCode']) && isset($_POST['phone']))
		{
			$model=new SmsLoginForm;
			$model->scenario='yes';
			$model->attributes=array('verifyCode'=>$_POST['verifyCode'],'phone'=>$_POST['phone']);
			if($model->validate())
			{
				if($model->agent_login_send())
				{
					echo json_encode(array());
				}else 
					echo json_encode(array('verifyCode'=>'发送短信次数过于频繁'));
			}else
				echo json_encode($model->get_error());
		}
	}

	/**
	 * 退出当前登录模块
	 */
	public function actionOut(){
		Yii::app()->agent->logout(false);
		Yii::app()->agent->loginRequired();
	}


}