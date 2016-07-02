<?php
/**
 * 管理员登录控制器
 * @author Changhai Zhan
 *
 */
class Tmm_loginController extends MainController
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
						'offset'=>mt_rand(-3,3),
						'padding'=>mt_rand(2,3),
						'width'=>120,
						'testLimit'=>3,//使用3次
				),
		);
	}
	
	/**
	 * 初始化
	 * @see MainController::init()
	 */
	public function init()
	{
		parent::init();
		$this->layout='/layouts/column';       
	}
	
	/**
	 * 登录
	 */
	public function actionIndex()
	{	
		$this->addCss(Yii::app()->baseUrl."/css/admin/login/index.css");
		$model=new AdminLoginForm;
		
		//$this->_Ajax_Verify($model,'login-form');
		if(isset($_POST['AdminLoginForm']))
		{
			$model->attributes = $_POST['AdminLoginForm'];
			if ($model->validate() && $model->login())
				$this->redirect(Yii::app()->homeUrl);
			
		}
		$this->render('index',array('model'=>$model));
	}

	/**
	 * 退出当前登录模块
	 */
	public function actionOut(){
		Yii::app()->admin->logout(false);
		Yii::app()->admin->loginRequired();		
	}
	
	
	
	
	
}