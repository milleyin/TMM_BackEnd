<?php

class Store_loginController extends StoreMainController
{
	/**
	 * 设置当前操作数据模型
	 * @var string
	 */
	public $_class_model='StoreUser';

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
		$model=new StoreLoginForm;
		
		if(isset($_POST['StoreLoginForm']))
		{
			$model->attributes = $_POST['StoreLoginForm'];
			if ($model->validate() && $model->login()) 
			{
				// 获取商家信息
				$this->store_info();
			}else
				$this->send_error_form($this->form_error($model));
		}else 
			$this->send_csrf(array(
					'verifyCode'=>Yii::app()->createUrl('/store/store_login/captcha',array('refresh'=>1)),
			));
	}

	/**
	 * 短信登录====验证短信及登录
	 */
	public function actionLogin_sms()
	{
		$model_sms = new StoreSmsLoginForm;
		
		if(isset($_POST['StoreSmsLoginForm']))
		{
			//获得所有post 值
			$model_sms->attributes = $_POST['StoreSmsLoginForm'];
			//输入了验证码
			if ($model_sms->validate() && $model_sms->login()) 
			{
				//获取商家信息
				$this->store_info();
			} else
				$this->send_error_form($this->form_error($model_sms));
		}else			
			$this->send_csrf(array(
					'verifyCode'=>Yii::app()->createUrl('/store/store_login/captcha',array('refresh'=>1)),
			));
	}
	/**
	 * 短信登录====发送短信
	 */
	public function actionCaptcha_sms()
	{
		if(isset($_POST['verifyCode']) && $_POST['verifyCode'] && isset($_POST['phone']) && $_POST['phone'])
		{
			$model = new SmsStoreLoginForm();
			$model->scenario='yes';
			$model->attributes=array(
					'verifyCode'=>$_POST['verifyCode'],
					'phone'=>$_POST['phone']
			);
			if($model->validate())
			{
				//验证
				if($model->store_login_send())
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
		}else{
			$this->send_csrf();
		}
	}

	/**
	 * 商家信息
	 */
	public function store_info()
	{
		$return = array();
		$datas = array(
			'phone',
			'count',
			'last_ip',
		);

		$model = $this->loadModel(Yii::app()->store->id,array(
			'with'=>array('Store_Content'),
			'condition'=>'t.status=1'
		));

		$return['value'] = $model->id;
		if (!empty($model->Store_Content)) {
			$return['name'] = $model->Store_Content->name;
		} else {
			if($model->p_id !=0 )
			{
				$model = $this->loadModel(Yii::app()->store->id, array(
					'with' => array(
						'Store_Store' => array('with' => 'Store_Content', 'condition' => 'Store_Store.status=1'),
					),
					'condition' => 't.status=1'
				));
			}
			$return['name'] = $model->Store_Store->Store_Content->name;
		}

		foreach($datas as $data)
			$return[$data] = $model->$data;

		if ($model->p_id) {
			$return['is_main'] = false;
			$return['link'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/store/store_store/son_view',array('id'=>$model->id));
		} else {
			$return['is_main'] = true;
			$return['link'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/store/store_store/view');
		}


		$return['add_time'] = Yii::app()->format->datetime($model->add_time);
		$return['last_time'] = Yii::app()->format->datetime($model->last_time);

		$this->send($return, array('store/store_home/index'));
	}


	/**
	 * 退出当前登录模块
	 */
	public function actionOut(){
		Yii::app()->store->logout(false);
		$this->send(array(),Yii::app()->params['STORE_LOGIN_REQUIRED']);
	}
}