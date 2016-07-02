<?php
/**
 * 商家提现申请记录控制器
 * @author Moore Mo
 * Class Store_cashController
 */
class Store_cashController extends StoreMainController
{
	/**
	 * 设置当前操作数据模型
	 * @var string
	 */
	public $_class_model = 'Cash';

	/**
	 * 提现申请记录列表
	 */
	public function actionIndex()
	{
		//登入验证
		$model_store = $this->verify_store_login();
		//获得记录列表
		$return = Cash::cash_index(Yii::app()->store->id,Cash::cash_type_store);
		//输出缓冲
		$this->send($return);
	}

	/**
	 * 提现 （主帐号）
	 *
	 * 2016-0105 改逻辑
	 * 1. 短信验证不管对错都不可以在用
	 * 2. 先验证账号是否可以申请的现
	 * 3. 新增 提现金额
	 */
	public function actionCreate()
	{
		//登入验证
		$model_store = $this->verify_store_login();

		$model = new Cash;

		//  验证帐是否可以申请
		$cash_result = $this->validate_cash($model);
		if (!$cash_result)
			$this->send_error_form($this->form_error($model));

		if (isset($_POST['Cash']) && is_numeric($_POST['Cash']['price']) && $_POST['Cash']['sms'] ) {

			$model = new Cash;
			$model->scenario = 'store_create';
			$model->attributes=array('phone'=>$model_store->phone,'sms'=>$_POST['Cash']['sms'],'price'=>$_POST['Cash']['price']);

			if ($model->verifycode_cash($model_store->id) && !$model->hasErrors() && $model->validate()) {
				//开启事物
				$transaction = Yii::app()->db->beginTransaction();
				try
				{

						//$model->unsetAttributes();
						// 1. 验证是否已绑定银行帐号
						$bank_info = $this->validate_bank($model);
						if ($bank_info == false) {
							throw new Exception("商家提现-错误-未绑定银行帐号");
						}
						// 2. 验证帐是否可以申请
						$cash_result = $this->validate_cash($model);
						if ($cash_result == false) {
							throw new Exception("商家提现-错误-还有没处理完的提现申请");
						}

						// 3. 验证帐单金额
						$bills_result = $this->validate_account($model,$_POST['Cash']['price']);

						//$this->p_r($bills_result);
						if ($bills_result == false) {
							throw new Exception("商家提现-错误-提取金额不足");
						}

						// 验证成功成功后
						$model->cash_type	 	= Cash::cash_type_store;	    //商家
						$model->cash_id 		= Yii::app()->store->id;		//商家ID
						$model->cash_status 	= Cash::cash_status_cashing;	//提现状态 待提现
						$model->audit_status 	= Cash::audit_status_first;	//审核状态 待初审
						$model->money 			= $_POST['Cash']['price'];	//可提现金额
						$model->price 			= $_POST['Cash']['price'];	//提现金额
						$model->bank_card_id 	= $bank_info->id;				//银行卡管理ID
						$model->bank_id 		= $bank_info->bank_id;			//银行类型ID
						$model->bank_name 		= $bank_info->bank_name;		//开户姓名
						$model->bank_branch 	= $bank_info->bank_branch;		//开户支行
						$model->bank_code 		= $bank_info->bank_code;		//开户银行账号
						$model->bank_type 		= $bank_info->bank_type;		//用户银行类型
						$model->bank_identity = $bank_info->bank_identity;	//开户身份证

						if (!$model->save(false)) {
							throw new Exception("商家提现-错误-添加提现记录失败");
						}

						if (!$this->update_cash_account($model)) {
							throw new Exception("商家提现-错误-冻结提现金额失败");
						}

						$return = $this->log('商家提现', ManageLog::store, ManageLog::create);


					$transaction->commit();
				}catch (Exception $e){
					$transaction->rollBack();
					$this->error_log($e->getMessage(), ErrorLog::store, ErrorLog::create, ErrorLog::rollback, __METHOD__);
				}
			} else {
				$this->send_error_form($this->form_error($model));
			}

			if (isset($return)) {
				//成功
				$return=array(
					'status'=>STATUS_SUCCESS,
				);
				$this->send($return);
			} else {
				$this->send_error_form($this->form_error($model));
			}

		} else {
			$model = new Cash;
			$bank_info = $this->validate_bank($model, $model_store);

			if (! empty($bank_info)) {
				$return['data'] = array(
					'bank_id'   => $bank_info->bank_id,
					'bank_name' => $bank_info->bank_name,
					'bank_code' => $bank_info->bank_code,
					'bank_code_format' => BankCard::set_number_black($bank_info->bank_code,4),
					'bank_info' => $bank_info->BankCard_Bank->name,
					'bank_branch' => $bank_info->bank_branch
				);
			} else {
				$this->send_error(DATA_NULL);
			}

			$this->send_csrf($return);
		}
	}

	/**
	 * 插一步，验证金额
	 */
	public function actionCash_verify_price(){

		//登入验证
		$model_store = $this->verify_store_login();

		$model = new Cash;

		//  验证帐是否可以申请
		$cash_result = $this->validate_cash($model);
		if (!$cash_result)
			$this->send_error_form($this->form_error($model));

		if (isset($_POST['Cash']) && is_numeric($_POST['Cash']['price']) ) {

			$model = new Cash;
			$model->scenario = 'cash_price';
			$model->attributes=array('price'=>$_POST['Cash']['price']);

			if ( !$model->hasErrors() && $model->validate()) {

				try
				{
					// 1. 验证是否已绑定银行帐号
					$bank_info = $this->validate_bank($model);
					if ($bank_info == false)
						throw new Exception("商家提现-错误-未绑定银行帐号");

					// 2. 验证帐是否可以申请
					$cash_result = $this->validate_cash($model);
					if ($cash_result == false)
						throw new Exception("商家提现-错误-还有没处理完的提现申请");

					// 3. 验证帐单金额
					$bills_result = $this->validate_account($model,$_POST['Cash']['price']);
					if ($bills_result == false)
						throw new Exception("商家提现-错误-提取金额不足");


					$return = true;

				}catch (Exception $e){
					$this->error_log($e->getMessage(), ErrorLog::store, ErrorLog::create, ErrorLog::rollback, __METHOD__);
				}

			} else {
				$this->send_error_form($this->form_error($model));
			}

			if (isset($return)) {
				//成功
				$return=array(
					'status'=>STATUS_SUCCESS,
				);
				$this->send($return);
			} else {
				$this->send_error_form($this->form_error($model));
			}

		}
		$this->send_csrf();
	}

	/**
	 * 发送短信=====提取
	 */
	public function actionCaptcha_cash()
	{
		//登入验证
		$model_store = $this->verify_store_login();

		$model = new Cash;
		$store_model = $this->validate_bank($model);

		if(! $store_model)
			$this->send_error(DATA_NOT_SCUSSECS);

		if ($store_model->bank_id && $store_model->bank_name && $store_model->bank_branch && $store_model->bank_code)
		{
			if($model_store->phone)
			{
				$model = new SmsStoreLoginForm;
				$model->scenario = 'cash_phone';
				$model->attributes = array('phone'=>$model_store->phone);
				if($model->validate())
				{
					//验证
					if($model->store_cash_phone_sms(Yii::app()->store->id))
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
			}
			else
			{
				$this->send_error(DATA_NOT_SCUSSECS);
			}
		}
		else
		{
			$this->send_error(DATA_NOT_SCUSSECS);
		}
	}

	/**
	 * 提现时验证是否绑定银行  返回排序最新一条银行卡信息
	 * @param $model
	 * @return array|bool|mixed|null
	 */
	private function validate_bank($model)
	{

		$store_model = $this->get_store_info();

		if( ! (isset($store_model->Store_BankCard) && is_array($store_model->Store_BankCard) && isset($store_model->Store_BankCard[0]) && $store_model->Store_BankCard[0]) ){
			$model->addError('msg','未绑定银行帐号');
			$model->addError('status','001');
			return false;
		}

		return $store_model->Store_BankCard[0];
	}

	/**
	 * 提现时，更新帐单记录
	 * @param $model
	 * @param $cash_id
	 * @param $bills_count
	 * @return bool
	 */
	private function update_cash_bills($model, $cash_id, $bills_count)
	{
		$this->_class_model = 'Bills';
		$update_criteria_bills = new CDbCriteria;
		$update_criteria_bills->addCondition('`status`=1 AND `store_id`=:store_id AND `cash_id`=0 AND cash_status=:cash_status');
		$update_criteria_bills->params[':store_id'] = Yii::app()->store->id;
		$update_criteria_bills->params[':cash_status'] = Bills::cash_status_not_apply; // 未申请

		$count = Bills::model()->updateAll(array(   //更新商家项目 取消订单
			'cash_id' => $cash_id, // 申请记录id
			'cash_status' => Bills::cash_status_auditing, // 账单提现状态 待审核
		),$update_criteria_bills);

		if ($count != $bills_count)
		{
			$model->addError('msg','提现失败');
			$model->addError('status','005');
			return false;
			//throw new Exception("商家提现-错误-更新帐单记录失败");
		}
		return true;
	}

	/**
	 * 提现时，冻结金额
	 * @param $model
	 * @return bool
	 * @throws CDbException
	 */
	private function update_cash_account($model)
	{

		$result=Account::moneyPendingCashFrozenRmb(
			$model->price,
			array('account_id'=>Yii::app()->store->id,'account_type'=>Account::store),
			array('info_id'=>$model->id,'info'=>'提现申请','name'=>'提现申请','address'=>'')
		);

		if (! $result)
		{
			$model->addError('msg','提现失败');
			$model->addError('status','005');
			return false;
			//throw new Exception("商家提现-错误-冻结提现金额失败");
		}
		return true;

	}


	/**
	 * 提现时验证钱包
	 * @param $model
	 * @param $total_money
	 * @return bool
	 */
	private function validate_account($model, $total_money='')
	{
		$account_model = Account::getAccount(Yii::app()->store->id,Account::store);

		if ($account_model->money <=0)
		{
			$model->addError('msg', '提现金额不足');
			$model->addError('status', '002');
			return false;
		}

		if ($total_money < Yii::app()->params['order_deposit_price_store']) {
			//throw new Exception("商家提现-错误-提现金额不足100元");
			$model->addError('msg', '提现金额不足'.Yii::app()->params['order_deposit_price_store'].'元');
			$model->addError('status', '002');
			return false;
		}

		$price = bccomp($account_model->money, $total_money,3);

		if (! ($price ==0 || $price==1) )
		{
			//throw new Exception("商家提现-错误-提取金额不足");
			$model->addError('msg', '金额数目出错');
			$model->addError('status', '003');
			return false;
		}
		return array(
			'total_money' => $account_model->money,
		);

	}


	/**
	 * 提现时验证帐单，金额数目是否匹配
	 * @param $model
	 * @return array|bool
	 */
	private function validate_bills($model)
	{
		$this->_class_model = 'Bills';
		$criteria_bills = new CDbCriteria;
		$criteria_bills->addCondition('`status`=1 AND `store_id`=:store_id AND `cash_id`=0 AND cash_status=:cash_status');
		$criteria_bills->params[':store_id'] = Yii::app()->store->id;
		$criteria_bills->params[':cash_status'] = Bills::cash_status_not_apply; // 未申请
		$bills_models = Bills::model()->findAll($criteria_bills);

		if (empty($bills_models)) {
			//throw new Exception("商家提现-错误-提取金额不足");
			$model->addError('msg', '提现金额不足1');
			$model->addError('status', '002');
			return false;
		}
		// 可提取的金额
		$total_money = 0.00;
		foreach ($bills_models as $bills_model) {
			$total_money += $bills_model->items_money_store;
		}
		if ($total_money <= 0) {
			//throw new Exception("商家提现-错误-提取金额不足");
			$model->addError('msg', '提现金额不足2');
			$model->addError('status', '002');
			return false;
		}
		if ($total_money <= Yii::app()->params['order_deposit_price_store']) {
			//throw new Exception("商家提现-错误-提现金额不足100元");
			$model->addError('msg', '提现金额不足3'.Yii::app()->params['order_deposit_price_store'].'元');
			$model->addError('status', '002');
			return false;
		}

		if (! $this->validate_account($model, $total_money))
		{
			return false;
		}
		else
		{
			return array(
				'total_money' => $total_money,
				'bills_count' => count($bills_models),
			);
		}
	}


	/**
	 * 提现时验证是否还有提现申请
	 * @param $model
	 * @return bool
	 */
	private function validate_cash($model)
	{
		$this->_class_model = 'Cash';
		$criteria_cash = new CDbCriteria;
		$criteria_cash->addCondition('`status`=1 AND `cash_type`=:cash_type AND `cash_id`=:cash_id AND (`audit_status`=:audit_status_first OR `audit_status`=:audit_status_double OR `audit_status`=:audit_status_submit)');
		$criteria_cash->params[':cash_type'] = Cash::cash_type_store;
		$criteria_cash->params[':cash_id'] = Yii::app()->store->id;
		$criteria_cash->params[':audit_status_first'] = Cash::audit_status_first;
		$criteria_cash->params[':audit_status_double'] = Cash::audit_status_double;
		$criteria_cash->params[':audit_status_submit'] = Cash::audit_status_submit;
		$cash_model = Cash::model()->find($criteria_cash);
		if (! empty($cash_model))
		{
			// throw new Exception("商家提现-错误-还有没处理完的提现申请");
			$model->addError('msg','还有没处理完的提现申请');
			$model->addError('status','004');
			return false;
		}
		return true;
	}

	/**
	 * 获取商家信息
	 */
	private function get_store_info()
	{
		$this->_class_model = 'StoreUser';
		$store_model = $this->loadModel(Yii::app()->store->id,
			array(
				'with'=>array(
					'Store_BankCard'=>array(
						'with'=>'BankCard_Bank'
					)
				),
				'condition'=>'t.status=:status AND t.p_id=0 and Store_BankCard.status=:bank_status and Store_BankCard.card_type=:card_type and Store_BankCard.card_id=:card_id',
				'params'=>array(':status'=>1,':bank_status'=>1,':card_type'=>BankCard::store,':card_id'=>Yii::app()->store->id),
				'order'=>'Store_BankCard.id,Store_BankCard.is_default desc'
			)
		);
		return $store_model;
	}

}