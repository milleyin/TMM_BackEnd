<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2016-04-01 10:47:25 */
class CashController extends OperatorMainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model = 'Cash';
	
	/**
	 * 创建
	 */
	public function actionCreate()
	{
		//是否有提现的
		Cash::getIsCash(Yii::app()->operator->id, Cash::cash_type_agent) && $this->redirect(array('admin'));	
		$model = new Cash;
		
		!($model->Cash_BankCard = BankCard::getBankCard(Yii::app()->operator->id, BankCard::agent)) && $this->redirect(array('/operator/bankCard/create'));
		$model->accountModel = Account::getAccount(Yii::app()->operator->id, $model::$_cash_type_account[Cash::cash_type_agent], Account::money_type_rmb);			
		
		$cash = array(
			'cash_id'=>Yii::app()->operator->id,
			'cash_type'=>Cash::cash_type_agent,
		);
		$model->scenario = 'create';
		if ( !$model::setCash($model, array(), $cash, Yii::app()->params['order_deposit_price_agent']))
			$this->redirect(array('admin'));
		$this->_Ajax_Verify($model, 'cash-form');
		
		$this->_class_model = 'Agent';
		$model->Cash_Agent = $this->loadModel(Yii::app()->operator->id, 'status=:status', array(':status'=>Agent::status_suc));

		if (isset($_POST['Cash']))
		{
			$model::setCash($model, $_POST['Cash'], $cash, Yii::app()->params['order_deposit_price_agent']);
			$sms = array(
					'sms_id'=>Yii::app()->operator->id,
					'sms_type'=>SmsLog::sms_agent,
			);
			$role = array(
					'role_id'=>Yii::app()->operator->id,
					'role_type'=>SmsLog::send_agent,
			);
			if ($model->validate() && $model->sendSms($sms, $role, $model->Cash_Agent->phone, 'agent_cash_phone') && $this->setCacheAttributes($model))
				$this->redirect(array('save'));
		}

		$this->render('create', array(
			'model'=>$model,
		));
	}
	
	/**
	 * 设置
	 * @param unknown $model
	 * @return unknown
	 */
	public function setCacheAttributes($model)
	{
		return Yii::app()->session['operatorCacheCashAttributes' . Yii::app()->operator->id] = $model->getAttributes();
	}
	
	/**
	 * 获取
	 */
	public function getCacheAttributes()
	{
		return Yii::app()->session['operatorCacheCashAttributes' . Yii::app()->operator->id];
	}
	
	/**
	 * 删除
	 * @return boolean
	 */
	public function unsetCacheAttributes()
	{
		unset(Yii::app()->session['operatorCacheCashAttributes' . Yii::app()->operator->id]);
		return true;
	}
	
	/**
	 * 验证保存
	 * @throws Exception
	 */
	public function actionSave()
	{
		//是否有提现的
		Cash::getIsCash(Yii::app()->operator->id, Cash::cash_type_agent) && $this->redirect(array('admin'));
		!($data = $this->getCacheAttributes()) && $this->redirect(array('create'));
		
		$model = new Cash;	
		
		!($model->Cash_BankCard = BankCard::getBankCard(Yii::app()->operator->id, BankCard::agent)) && $this->redirect(array('/operator/bankCard/create'));
		$model->accountModel = Account::getAccount(Yii::app()->operator->id, $model::$_cash_type_account[Cash::cash_type_agent], Account::money_type_rmb);	

		$cash = array(
				'cash_id'=>Yii::app()->operator->id,
				'cash_type'=>Cash::cash_type_agent,
		);
		$model->scenario = 'create';
		$model::setCash($model, $data, $cash, Yii::app()->params['order_deposit_price_agent']);
		
 		$model->scenario = 'save';
 		$this->_class_model = 'Agent';
 		$model->Cash_Agent = $this->loadModel(Yii::app()->operator->id, 'status=:status', array(':status'=>Agent::status_suc));
 		
		if (isset($_POST['Cash']))
		{
			$model->attributes = $_POST['Cash'];
			$sms = array(
					'sms_id'=>Yii::app()->operator->id,
					'sms_type'=>SmsLog::sms_agent,
			);
			$role = array(
					'role_id'=>Yii::app()->operator->id,
					'role_type'=>SmsLog::send_agent,
			);
			if ($model->validate() && $model->validatorSms($sms, $role, $model->Cash_Agent->phone))
			{
				//开启事物
				$transaction = Yii::app()->db->beginTransaction();
				try
				{
					if (Cash::getIsCash(Yii::app()->operator->id, Cash::cash_type_agent))
						throw new Exception("提现申请未处理");
					
					if ( !($model->Cash_BankCard = BankCard::getBankCard(Yii::app()->operator->id, BankCard::agent)))
						throw new Exception("没有绑定银行卡");
					
					$model->accountModel = Account::getAccount(Yii::app()->operator->id, $model::$_cash_type_account[Cash::cash_type_agent], Account::money_type_rmb);
					$cash = array(
							'cash_id'=>Yii::app()->operator->id,
							'cash_type'=>Cash::cash_type_agent,
					);
					$model->scenario = 'create';
					$model::setCash($model, $data, $cash, Yii::app()->params['order_deposit_price_agent']);
					$model->money = $model->price;
					if ( !$model::cashSave($model))
						throw new Exception("保存提现失败");
					
					if ( !Account::moneyPendingCashFrozenRmb($model->price,
							array('account_id'=>Yii::app()->operator->id, 'account_type'=>Account::agent),
							array('info_id'=>$model->id, 'info'=>'提现申请', 'name'=>'提现申请', 'address'=>'')
					))
						throw new Exception("保存资金流水失败");
						
					$return = $this->log('提现申请成功', ManageLog::operator, ManageLog::create);
					$transaction->commit();
				}
				catch (Exception $e)
				{
					$transaction->rollBack();
					$this->error_log($e->getMessage(), ErrorLog::operator, ErrorLog::create, ErrorLog::rollback, __METHOD__);
				}
				$this->unsetCacheAttributes();
				if (isset($return) && $return)
					$this->redirect(array('admin'));
			}
		}
		
		$this->render('save', array(
				'model'=>$model,
		));
	}

	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model = new Cash('operatorSearch');
		$model->unsetAttributes();  // 删除默认属性
		if (isset($_GET['Cash']))
			$model->attributes = $_GET['Cash'];

		$this->render('admin', array(
			'model'=>$model,
		));
	}
}
