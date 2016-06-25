<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-09-08 11:29:43 */
class Agent_agentController extends AgentController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Agent';
	
	/**
	 * 修改手机的秘钥
	 * @var unknown
	 */
	public $_update_phone_key='__input_sms';
	/**
	 * 修改手机的秘钥的值
	 * @var unknown
	 */
	public $_update_phone_key_value=true;
	/**
	 * 修改手机的秘钥银行卡
	 * @var unknown
	 */
	public $_update_phone_bank_key='__input_bank_sms';
	/**
	 * 修改手机的秘钥的值
	 * @var unknown
	 */
	public $_update_phone_bank_key_value=true;
	/**
	 * 修改密码的秘钥
	 * @var unknown
	 */
	public $_update_phone_pwd_key='__input_pwd_sms';
	/**
	 * 修改手机密码的秘钥的值
	 * @var unknown
	 */
	public $_update_phone_pwd_key_value=true;


	/**
	 * 验证码类
	 * @return array
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
	 * 代理商-我的收益
	 */
	public function actionIncome(){
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/profit.css');

		$this->_class_model = 'Account';
		$criteria = new CDbCriteria;
		$criteria->addCondition('`status`=1 AND `account_type`=:account_type AND `account_id`=:account_id');
		$criteria->params[':account_type'] = Account::agent;
		$criteria->params[':account_id'] = Yii::app()->agent->id;

		$account_model = Account::model()->find($criteria);
		if (empty($account_model)) {
			throw new CHttpException(404,'没有找到相关的数据！.');
		}

		$this->render('income',array(
			'model'=>$account_model,
		));
	}

	/**
	 * 账号信息
	 */
	public function actionAccount(){
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/set_up.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/details.css');

		$id = Yii::app()->agent->id;

		$this->render('account',array(
			'model'=>$this->loadModel($id, '`status`=1'),
		));
	}

	/*
	 * 账务信息======银行卡信息
	 */
	public function actionBank(){
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/set_up.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/details.css');

		$id = Yii::app()->agent->id;


		$this->render('bank',array(
			'model'=>$this->loadModel($id,array(
				'with'=>array(
					'Agent_Bank'
				),
				'condition'=>' t.status=1 ',
			)),
		));
	}

	/**
	 * 绑定银行卡=====手机验证页
	 */
	public function actionBind_bank(){
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/set_up.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/details.css');

		$id = Yii::app()->agent->id;
		$model=$this->loadModel($id, '`status`=1');

		$model->scenario='agent_bind_bank';
		$this->_Ajax_Verify($model,'agent-form');

		if(isset($_POST['Agent']))
		{
			$model->attributes=$_POST['Agent'];
			if($model->validate() && $model->verifycode_bank())
			{
				//设置 输入的短信
				Yii::app()->agent->setState($this->_update_phone_bank_key, $this->_update_phone_bank_key_value);
				$this->log('代理商绑定银行账号验证 手机号:'.$model->phone,ManageLog::agent,ManageLog::create);
				//跳转下一步
				$this->redirect(array('bank_submit'));
			}
		}

		$this->render('bind_bank',array(
			'model'=>$model,
		));
	}

	/**
	 * 绑定银行卡====提交页
	 */
	public function actionBank_submit(){

		//判断是否是从是一个控制器过来的
		if(!(Yii::app()->agent->hasState($this->_update_phone_bank_key)  && Yii::app()->agent->getState($this->_update_phone_bank_key)==$this->_update_phone_bank_key_value) )
			$this->redirect(array('bank'));

		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/set_up.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/details.css');

		$id = Yii::app()->agent->id;
		$model=$this->loadModel($id, '`status`=1');

		$model->scenario='bank';
		$this->_Ajax_Verify($model,'agent-form');

		if(isset($_POST['Agent'])) {
			$model->attributes = $_POST['Agent'];
			if ($model->validate()) {
				if ($model->save(false)) {
					$this->log('更新/设置代理商的银行信息:' . $model->phone, ManageLog::agent, ManageLog::update);
					//成功  将值清空
					Yii::app()->agent->setState($this->_update_phone_bank_key, false);
					//跳转安全页
					$this->redirect(array('/agent/agent_agent/bank'));
				}
			} else {
				Yii::app()->agent->setState($this->_update_phone_bank_key, false);
				$this->redirect(array('/agent/agent_agent/bind_bank'));
			}
		}

		$this->render('bank_submit',array(
			'model'=>$model,
		));
	}

	/**
	 * 保证金
	 */
	public function actionDeposit(){

		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/set_up.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/details.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/business.css');

		//搜索
		$model=new DepositLog;
		$criteria=new CDbCriteria;
		$criteria->addColumnCondition(array(
			'deposit_who'=>DepositLog::deposit_agent,
			'deposit_id'=>Yii::app()->agent->id,
			'status'=>1,
		));
		$this->render('deposit',array(
			'dataProvider'=>$model->search($criteria),
			'model'=>$this->loadModel(Yii::app()->agent->id),
		));
	}
	/**
	 * 查看区域
	 */
	public function actionAreainfo()
	{

		$this->addCss(Yii::app()->baseUrl . "/css/agent/css/business.css");

		$id = Yii::app()->agent->id;

		$store_model = $this->loadModel($id, array(
			'with' => array(
				'Agent_area_info'
			),
			'condition' => 't.status=1 ',
		));
		//搜索
		$model = new Area;
		$criteria = new CDbCriteria;

		$criteria->with = array(
			'Area_Area_M' => array(
				'with' => array('Area_Area_P')
			),
		);
		if (isset($_GET['Area']['area_id_p']) && $_GET['Area']['area_id_p']) {
			$model->area_id_p = $_GET['Area']['area_id_p'];
			$criteria->addColumnCondition(array('Area_Area_P.id' => $model->area_id_p));
		}

		if(isset($_GET['Area']['area_id_m']) && $_GET['Area']['area_id_m']){
			$model->area_id_m = $_GET['Area']['area_id_m'];
			$criteria->addColumnCondition(array('Area_Area_M.id'=>$model->area_id_m));
		}

		if(isset($_GET['Area']['area_id_c']) && $_GET['Area']['area_id_c']){
			$model->area_id_c = $_GET['Area']['area_id_c'];
			$criteria->addColumnCondition(array('t.id'=>$model->area_id_c));
		}

		$criteria->addCondition('`t`.`pid`=`Area_Area_M`.`id` AND `Area_Area_M`.`pid`=`Area_Area_P`.`id` AND `Area_Area_P`.`pid`=0');

		$criteria->addColumnCondition(array(
			't.agent_id'=>$id,
		));
		$this->render('areainfo',array(
			'dataProvider'=>$model->search($criteria),
			'store_model'=>$store_model,
			'model'=>$model,
		));
	}
	/**
	 * 公司信息
	 */
	public function actionCompany(){
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/set_up.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/details.css');

		$id = Yii::app()->agent->id;

		$this->render('company',array(
			'model'=>$this->loadModel($id, '`status`=1'),
		));
	}

	/**
	 * 安全信息
	 */
	public function actionSafe(){
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/set_up.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/details.css');

		$id = Yii::app()->agent->id;
		$this->render('safe',array(
			'model'=>$this->loadModel($id, '`status`=1'),
		));
	}

	/**
	 * 更换手机号  ====  输入旧手机号
	 */
	public function actionSafeInput(){
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/set_up.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/details.css');


		$id = Yii::app()->agent->id;
		$model = $this->loadModel($id, '`status`=1');
		$model->phone='';
		$model->scenario='agent_update_old';
		
		$this->_Ajax_Verify($model,'agent-form');
		
		if(isset($_POST['Agent']))
		{
			$model->attributes=$_POST['Agent'];
			if($model->validate() && $model->verifycode())
			{
				//设置 输入的短信
				Yii::app()->agent->setState($this->_update_phone_key, $this->_update_phone_key_value);
				$this->log('代理商修改手机号码验证 手机号:'.$model->phone,ManageLog::agent,ManageLog::create);
				//跳转下一步
				$this->redirect(array('safeNew'));
			}
		}

		$this->render('safeinput',array(
			'model'=>$model
		));
	}

	/**
	 * 更换手机号 ==== 输入新手机号
	 */
	public function actionSafeNew(){
		//判断是否是从是一个控制器过来的
		if(!(Yii::app()->agent->hasState($this->_update_phone_key)  && Yii::app()->agent->getState($this->_update_phone_key)==$this->_update_phone_key_value) )
			$this->redirect(array('safe'));
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/set_up.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/details.css');

		$id = Yii::app()->agent->id;
		$model = $this->loadModel($id, '`status`=1');

		$model->scenario='agent_update_new';		
		$this->_Ajax_Verify($model,'agent-form');
		
		if(isset($_POST['Agent']))
		{
			$model->attributes=$_POST['Agent'];
			if($model->validate() && $model->verifycode())
			{
				if($model->save(false))
				{
					$this->log('更新代理商手机号码:'.$model->phone,ManageLog::agent,ManageLog::update);
					//成功  将值清空
					Yii::app()->agent->setState($this->_update_phone_key, false);
					//跳转安全页
					$this->redirect(array('/agent/agent_login/out'));
				}			
			}else{
				Yii::app()->agent->setState($this->_update_phone_key, false);
				$this->redirect(array('/agent/agent_agent/safeInput'));
			}		
		}

		$this->render('safenew',array(
			'model'=>$model
		));
	}

	/**
	 * 修改密码  =====手机验证页
	 */
	public function actionPwd()
	{
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/set_up.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/details.css');


		$id = Yii::app()->agent->id;
		$model=$this->loadModel($id, '`status`=1');

		$model->scenario='agent_update_pwd';
		$this->_Ajax_Verify($model,'agent-form');

		if(isset($_POST['Agent']))
		{
			$model->attributes=$_POST['Agent'];
			if($model->validate() && $model->verifycode_pwd())
			{
				//设置 输入的短信
				Yii::app()->agent->setState($this->_update_phone_pwd_key, $this->_update_phone_pwd_key_value);
				$this->log('代理商更新密码验证 手机号:'.$model->phone,ManageLog::agent,ManageLog::create);
				//跳转下一步
				$this->redirect(array('pwd_submit'));
			}
		}

		$this->render('pwd',array(
			'model'=>$model,
		));
	}

	/**
	 *修改密码 ====提交页
	 */
	public function actionPwd_submit(){
		//判断是否是从是一个控制器过来的
		if(!(Yii::app()->agent->hasState($this->_update_phone_pwd_key)  && Yii::app()->agent->getState($this->_update_phone_pwd_key)==$this->_update_phone_pwd_key_value) )
			$this->redirect(array('safe'));

		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/set_up.css');
		$this->addCss(Yii::app()->request->baseUrl . '/css/agent/css/details.css');

		$id = Yii::app()->agent->id;
		$model=$this->loadModel($id, '`status`=1');

		$model->scenario='agent_new_pwd';
		$this->_Ajax_Verify($model,'agent-form');

		if(isset($_POST['Agent'])) {
			$model->attributes = $_POST['Agent'];
			$model->password = $model::pwdEncrypt($model->_pwd);
			if ($model->validate()) {
				if ($model->save(false)) {
					$this->log('更新/设置代理商的密码:' . $model->phone, ManageLog::agent, ManageLog::update);
					//成功  将值清空
					Yii::app()->agent->setState($this->_update_phone_pwd_key, false);
					//跳转安全页
					$this->redirect(array('/agent/agent_login/out'));
				}
			} else {
				Yii::app()->agent->setState($this->_update_phone_pwd_key, false);
				$this->redirect(array('/agent/agent_agent/pwd'));
			}
		}

		$this->render('pwd_submit',array(
			'model'=>$model,
		));
	}

	/**
	 *短信发送    ====  输入安全手机号
	 */
	public function actionCaptcha_sms()
	{
		if(isset($_POST['verifyCode']) && $_POST['verifyCode'] && isset($_POST['phone']) && $_POST['phone'])
		{
			$model=new SmsLoginForm;
			$model->scenario='old_phone';

			$model->attributes=array('verifyCode'=>$_POST['verifyCode'],'phone'=>$_POST['phone']);
			//验证手机号及  图形验证码
			if($model->validate())
			{
				if($model->agent_update_old_phone_sms())
				{
					echo json_encode(array());
				}else
					echo json_encode(array('phone'=>'发送短信次数过于频繁'));
			}else
				echo json_encode($model->get_error());
		}
	}

	/**
	 *短信发送   ==== 输入新手机号
	 */
	public function actionCaptcha_new_sms()
	{
		if(isset($_POST['phone']) && $_POST['phone'])
		{
		 	if(Yii::app()->agent->hasState($this->_update_phone_key)  && Yii::app()->agent->getState($this->_update_phone_key)==$this->_update_phone_key_value)
		 	{	
				$model=new SmsLoginForm;
				$model->scenario='new_phone';
				$model->attributes=array('phone'=>$_POST['phone']);
				//验证手机号
				if($model->validate())
				{
					if($model->agent_update_new_phone_sms())
					{
						echo json_encode(array());
					}else
						echo json_encode(array('phone'=>'发送短信次数过于频繁'));
				}else
					echo json_encode($model->get_error());
			 }
		}
	}

	/**
	 *短信发送   ==== 绑定银行卡
	 */
	public function actionCaptcha_bank_sms(){

		if(isset($_POST['verifyCode']) && $_POST['verifyCode'] )
		{
			$model=new SmsLoginForm;
			$model->scenario='bank_phone';

			$model->attributes=array('verifyCode'=>$_POST['verifyCode'],'phone'=>Yii::app()->agent->phone);
			//验证手机号及  图形验证码
			if($model->validate())
			{
				//默认正确
				//		echo json_encode(array());
				if($model->agent_update_bank_phone_sms())
				{
					echo json_encode(array());
				}else
					echo json_encode(array('verifyCode'=>'发送短信次数过于频繁'));
			}else
				echo json_encode($model->get_error());
		}
	}

	/**
	 *短信发送   ==== 修改密码
	 */
	public function actionCaptcha_pwd_sms(){

		if(isset($_POST['verifyCode']) && $_POST['verifyCode'] )
		{
			$model=new SmsLoginForm;
			$model->scenario='pwd_phone';

			$model->attributes=array('verifyCode'=>$_POST['verifyCode'],'phone'=>Yii::app()->agent->phone);
			//验证手机号及  图形验证码
			if($model->validate())
			{
				//默认正确
				//		echo json_encode(array());
				if($model->agent_update_pwd_phone_sms())
				{
					echo json_encode(array());
				}else
					echo json_encode(array('verifyCode'=>'发送短信次数过于频繁'));
			}else
				echo json_encode($model->get_error());
		}
	}


}
